<?php
require_once __DIR__ . '/db.php';

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function setting($key, $default = '') {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        foreach (db()->query('SELECT setting_key, setting_value FROM site_settings') as $row) {
            $cache[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $cache[$key] ?? $default;
}

function site_image($key, $default = '') {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        foreach (db()->query('SELECT image_key, path FROM site_images') as $row) {
            $cache[$row['image_key']] = $row['path'];
        }
    }
    return $cache[$key] ?? $default;
}

/**
 * Builds an absolute URL against the configured site domain (site_settings.site_domain).
 * Pass a relative path like "services.php"; omit it to use the current request path.
 */
function site_url($path = null) {
    $domain = rtrim(setting('site_domain', ''), '/');
    if ($path === null) {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    }
    return $domain . '/' . ltrim($path, '/');
}

/** Human-readable size of a file stored at a site-root-relative path, or null if missing. */
function admin_file_size($relativePath) {
    $abs = __DIR__ . '/../' . $relativePath;
    if (!is_file($abs)) {
        return null;
    }
    $bytes = filesize($abs);
    return $bytes < 1024 * 1024
        ? round($bytes / 1024, 1) . ' KB'
        : round($bytes / (1024 * 1024), 2) . ' MB';
}

function lines_to_array($text) {
    $lines = preg_split('/\r\n|\r|\n/', trim((string) $text));
    return array_values(array_filter(array_map('trim', $lines), fn($l) => $l !== ''));
}

// Category -> [tag label, tag css class]
function project_category_meta($category) {
    $map = [
        'health'   => ['Healthcare', 'tag-health'],
        'agri'     => ['AgriTech', 'tag-agri'],
        'restate'  => ['Real Estate', 'tag-restate'],
        'business' => ['Business', 'tag-business'],
        'church'   => ['Church', 'tag-church'],
        'edu'      => ['Education', 'tag-edu'],
        'fintech'  => ['FinTech', 'tag-fintech'],
        'suite'    => ['Amex Product', 'tag-suite'],
    ];
    return $map[$category] ?? [ucfirst($category), 'tag-business'];
}

/**
 * Validate and store an uploaded image. Returns the stored relative path
 * (e.g. "img/uploads/team/xyz.jpg") on success, or null if no file was
 * uploaded. Throws RuntimeException on a bad upload.
 *
 * If the GD extension is available, the image is automatically resized
 * (longest side capped at $maxDimension) and re-compressed at $quality
 * (1-100), so large phone photos or unoptimized exports don't bloat the site.
 */
function upload_image($fieldName, $subdir, $maxDimension = 1920, $quality = 82) {
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$fieldName];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload failed. Please try again.');
    }
    if ($file['size'] > 15 * 1024 * 1024) {
        throw new RuntimeException('Image must be smaller than 15MB.');
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];
    $mime = mime_content_type($file['tmp_name']);
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Only JPG, PNG, WEBP, or GIF images are allowed.');
    }

    $destDir = __DIR__ . '/../img/uploads/' . $subdir;
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $extension = $allowed[$mime];
    $filename = bin2hex(random_bytes(8)) . '.' . $extension;
    $destPath = $destDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        throw new RuntimeException('Could not save the uploaded image.');
    }

    if (extension_loaded('gd')) {
        $quality = max(1, min(100, (int) $quality));
        compress_image_in_place($destPath, $mime, $maxDimension, $quality);
    }

    return 'img/uploads/' . $subdir . '/' . $filename;
}

/**
 * Resizes (if larger than $maxDimension on the longest side) and
 * re-compresses an image file on disk, in its original format. Silently
 * leaves the original file untouched if GD can't decode it.
 */
function compress_image_in_place($path, $mime, $maxDimension, $quality = 82) {
    try {
        $image = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png'  => imagecreatefrompng($path),
            'image/webp' => imagecreatefromwebp($path),
            'image/gif'  => imagecreatefromgif($path),
            default      => null,
        };
        if (!$image) {
            return;
        }

        // Correct sideways phone photos before resizing.
        if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
            $exif = @exif_read_data($path);
            $orientation = $exif['Orientation'] ?? 1;
            $rotateBy = ['3' => 180, '6' => -90, '8' => 90][(string) $orientation] ?? 0;
            if ($rotateBy !== 0) {
                $rotated = imagerotate($image, $rotateBy, 0);
                if ($rotated !== false) {
                    imagedestroy($image);
                    $image = $rotated;
                }
            }
        }

        $width = imagesx($image);
        $height = imagesy($image);

        if (max($width, $height) > $maxDimension) {
            $ratio = $maxDimension / max($width, $height);
            $newWidth = (int) round($width * $ratio);
            $newHeight = (int) round($height * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            if ($mime === 'image/png' || $mime === 'image/gif') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
                imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
            }
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($image, $path, $quality);
                break;
            case 'image/png':
                // PNG is lossless; "quality" instead controls compression effort (0=none, 9=max).
                imagepng($image, $path, (int) round((100 - $quality) / 100 * 9));
                break;
            case 'image/webp':
                imagewebp($image, $path, $quality);
                break;
            case 'image/gif':
                imagegif($image, $path);
                break;
        }
        imagedestroy($image);
    } catch (Throwable $e) {
        // If anything goes wrong, keep the originally uploaded file as-is.
    }
}

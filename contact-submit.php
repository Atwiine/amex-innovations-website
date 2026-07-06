<?php
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

function respond($ok, $msg) {
    echo json_encode(['success' => $ok, 'message' => $msg]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Invalid request method.');
}

// Honeypot — bots fill hidden fields
if (!empty($_POST['hp'])) {
    respond(true, 'Thank you!');
}

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$phone   = trim($_POST['phone']   ?? '');
$service = trim($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
    respond(false, 'Please fill in your name, email and message.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Please enter a valid email address.');
}
if (strlen($message) < 10) {
    respond(false, 'Your message is too short — please give us a bit more detail.');
}

$companyEmail = setting('company_email', 'amexinnovationslt@gmail.com');
$companyPhone = setting('company_phone', '+256 779 008858');

$dbOk = false;
try {
    $stmt = db()->prepare(
        'INSERT INTO contact_messages (name, email, phone, service, message) VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([$name, $email, $phone, $service, $message]);
    $dbOk = true;
} catch (Throwable $e) {
    // Continue even if the DB insert fails — we still try to email the team.
}

$subject = 'New enquiry from Amex website' . ($service ? ' — ' . $service : '');
$body    = "Name:    {$name}\n"
         . "Email:   {$email}\n"
         . "Phone:   {$phone}\n"
         . "Service: {$service}\n\n"
         . "Message:\n{$message}\n\n"
         . "-- Sent from amexinnovations.com";

$headers = implode("\r\n", [
    "From: Amex Website <no-reply@amexinnovations.com>",
    "Reply-To: {$name} <{$email}>",
    "MIME-Version: 1.0",
    "Content-Type: text/plain; charset=UTF-8",
]);

$sent = @mail($companyEmail, $subject, $body, $headers);

if ($dbOk || $sent) {
    respond(true, "Thanks {$name}! We received your message and will get back to you within 24 hours.");
} else {
    respond(false, "We could not send your message right now. Please email us directly at {$companyEmail} or call {$companyPhone}.");
}

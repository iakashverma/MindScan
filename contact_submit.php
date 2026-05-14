<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('contact.php');
}

$name = post_string('contact_name');
$email = post_string('contact_email');
$message = post_string('contact_message');

if ($name === '' || $email === '' || $message === '') {
    redirect('contact.php?status=error');
}

$to = 'research-team@example.com';
$subject = 'MindScan Contact Form';
$body = "Name: {$name}\nEmail: {$email}\nMessage: {$message}\n";
$headers = "From: {$email}\r\nReply-To: {$email}\r\n";

$sent = @mail($to, $subject, $body, $headers);

redirect('contact.php?status=' . ($sent ? 'sent' : 'error'));

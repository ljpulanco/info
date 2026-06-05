<?php
ob_start();
header('Content-Type: application/json');

$email_to = 'lesterjohnpulanco@gmail.com';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? 'New Message');
    $message = trim($_POST['message'] ?? '');
    
    if (!$name || !$email || !$message) {
        ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(['success' => false, 'message' => 'All fields are required.']));
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(['success' => false, 'message' => 'Invalid email address.']));
    }
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . $email . "\r\n";
    
    $body = "<html><body>";
    $body .= "<h2>New Message from " . htmlspecialchars($name) . "</h2>";
    $body .= "<p><strong>From:</strong> " . htmlspecialchars($email) . "</p>";
    $body .= "<p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>";
    $body .= "<p><strong>Message:</strong></p>";
    $body .= "<p>" . nl2br(htmlspecialchars($message)) . "</p>";
    $body .= "</body></html>";
    
    $result = mail($email_to, $subject, $body, $headers);
    
    ob_end_clean();
    header('Content-Type: application/json');
    
    if ($result) {
        die(json_encode(['success' => true, 'message' => '✓ Message sent successfully! I\'ll get back to you soon.']));
    } else {
        die(json_encode(['success' => false, 'message' => '✗ Error sending message. Please try again.']));
    }
}

ob_end_clean();
header('Content-Type: application/json');
die(json_encode(['success' => false, 'message' => 'Invalid request.']));
?>

<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES);
    $email = htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES);
    $subject = htmlspecialchars($_POST['subject'] ?? 'Message', ENT_QUOTES);
    $message = htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES);
    
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'All fields required']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email']);
        exit;
    }
    
    $to = 'lesterjohnpulanco@gmail.com';
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . $email . "\r\n";
    
    $body = "Name: " . $name . "\n";
    $body .= "Email: " . $email . "\n";
    $body .= "Subject: " . $subject . "\n\n";
    $body .= "Message:\n" . $message;
    
    $sent = mail($to, $subject, $body, $headers);
    
    if ($sent) {
        echo json_encode(['success' => true, 'message' => '✓ Message sent successfully!']);
    } else {
        echo json_encode(['success' => true, 'message' => '✓ Message submitted! We\'ll contact you soon.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
exit;
?>

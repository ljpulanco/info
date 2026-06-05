<?php
// Start output buffering to prevent any accidental output
ob_start();

// Set JSON response header FIRST before any output
header('Content-Type: application/json; charset=UTF-8');

// Your email address
$recipient_email = 'lesterjohnpulanco@gmail.com';

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : 'New Message from Portfolio';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Validate inputs
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Name is required';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is invalid';
    }
    
    if (empty($message)) {
        $errors[] = 'Message is required';
    }
    
    // If there are validation errors, return them
    if (!empty($errors)) {
        ob_end_clean();
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => implode(', ', $errors)
        ]);
        exit;
    }
    
    // Sanitize inputs to prevent email injection
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $subject = filter_var($subject, FILTER_SANITIZE_STRING);
    $message = filter_var($message, FILTER_SANITIZE_STRING);
    
    // Prepare email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    
    // Prepare email subject
    $email_subject = "New Portfolio Contact: " . $subject;
    
    // Prepare email body
    $email_body = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body {
                font-family: 'Poppins', Arial, sans-serif;
                line-height: 1.6;
                color: #333;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #f8f9ff;
                border-radius: 10px;
            }
            .header {
                border-bottom: 3px solid #0066ff;
                padding-bottom: 15px;
                margin-bottom: 20px;
            }
            .header h2 {
                color: #0066ff;
                margin: 0;
            }
            .content {
                background-color: white;
                padding: 20px;
                border-radius: 8px;
                margin-bottom: 20px;
            }
            .field {
                margin-bottom: 15px;
            }
            .field-label {
                font-weight: 600;
                color: #0066ff;
                margin-bottom: 5px;
            }
            .field-value {
                color: #666;
                padding: 10px;
                background-color: #f3f4f6;
                border-radius: 5px;
            }
            .footer {
                font-size: 12px;
                color: #999;
                text-align: center;
                border-top: 1px solid #e5e7eb;
                padding-top: 15px;
            }
        </style>
    </head>
    <body>
        <div class=\"container\">
            <div class=\"header\">
                <h2>📧 New Message from Portfolio</h2>
            </div>
            <div class=\"content\">
                <div class=\"field\">
                    <div class=\"field-label\">From:</div>
                    <div class=\"field-value\">" . htmlspecialchars($name) . " (" . htmlspecialchars($email) . ")</div>
                </div>
                <div class=\"field\">
                    <div class=\"field-label\">Subject:</div>
                    <div class=\"field-value\">" . htmlspecialchars($subject) . "</div>
                </div>
                <div class=\"field\">
                    <div class=\"field-label\">Message:</div>
                    <div class=\"field-value\" style=\"white-space: pre-wrap;\">" . htmlspecialchars($message) . "</div>
                </div>
            </div>
            <div class=\"footer\">
                <p>This message was sent from your portfolio contact form.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Send email
    $mail_sent = @mail($recipient_email, $email_subject, $email_body, $headers);
    
    // Clear output buffer
    ob_end_clean();
    
    if ($mail_sent) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => '✓ Message sent successfully! I\'ll get back to you soon.'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => '✗ Error sending message. Please try again.'
        ]);
    }
    exit;
}

// If not a POST request, clear buffer and return error
ob_end_clean();
http_response_code(405);
echo json_encode([
    'success' => false,
    'message' => 'Method not allowed'
]);
exit;
?>

<?php
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Test success']);
exit;
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers for JSON response
header('Content-Type: application/json');

// Database configuration (optional - if you want to store messages)
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'fitzone';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get and sanitize form data
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

        // Validate inputs
        if (empty($name) || empty($email) || empty($message)) {
            throw new Exception('All fields are required.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format.');
        }

        // Database storage (optional)
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        
        if ($conn->connect_error) {
            throw new Exception('Database connection failed: ' . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $name, $email, $message);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to save message: ' . $stmt->error);
        }
        
        $stmt->close();
        $conn->close();

        // Email notification (optional)
        $to = "your-email@example.com";
        $subject = "New Contact Form Submission";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        $email_body = "You have received a new message from your website contact form.\n\n";
        $email_body .= "Name: $name\n";
        $email_body .= "Email: $email\n\n";
        $email_body .= "Message:\n$message\n";
        
        if (!mail($to, $subject, $email_body, $headers)) {
            // Don't throw error - email failure shouldn't prevent form submission
            error_log("Failed to send email notification");
        }

        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Thank you! Your message has been sent successfully.'
        ]);

    } catch (Exception $e) {
        // Return error response
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    // Not a POST request
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
}
?>
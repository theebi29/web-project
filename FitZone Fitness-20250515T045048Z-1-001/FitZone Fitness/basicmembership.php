<?php
// Database configuration
$db_host = 'localhost';
$db_username = 'root'; // Change to your database username
$db_password = ''; // Change to your database password
$db_name = 'fitzone';

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    
    // Basic validation
    if (empty($first_name)) {
        die("First name is required");
    }
    if (empty($last_name)) {
        die("Last name is required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    if (empty($phone)) {
        die("Phone number is required");
    }
    
    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO members (first_name, last_name, email, phone, plan_type, registration_date) VALUES (?, ?, ?, ?, 'Basic', NOW())");
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $phone);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Redirect back to form with success status
        header("Location: membership_sucess.html");
        exit();
    } else {
        // Redirect back to form with error status
        header("Location: index.html?status=error");
        exit();
    }
    
    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
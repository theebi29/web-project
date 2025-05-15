<?php

$db_host = 'localhost';
$db_username = 'root'; 
$db_password = ''; 
$db_name = 'fitzone';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    
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
    
    $stmt = $conn->prepare("INSERT INTO premium_members (first_name, last_name, email, phone, plan_type, registration_date) VALUES (?, ?, ?, ?, 'premium', NOW())");
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $phone);
    
    if ($stmt->execute()) {
        header("Location: premium_sucess.html");
        exit();
    } else {
        header("Location: index.html?status=error");
        exit();
    }
    
    $stmt->close();
}
$conn->close();
?>
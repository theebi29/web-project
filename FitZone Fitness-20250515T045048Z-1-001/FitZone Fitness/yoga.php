<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "fitzone"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $membership = $_POST['membership'];
    $schedule = $_POST['schedule'];
    $experience = $_POST['experience'] ?? 'beginner'; 

 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    
    $sql = "INSERT INTO yoga_registrations (name, email, phone, membership_type, preferred_time, experience_level) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    
    $stmt->bind_param("ssssss", $name, $email, $phone, $membership, $schedule, $experience);

    
    if ($stmt->execute()) {
        
        header("Location: registration-success.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "fitzone";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        die("Please enter a username.");
    } else {
        $username = trim($_POST["username"]);
    }
    
    // Validate password
    if (empty(trim($_POST["password"]))) {
        die("Please enter a password.");
    } elseif (strlen(trim($_POST["password"])) < 5) {
        die("Password must have at least 5 characters.");
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        die("Please confirm password.");
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password != $confirm_password) {
            die("Password did not match.");
        }
    }
    
    // Check if username already exists
    $sql = "SELECT id FROM users WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $param_username);
        $param_username = $username;
        
        if ($stmt->execute()) {
            $stmt->store_result();
            
            if ($stmt->num_rows == 1) {
                echo "This username is already taken.";
            } else {
                // Insert new user
                $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ss", $param_username, $param_password);
                    
                    $param_username = $username;
                    $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
                    
                    if ($stmt->execute()) {
                        // Redirect to index.html after successful registration
                        header("Location: index.html");
                        exit(); // Make sure no further code is executed after redirect
                    } else {
                        echo "Something went wrong. Please try again later.";
                    }
                }
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        
        $stmt->close();
    }
    
    $conn->close();
}
?>
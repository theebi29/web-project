<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitzone";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        die("Please enter username.");
    } else {
        $username = trim($_POST["username"]);
    }
    if (empty(trim($_POST["password"]))) {
        die("Please enter your password.");
    } else {
        $password = trim($_POST["password"]);
    }
    
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $param_username);
        $param_username = $username;
        
        if ($stmt->execute()) {
            $stmt->store_result();
            
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username, $hashed_password);
                if ($stmt->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        session_start();
                        
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;
                        
                        header("Location: index.html");
                        exit();
                    } else {
                        header("Location: login.html?error=1");
                        exit();
                    }
                }
            } else {
                header("Location: login.html?error=1");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        
        $stmt->close();
    }
    
    $conn->close();
}
?>
<?php
session_start();
$db_host = "localhost";
$db_user = "root"; 
$db_pass = ""; 
$db_name = "fitzone";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = $_POST['user_type'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $username = mysqli_real_escape_string($conn, $username);
    
    $sql = "SELECT * FROM admin_staff_login WHERE username = '$username' AND user_type = '$user_type'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_type'] = $row['user_type'];
            
            if ($user_type == 'admin') {
                header("Location: admin.html");
                exit();
            } elseif ($user_type == 'staff') {
                header("Location: staff.html");
                exit();
            } else {
                // Unknown user type
                $error = "Unknown user type";
                header("Location: login.html?error=" . urlencode($error));
                exit();
            }
        } else {
            // Invalid password
            $error = "Invalid username or password";
        }
    } else {
        // User not found
        $error = "Invalid username or password";
    }

    // Redirect to correct login page on error
    if ($user_type == 'admin') {
        header("Location: admin.html?error=" . urlencode($error));
    } elseif ($user_type == 'staff') {
        header("Location: staff.html?error=" . urlencode($error));
    } else {
        header("Location: login.html?error=" . urlencode($error));
    }
    exit();
}

$conn->close();
?>

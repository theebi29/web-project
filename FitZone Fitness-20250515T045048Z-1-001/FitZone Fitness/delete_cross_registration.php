<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB config
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitzone";

// Connect
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete logic
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM cross_registrations WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: view_cross_registration.php?message=deleted");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Prepare failed: " . $conn->error;
    }
} else {
    echo "Invalid ID.";
}

$conn->close();
?>

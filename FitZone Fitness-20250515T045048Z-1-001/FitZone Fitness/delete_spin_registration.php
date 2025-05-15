<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitzone";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is provided via GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare the delete statement
    $sql = "DELETE FROM spin_registrations WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Redirect back to view page after deletion
            header("Location: view_spin_registration.php");
            exit();
        } else {
            echo "Error executing delete: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing delete statement: " . $conn->error;
    }
} else {
    echo "Invalid or missing ID.";
}

$conn->close();
?>

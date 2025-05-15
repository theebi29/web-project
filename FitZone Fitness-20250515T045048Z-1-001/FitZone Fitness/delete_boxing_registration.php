<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitzone";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an ID was passed via GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM boxing_registrations WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);

        // Execute the deletion
        if ($stmt->execute()) {
            // Redirect after successful deletion
            header("Location: view_box_registration.php?message=deleted");
            exit();
        } else {
            echo "Error executing delete statement: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Invalid ID provided.";
}

$conn->close();
?>

<?php
$conn = new mysqli("localhost", "root", "", "fitzone");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    
    $stmt = $conn->prepare("DELETE FROM add_fitzone_members WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: view_members.php?delete_success=1");
    } else {
        header("Location: view_members.php?delete_error=1");
    }
    
    $stmt->close();
} else {
    header("Location: view_members.php");
}

$conn->close();
exit();
?>
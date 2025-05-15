<?php
// Simple MySQL connection test
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>MySQL Connection Test</h2>";

// Try to connect
$conn = new mysqli('localhost', 'root', ''); // Default XAMPP credentials

if ($conn->connect_error) {
    die("<p style='color:red'>Connection failed: " . $conn->connect_error . "</p>");
} else {
    echo "<p style='color:green'>✅ Connected successfully to MySQL server!</p>";
    
    // Test database selection
    if ($conn->select_db('fitzon')) {
        echo "<p style='color:green'>✅ Database 'fitZone' selected successfully</p>";
        
        // Test table existence
        $result = $conn->query("SHOW TABLES LIKE 'members'");
        if ($result->num_rows > 0) {
            echo "<p style='color:green'>✅ 'members' table exists</p>";
        } else {
            echo "<p style='color:orange'>⚠️ 'members' table doesn't exist</p>";
        }
    } else {
        echo "<p style='color:red'>❌ Could not select database: " . $conn->error . "</p>";
    }
}

$conn->close();
?>
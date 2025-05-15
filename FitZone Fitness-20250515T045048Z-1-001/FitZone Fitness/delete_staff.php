<?php
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

// Check if ID parameter exists
if (!isset($_GET['id'])) {
    header("Location: view_staff.php");
    exit();
}

$id = intval($_GET['id']);

// Process deletion if confirmation is received
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    // Prepare and execute delete statement
    $stmt = $conn->prepare("DELETE FROM staff WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $message = "Staff member deleted successfully";
    } else {
        $message = "Error deleting staff member: " . $stmt->error;
    }
    $stmt->close();
    
    // Redirect to view staff page with success/error message
    header("Location: view_staff.php?message=" . urlencode($message));
    exit();
}

// Fetch staff data for confirmation display
$stmt = $conn->prepare("SELECT first_name, last_name FROM staff WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();
$stmt->close();

if (!$staff) {
    header("Location: view_staff.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Staff Member</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(145deg, #1e2a3a 0%, #0f172a 100%);
            color: white;
            min-height: 100vh;
            padding: 40px;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: rgba(15, 23, 42, 0.8);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        h1 {
            color: #f59e0b;
            margin-bottom: 20px;
        }
        
        .confirmation-message {
            margin-bottom: 30px;
            font-size: 18px;
        }
        
        .staff-name {
            color: #f59e0b;
            font-weight: bold;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s;
            text-decoration: none;
            border: none;
        }
        
        .btn-danger {
            background-color: #ef4444;
            color: white;
        }
        
        .btn-secondary {
            background-color: #4b5563;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Staff Member</h1>
        
        <div class="confirmation-message">
            Are you sure you want to permanently delete staff member 
            <span class="staff-name"><?php echo htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']); ?></span>
        </div>
        
        <div class="btn-group">
            <a href="delete_staff.php?id=<?php echo $id; ?>&confirm=yes" class="btn btn-danger">Yes, Delete</a>
            <a href="view_staff.php" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "fitzone");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get member ID from URL parameter
$member_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch specific member from the database
$sql = "SELECT * FROM add_fitzone_members WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Details</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .member-card { 
            max-width: 600px; 
            margin: 0 auto; 
            padding: 20px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }
        .member-card h2 { 
            text-align: center; 
            color: #333; 
            margin-bottom: 20px; 
        }
        .detail-row { 
            display: flex; 
            margin-bottom: 10px; 
        }
        .detail-label { 
            font-weight: bold; 
            width: 150px; 
        }
        .action-btns { 
            margin-top: 20px; 
            text-align: center; 
        }
        .btn { 
            padding: 8px 15px; 
            text-decoration: none; 
            border-radius: 4px; 
            margin: 0 5px; 
        }
        .edit-btn { background-color: #4CAF50; color: white; }
        .delete-btn { background-color: #f44336; color: white; }
        .back-btn { background-color: #2196F3; color: white; }
    </style>
</head>
<body>
    <?php if ($member): ?>
        <div class="member-card">
            <h2>Member Details</h2>
            
            <div class="detail-row">
                <div class="detail-label">ID:</div>
                <div><?php echo htmlspecialchars($member['id']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">First Name:</div>
                <div><?php echo htmlspecialchars($member['FName']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Last Name:</div>
                <div><?php echo htmlspecialchars($member['LName']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">NIC:</div>
                <div><?php echo htmlspecialchars($member['NIC']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Email:</div>
                <div><?php echo htmlspecialchars($member['Email']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Address:</div>
                <div><?php echo htmlspecialchars($member['Address']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Gender:</div>
                <div><?php echo htmlspecialchars($member['gender']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Date of Birth:</div>
                <div><?php echo htmlspecialchars($member['Date']); ?></div>
            </div>
            
            <div class="action-btns">
                <a href="edit_member.php?id=<?php echo $member['id']; ?>" class="btn edit-btn">Edit</a>
                <a href="delete_member.php?id=<?php echo $member['id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this member?')">Delete</a>
                <a href="view_members.php" class="btn back-btn">Back to List</a>
            </div>
        </div>
    <?php else: ?>
        <p style="text-align: center;">Member not found.</p>
        <p style="text-align: center;"><a href="view_members.php">Back to Member List</a></p>
    <?php endif; ?>
    
    <?php $conn->close(); ?>
</body>
</html>
<?php

$conn = new mysqli("localhost", "root", "", "fitzone");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM add_fitzone_members";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fitzone Members</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .action-btns { display: flex; gap: 5px; }
        .btn { padding: 5px 10px; text-decoration: none; border-radius: 4px; }
        .edit-btn { background-color: #4CAF50; color: white; }
        .delete-btn { background-color: #f44336; color: white; }
    </style>
</head>
<body>
    <h1>Fitzone Member List</h1>
    
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>NIC</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['FName']); ?></td>
                        <td><?php echo htmlspecialchars($row['LName']); ?></td>
                        <td><?php echo htmlspecialchars($row['NIC']); ?></td>
                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td><?php echo htmlspecialchars($row['Address']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td><?php echo htmlspecialchars($row['Date']); ?></td>
                        <td class="action-btns">
                            <a href="member_details.php?id=<?php echo $row['id']; ?>" class="btn edit-btn">Edit</a>
                            <a href="delete_member.php?id=<?php echo $row['id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this member?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No members found in the database.</p>
    <?php endif; ?>
    
    <?php $conn->close(); ?>
</body>
</html>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitzone";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM staff ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Staff Members</title>
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
            max-width: 1200px;
            margin: 0 auto;
            background-color: rgba(15, 23, 42, 0.8);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: #f59e0b;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .staff-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .staff-table th, .staff-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .staff-table th {
            background-color: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }
        
        .staff-table tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .action-btns {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-edit {
            background-color: #f59e0b;
            color: #1e2a3a;
            border: none;
        }
        
        .btn-delete {
            background-color: #ef4444;
            color: white;
            border: none;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .no-staff {
            text-align: center;
            padding: 20px;
            color: #f59e0b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Staff Members</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <table class="staff-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Staff ID</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Sections</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['staff_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['sections']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
                            <td class="action-btns">
                                <a href="edit_staff.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                                <a href="delete_staff.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this staff member?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-staff">
                <p>No staff members found in the database.</p>
                <p><a href="add_staff.html" style="color: #f59e0b;">Add a new staff member</a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>
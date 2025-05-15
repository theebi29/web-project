<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitzone";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM yoga_registrations ORDER BY registration_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Yoga Class Registrations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 3px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9e9e9;
        }
        .action-btns {
            display: flex;
            gap: 5px;
        }
        .btn {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .edit-btn {
            background-color: #2ecc71;
            color: white;
        }
        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <h1>Yoga Class Registrations</h1>
    
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Membership</th>
                    <th>Preferred Time</th>
                    <th>Experience Level</th>
                    <th>Registration Date</th>
                    <!-- <th>Actions</th> -->
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo isset($row['id']) ? htmlspecialchars($row['id']) : ''; ?></td>
                        <td><?php echo isset($row['name']) ? htmlspecialchars($row['name']) : ''; ?></td>
                        <td><?php echo isset($row['email']) ? htmlspecialchars($row['email']) : ''; ?></td>
                        <td><?php echo isset($row['phone']) ? htmlspecialchars($row['phone']) : ''; ?></td>
                        <td><?php echo isset($row['membership_type']) ? htmlspecialchars($row['membership_type']) : ''; ?></td>
                        <td><?php echo isset($row['preferred_time']) ? htmlspecialchars($row['preferred_time']) : ''; ?></td>
                        <td><?php echo isset($row['experience_level']) ? htmlspecialchars($row['experience_level']) : ''; ?></td>
                        <td><?php echo isset($row['registration_date']) ? htmlspecialchars($row['registration_date']) : ''; ?></td>
                       
                    </tr>

                    
                    
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-data">No yoga registrations found in the database.</p>
    <?php endif; ?>
    
    <?php $conn->close(); ?>
</body>
</html>
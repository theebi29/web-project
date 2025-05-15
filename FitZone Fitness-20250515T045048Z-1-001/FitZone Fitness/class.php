<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitzone";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT day, time, class_name, instructor, duration FROM fitness_classes ORDER BY 
        FIELD(day, 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'), 
        time";
$result = $conn->query($sql);
$classes = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Class Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(145deg, #1e2a3a 0%, #0f172a 100%);
            color: #fff;
        }
        
        .schedule-container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: rgba(30, 42, 58, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        h1 {
            text-align: center;
            color: #f59e0b;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th {
            background-color: #f59e0b;
            color: #0f172a;
            padding: 12px;
            text-align: left;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid rgba(245, 158, 11, 0.1);
            color: #eee;
        }
        
        tr:nth-child(even) {
            background-color: rgba(15, 23, 42, 0.3);
        }
        
        tr:hover {
            background-color: rgba(245, 158, 11, 0.1);
        }
        
        .class-time {
            font-weight: bold;
            color: #f59e0b;
        }
        
        .class-name {
            font-weight: bold;
        }
        
        .class-instructor {
            color: #ccc;
            font-size: 14px;
        }
        
        .class-duration {
            color: #999;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="schedule-container">
        <h1>Weekly Fitness Class Schedule</h1>
        
        <table>
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Class</th>
                    <th>Instructor</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($classes) > 0): ?>
                    <?php foreach ($classes as $class): ?>
                        <tr>
                            <td><?= ucfirst(htmlspecialchars($class['day'] ?? '')) ?></td>
                            <td class="class-time"><?= htmlspecialchars($class['time'] ?? '') ?></td>
                            <td class="class-name"><?= htmlspecialchars($class['class_name'] ?? '') ?></td>
                            <td class="class-instructor"><?= htmlspecialchars($class['instructor'] ?? '') ?></td>
                            <td class="class-duration"><?= htmlspecialchars($class['duration'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No classes scheduled</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
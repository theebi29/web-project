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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new class
    if (isset($_POST['add_class'])) {
        $day = $conn->real_escape_string($_POST['day']);
        $time = $conn->real_escape_string($_POST['time']);
        $class_name = $conn->real_escape_string($_POST['class_name']);
        $instructor = $conn->real_escape_string($_POST['instructor']);
        $duration = $conn->real_escape_string($_POST['duration']);

        $sql = "INSERT INTO fitness_classes (day, time, class_name, instructor, duration) 
                VALUES ('$day', '$time', '$class_name', '$instructor', '$duration')";
        
        if ($conn->query($sql) === TRUE) {
            $message = "Class added successfully!";
        } else {
            $error = "Error adding class: " . $conn->error;
        }
    }
    
    // Update class
    if (isset($_POST['update_class'])) {
        $id = intval($_POST['id']);
        $day = $conn->real_escape_string($_POST['day']);
        $time = $conn->real_escape_string($_POST['time']);
        $class_name = $conn->real_escape_string($_POST['class_name']);
        $instructor = $conn->real_escape_string($_POST['instructor']);
        $duration = $conn->real_escape_string($_POST['duration']);

        $sql = "UPDATE fitness_classes SET 
                day = '$day',
                time = '$time',
                class_name = '$class_name',
                instructor = '$instructor',
                duration = '$duration'
                WHERE id = $id";
        
        if ($conn->query($sql) === TRUE) {
            $message = "Class updated successfully!";
        } else {
            $error = "Error updating class: " . $conn->error;
        }
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM fitness_classes WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Class deleted successfully!";
    } else {
        $error = "Error deleting class: " . $conn->error;
    }
}

// Fetch all classes from database
$sql = "SELECT * FROM fitness_classes ORDER BY 
        FIELD(day, 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'), 
        time";
$result = $conn->query($sql);
$classes = [];
while ($row = $result->fetch_assoc()) {
    $classes[] = $row;
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Schedule Management</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(145deg, #1e2a3a 0%, #0f172a 100%);
            color: #fff;
        }

        /* Section Styles */
        .schedule-section {
            padding: 50px 20px;
            background-color: rgba(30, 42, 58, 0.8);
            max-width: 1200px;
            margin: 40px auto;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .title {
            font-size: 32px;
            color: #f59e0b;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .description {
            font-size: 16px;
            color: #ccc;
            max-width: 700px;
            margin: 0 auto 30px;
            text-align: center;
        }

        /* Schedule Tabs */
        .schedule-tabs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .tab {
            padding: 12px 18px;
            background-color: rgba(15, 23, 42, 0.7);
            color: #f59e0b;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .tab:hover {
            background-color: rgba(245, 158, 11, 0.1);
        }

        .tab.active {
            background-color: #f59e0b;
            color: #0f172a;
            border-color: #f59e0b;
        }

        /* Schedule Table */
        .schedule-table-container {
            overflow-x: auto;
            border-radius: 8px;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            background-color: rgba(30, 42, 58, 0.9);
        }

        .schedule-table th {
            background-color: #f59e0b;
            color: #0f172a;
            padding: 15px;
            text-align: left;
        }

        .schedule-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(245, 158, 11, 0.1);
            vertical-align: middle;
            color: #eee;
        }

        .schedule-table tr:nth-child(even) {
            background-color: rgba(15, 23, 42, 0.3);
        }

        .schedule-table tr:hover {
            background-color: rgba(245, 158, 11, 0.1);
        }

        .class-time {
            font-weight: bold;
            color: #f59e0b;
            white-space: nowrap;
        }

        .class-name {
            font-weight: bold;
            color: #fff;
        }

        .class-instructor {
            color: #ccc;
            font-size: 14px;
        }

        .class-duration {
            color: #999;
            font-size: 13px;
            white-space: nowrap;
        }

        /* Admin Controls */
        .admin-controls {
            background-color: rgba(15, 23, 42, 0.7);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #f59e0b;
        }
        
        input, select {
            width: 100%;
            padding: 8px;
            background-color: rgba(30, 42, 58, 0.9);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: white;
            border-radius: 4px;
        }
        
        button {
            background-color: #f59e0b;
            color: #0f172a;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .edit-btn {
            background-color: #2ecc71;
        }
        
        .delete-btn {
            background-color: #e74c3c;
        }
        
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        
        .error {
            background-color: #f2dede;
            color: #a94442;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .schedule-section {
                padding: 30px 15px;
                margin: 20px auto;
            }
            
            .schedule-table {
                font-size: 14px;
            }
            
            .schedule-table th, 
            .schedule-table td {
                padding: 10px 8px;
            }
            
            .tab {
                padding: 10px 15px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <section class="schedule-section">
        <h2 class="title">Weekly Class Schedule Management</h2>
        
        <?php if (isset($message)): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Admin Controls -->
        <div class="admin-controls">
            <h3>Add/Edit Class</h3>
            <form method="POST">
                <input type="hidden" name="id" id="edit_id" value="">
                <div class="form-group">
                    <label for="day">Day:</label>
                    <select name="day" id="day" required>
                        <option value="sunday">Sunday</option>
                        <option value="monday">Monday</option>
                        <option value="tuesday">Tuesday</option>
                        <option value="wednesday">Wednesday</option>
                        <option value="thursday">Thursday</option>
                        <option value="friday">Friday</option>
                        <option value="saturday">Saturday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="time">Time:</label>
                    <input type="text" name="time" id="time" placeholder="9:00am - 10:30am" required>
                </div>
                <div class="form-group">
                    <label for="class_name">Class Name:</label>
                    <input type="text" name="class_name" id="class_name" required>
                </div>
                <div class="form-group">
                    <label for="instructor">Instructor:</label>
                    <input type="text" name="instructor" id="instructor" required>
                </div>
                <div class="form-group">
                    <label for="duration">Duration:</label>
                    <input type="text" name="duration" id="duration" placeholder="90 minutes" required>
                </div>
                <button type="submit" name="add_class" id="add_btn">Add Class</button>
                <button type="submit" name="update_class" id="update_btn" style="display:none;">Update Class</button>
                <button type="button" id="cancel_btn" style="display:none;">Cancel</button>
            </form>
        </div>

        <!-- Tabs for each day -->
        <div class="schedule-tabs">
            <span class="tab active" data-day="sunday">Sunday</span>
            <span class="tab" data-day="monday">Monday</span>
            <span class="tab" data-day="tuesday">Tuesday</span>
            <span class="tab" data-day="wednesday">Wednesday</span>
            <span class="tab" data-day="thursday">Thursday</span>
            <span class="tab" data-day="friday">Friday</span>
            <span class="tab" data-day="saturday">Saturday</span>
        </div>

        <!-- Schedule Table -->
        <div class="schedule-table-container">
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Class</th>
                        <th>Instructor</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="schedule-content">
                    <?php foreach ($classes as $class): ?>
                        <tr class="schedule-day" id="<?php echo $class['day']; ?>">
                            <td class="class-time"><?php echo htmlspecialchars($class['time']); ?></td>
                            <td class="class-name"><?php echo htmlspecialchars($class['class_name']); ?></td>
                            <td class="class-instructor"><?php echo htmlspecialchars($class['instructor']); ?></td>
                            <td class="class-duration"><?php echo htmlspecialchars($class['duration']); ?></td>
                            <td>
                                <button class="edit-btn" onclick="editClass(<?php echo $class['id']; ?>)">Edit</button>
                                <button class="delete-btn" onclick="deleteClass(<?php echo $class['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <script>
        // Tab functionality
        document.addEventListener("DOMContentLoaded", function () {
            const tabs = document.querySelectorAll(".tab");
            const scheduleDays = document.querySelectorAll(".schedule-day");

            tabs.forEach(tab => {
                tab.addEventListener("click", function () {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove("active"));
                    this.classList.add("active");

                    // Hide all schedule-day rows
                    scheduleDays.forEach(day => day.style.display = "none");

                    // Show the selected day's rows
                    const selectedDay = this.getAttribute("data-day");
                    document.querySelectorAll(`.schedule-day#${selectedDay}`).forEach(row => {
                        row.style.display = "table-row";
                    });
                });
            });

            // Initialize by showing only Sunday
            scheduleDays.forEach(day => day.style.display = "none");
            document.querySelectorAll('.schedule-day#sunday').forEach(row => {
                row.style.display = "table-row";
            });
        });

        // Edit class function
        function editClass(id) {
            // Find the class with this ID in the PHP array
            <?php foreach ($classes as $class): ?>
                if (<?php echo $class['id']; ?> === id) {
                    document.getElementById('edit_id').value = <?php echo $class['id']; ?>;
                    document.getElementById('day').value = '<?php echo $class['day']; ?>';
                    document.getElementById('time').value = '<?php echo $class['time']; ?>';
                    document.getElementById('class_name').value = '<?php echo $class['class_name']; ?>';
                    document.getElementById('instructor').value = '<?php echo $class['instructor']; ?>';
                    document.getElementById('duration').value = '<?php echo $class['duration']; ?>';
                    
                    // Show update button and hide add button
                    document.getElementById('add_btn').style.display = 'none';
                    document.getElementById('update_btn').style.display = 'inline-block';
                    document.getElementById('cancel_btn').style.display = 'inline-block';
                    
                    // Scroll to form
                    document.querySelector('.admin-controls').scrollIntoView({ behavior: 'smooth' });
                }
            <?php endforeach; ?>
        }

        // Cancel edit function
        document.getElementById('cancel_btn').addEventListener('click', function() {
            document.getElementById('edit_id').value = '';
            document.getElementById('day').value = 'sunday';
            document.getElementById('time').value = '';
            document.getElementById('class_name').value = '';
            document.getElementById('instructor').value = '';
            document.getElementById('duration').value = '';
            
            document.getElementById('add_btn').style.display = 'inline-block';
            document.getElementById('update_btn').style.display = 'none';
            document.getElementById('cancel_btn').style.display = 'none';
        });

        // Delete class function
        function deleteClass(id) {
            if (confirm('Are you sure you want to delete this class?')) {
                window.location.href = 'class_view.php?delete=' + id;
            }
        }
    </script>

</body>
</html>
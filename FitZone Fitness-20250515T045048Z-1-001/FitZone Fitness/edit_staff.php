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

// Initialize variables
$staff = null;
$error = '';
$success = '';

// Check if ID parameter exists
if (!isset($_GET['id']) ){
    header("Location: view_staff.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch staff data
$stmt = $conn->prepare("SELECT * FROM staff WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();
$stmt->close();

if (!$staff) {
    header("Location: view_staff.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input data
    $firstName = sanitizeInput($_POST['firstName']);
    $lastName = sanitizeInput($_POST['lastName']);
    $staffId = sanitizeInput($_POST['staffId']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $sections = isset($_POST['sections']) ? $_POST['sections'] : [];
    
    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($staffId) || empty($email) || empty($phone)) {
        $error = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } else {
        // Convert sections array to comma-separated string
        $sectionsStr = !empty($sections) ? implode(', ', $sections) : '';
        
        // Update staff record
        $stmt = $conn->prepare("UPDATE staff SET first_name = ?, last_name = ?, staff_id = ?, email = ?, phone = ?, sections = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $firstName, $lastName, $staffId, $email, $phone, $sectionsStr, $id);
        
        if ($stmt->execute()) {
            $success = 'Staff member updated successfully';
            // Refresh staff data
            $staff['first_name'] = $firstName;
            $staff['last_name'] = $lastName;
            $staff['staff_id'] = $staffId;
            $staff['email'] = $email;
            $staff['phone'] = $phone;
            $staff['sections'] = $sectionsStr;
        } else {
            $error = 'Error updating staff: ' . $stmt->error;
        }
        $stmt->close();
    }
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Convert sections string to array
$currentSections = !empty($staff['sections']) ? explode(', ', $staff['sections']) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff Member</title>
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
        }
        
        h1 {
            color: #f59e0b;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        input, select {
            width: 100%;
            padding: 12px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .btn {
            background-color: #f59e0b;
            color: #1e2a3a;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #e69009;
        }
        
        .btn-secondary {
            background-color: #4b5563;
            margin-top: 15px;
        }
        
        .section-checkboxes {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
        }
        
        .checkbox-group input {
            width: auto;
            margin-right: 8px;
        }
        
        .alert {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: rgba(74, 222, 128, 0.2);
            border: 1px solid rgba(74, 222, 128, 0.5);
            color: #4ade80;
        }
        
        .alert-error {
            background-color: rgba(248, 113, 113, 0.2);
            border: 1px solid rgba(248, 113, 113, 0.5);
            color: #f87171;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Staff Member</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($staff['first_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($staff['last_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="staffId">Staff ID</label>
                <input type="text" id="staffId" name="staffId" value="<?php echo htmlspecialchars($staff['staff_id']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($staff['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($staff['phone']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Sections</label>
                <div class="section-checkboxes">
                    <?php
                    $allSections = ['yoga', 'boxing', 'gym', 'swimming', 'aerobics', 'pilates'];
                    foreach ($allSections as $section): ?>
                        <div class="checkbox-group">
                            <input type="checkbox" id="<?php echo $section; ?>" name="sections[]" value="<?php echo $section; ?>" 
                                <?php echo in_array($section, $currentSections) ? 'checked' : ''; ?>>
                            <label for="<?php echo $section; ?>"><?php echo ucfirst($section); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <button type="submit" class="btn">Update Staff Member</button>
            <a href="view_staff.php" class="btn btn-secondary">Back to Staff List</a>
        </form>
    </div>
</body>
</html>
<?php
$conn->close();
?>
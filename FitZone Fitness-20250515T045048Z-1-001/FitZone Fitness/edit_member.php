<?php

$conn = new mysqli("localhost", "root", "", "fitzone");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$member = [];
$update_success = false;
$update_error = false;


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $Fname = $conn->real_escape_string($_POST['firstname']);
    $Lname = $conn->real_escape_string($_POST['lastname']);
    $NIC = $conn->real_escape_string($_POST['nic']);
    $Email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $dob = $conn->real_escape_string($_POST['dob']);

   
    $sql = "UPDATE add_fitzone_members SET 
            FName = '$Fname', 
            LName = '$Lname', 
            NIC = '$NIC', 
            Email = '$Email', 
            Address = '$address', 
            gender = '$gender', 
            Date = '$dob' 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $update_success = true;
    } else {
        $update_error = true;
    }
}


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM add_fitzone_members WHERE id = $id";
    $result = $conn->query($sql);
    $member = $result->fetch_assoc();
    
    if (!$member) {
        die("Member not found");
    }
} else {
    die("No member ID specified");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Member</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-container { max-width: 600px; margin: 0 auto; }
        h1 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { 
            width: 100%; 
            padding: 8px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            box-sizing: border-box;
        }
        .btn { 
            padding: 10px 15px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        .update-btn { background-color: #4CAF50; }
        .cancel-btn { background-color: #f44336; }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            text-align: center;
        }
        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Member</h1>
        
        <?php if ($update_success): ?>
            <div class="alert alert-success">Member updated successfully!</div>
        <?php elseif ($update_error): ?>
            <div class="alert alert-danger">Error updating member. Please try again.</div>
        <?php endif; ?>
        
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
            
            <div class="form-group">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($member['FName']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($member['LName']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="nic">NIC:</label>
                <input type="text" id="nic" name="nic" value="<?php echo htmlspecialchars($member['NIC']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($member['Email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($member['Address']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="Male" <?php echo ($member['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo ($member['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo ($member['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($member['Date']); ?>" required>
            </div>
            
            <div class="form-group">
                <button type="submit" name="update" class="btn update-btn">Update Member</button>
                <a href="view_members.php" class="btn cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
    
    <?php $conn->close(); ?>
</body>
</html>
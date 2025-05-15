
<?php
$conn = new mysqli("localhost", "root", "", "fitzone");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!empty($_POST['firstname']) && !empty($_POST['lastname'])&&!empty($_POST['nic']) && !empty($_POST['email'])&&!empty($_POST['address']) && !empty($_POST['gender'])&& !empty($_POST['dob'])) {
    $Fname = $conn->real_escape_string($_POST['firstname']);
    $Lname = $conn->real_escape_string($_POST['lastname']);
    $NIC = $conn->real_escape_string($_POST['nic']);
    $Email = $conn->real_escape_string($_POST['email']);  
	$address = $conn->real_escape_string($_POST['address']);
    $gender = $conn->real_escape_string($_POST['gender']);
	  $dob = $conn->real_escape_string($_POST['dob']);
    
    $check = "SELECT * FROM  add_fitzone_members WHERE FName = '$Fname'";
    $qry = $conn->query($check);

    if ($qry->num_rows > 0) {
        echo "The username you have entered already exists. Please try another username.";
        echo '<a href="register.html">Try Again</a>';
        exit;
    }

    
    $query = "INSERT INTO  add_fitzone_members (FName,LName,NIC,Email,Address,gender,Date) VALUES
     ('$Fname', ' $Lname','$NIC', ' $Email','$address', '  $gender','$dob')";
    
    if ($conn->query($query) === TRUE) {
       
        header("Location: addmember_conformation.html");
        exit(); 
    
    } else {
        echo "Error: " . $conn->error;
    }
}
$conn->close();







?>
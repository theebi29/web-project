<?php
ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fitzone";

$response = ['success' => false, 'message' => ''];

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $required = ['firstName', 'lastName', 'staffId', 'email', 'phone'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception('All fields are required');
            }
        }
        $firstName = sanitizeInput($_POST['firstName']);
        $lastName = sanitizeInput($_POST['lastName']);
        $staffId = sanitizeInput($_POST['staffId']);
        $email = sanitizeInput($_POST['email']);
        $phone = sanitizeInput($_POST['phone']);
        $sections = isset($_POST['sections']) ? $_POST['sections'] : [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }
        $sectionsStr = !empty($sections) ? implode(', ', $sections) : '';
        
        $stmt = $conn->prepare("INSERT INTO staff (first_name, last_name, staff_id, email, phone, sections) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        
        $stmt->bind_param("ssssss", $firstName, $lastName, $staffId, $email, $phone, $sectionsStr);
        
        if ($stmt->execute()) {
            $response = [
                'success' => true,
                'message' => 'Staff member added successfully'
            ];
        } else {
            throw new Exception('Execute failed: ' . $stmt->error);
        }
        
        $stmt->close();
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
    echo json_encode($response);
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
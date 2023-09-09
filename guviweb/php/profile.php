<?php
session_start();
include 'db.php';
include 'auth.php';
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Perform Read operation (GET request)
    $id = $_SESSION['id'];
    $sql = "SELECT dob, phone, name,username FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $row = $result->fetch_assoc();
    $response['success'] = true;
    $response['data'] = $row;
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Perform Update operation (PUT request)
    parse_str(file_get_contents("php://input"), $putData);
    $dob = $putData['dob'];
    $phone = $putData['phone'];
    $name = $putData['name'];
    $username = $putData['username'];

    // Perform validation and sanitization

    // Update user details in the database
    $id = $_SESSION['id'];
    $sql = "UPDATE users SET dob = ?, phone = ?, name = ? , username = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $dob, $phone, $name, $username, $id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Details updated successfully.";
    } else {
        $response['success'] = false;
        $response['message'] = "Failed to update details.";
    }

    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

header('Content-Type: application/json');
echo json_encode($response);

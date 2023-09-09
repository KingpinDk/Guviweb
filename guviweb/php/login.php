<?php
session_start();
include 'db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate inputs
    if (empty($username) || empty($password)) {
        $response['success'] = false;
        $response['message'] = "Username and password are required.";
    } else {
        // Sanitize inputs
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        // Check user credentials in the database
        $sql = "SELECT id, username FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $username);
            $stmt->fetch();

            // Set user session
            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;

            $response['success'] = true;
            $response['message'] = "Login successful.";
        } else {
            $response['success'] = false;
            $response['message'] = "Invalid username or password.";
        }

        $stmt->close();
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

header('Content-Type: application/json');
echo json_encode($response);

<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'guvi';

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to execute prepared statement with binding and returning results
function executeStatement($query, $params = array())
{
    global $conn;

    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error in preparing statement: " . $conn->error);
    }

    if (!empty($params)) {
        $paramTypes = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $paramTypes .= 'i';
            } elseif (is_double($param)) {
                $paramTypes .= 'd';
            } else {
                $paramTypes .= 's';
            }
        }
        array_unshift($params, $paramTypes);

        call_user_func_array(array($stmt, 'bind_param'), $params);
    }

    if ($stmt->execute() === false) {
        die("Error in executing statement: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $stmt->close();

    return $result;
}

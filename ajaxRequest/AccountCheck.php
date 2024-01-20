<?php
session_start();
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userName = $_POST['userName'];
    $userPassword = base64_encode($_POST['userPassword']);

    if (empty($userName) || empty($userPassword)) {
        // Return an error message
        echo json_encode(['status' => 'error', 'message' => 'Username and password are required']);
        exit;
    }

    $getAccount = "SELECT * FROM account_table WHERE account_username = '$userName' AND account_password = '$userPassword'";
    $getResult = $con->query($getAccount);

    if ($getResult === FALSE) {
        // Log the detailed error information
        $error_message = $con->error;
        $error_code = $con->errno;
        error_log("SQL Error [$error_code]: $error_message", 0);

        // Return an error message
        echo json_encode(['status' => 'error', 'message' => 'Login unsuccessful. Please try again.']);
    } else {
        // Check if any rows were returned
        if ($getResult->num_rows > 0) {

            $userData = $getResult->fetch_assoc();

            // Session
            $_SESSION['account_id'] = $userData['account_id'];

            echo json_encode(['status' => 'success', 'message' => 'Login successful']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid username or password. Please try again.']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

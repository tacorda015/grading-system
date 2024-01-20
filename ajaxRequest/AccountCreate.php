<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fname = ucfirst($_POST['fname']);
    $lname = ucfirst($_POST['lname']);
    $userName = $_POST['userName'];
    $userPassword = base64_encode($_POST['userPassword']);

    if (empty($userName) || empty($userPassword) || empty($fname) || empty($lname)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields need to be filled up']);
        exit;
    }

    $insertAccount = "INSERT INTO account_table (account_username, account_password, account_fName, account_lName) VALUES ('$userName', '$userPassword', '$fname', '$lname')";
    $insertResult = $con->query($insertAccount);

    if ($insertResult === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Register successful']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Register unsuccessful']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

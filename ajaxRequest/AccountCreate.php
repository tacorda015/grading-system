<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fname = ucfirst($_POST['fname']);
    $lname = ucfirst($_POST['lname']);
    $userName = $_POST['userName'];
    $nameTitle = strtoupper($_POST['nameTitle']);
    $userPassword = base64_encode($_POST['userPassword']);

    if (empty($userName) || empty($userPassword) || empty($fname) || empty($lname)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields need to be filled up']);
        exit;
    }

    $checkUserName = "SELECT COUNT(account_username) AS numberOfAccount FROM account_table WHERE account_username = '$userName'";
    $checkUserNameResult = $con->query($checkUserName)->fetch_assoc();

    if($checkUserNameResult['numberOfAccount'] == 0){
        $insertAccount = "INSERT INTO account_table (account_username, account_password, account_fName, account_lName, account_NameTitle) VALUES ('$userName', '$userPassword', '$fname', '$lname', '$nameTitle')";
        $insertResult = $con->query($insertAccount);

        if ($insertResult === TRUE) {
            echo json_encode(['status' => 'success', 'message' => 'Register successful']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Register unsuccessful']);
        }
    }else{
        echo json_encode(['status' => 'error', 'message' => 'Username Already used']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

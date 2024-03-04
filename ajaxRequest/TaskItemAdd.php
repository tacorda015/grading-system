<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $account_id = $_POST['account_id'];
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $task_status = $_POST['task_status'];
    $task_level = $_POST['task_level'];
    $task_start = $_POST['task_start'];
    $task_end = $_POST['task_end'];

    $taskAdd = "INSERT INTO task_table (account_id, task_status, task_level, task_order, task_name, task_description, task_start, task_end) 
    VALUES ('$account_id', '$task_status', '$task_level', 1, '$task_name', '$task_description', '$task_start', '$task_end')";


    $tasktaskAddResult = $con->query($taskAdd);

    if ($tasktaskAddResult === TRUE) {
        
      $lastInsertId = $con->insert_id;

      $getTask = "SELECT task_order, task_id FROM task_table WHERE task_status = '{$task_status}' AND account_id = '{$account_id}' AND task_id != $lastInsertId ORDER BY task_order";
      $getTaskResult = $con->query($getTask);

      while($getTaskRow = $getTaskResult->fetch_assoc()){
        $updateTaskOrder = "UPDATE task_table SET task_order = {$getTaskRow['task_order']} + 1 WHERE task_id = '{$getTaskRow['task_id']}'";
        
        $updateTaskOrderResult = $con->query($updateTaskOrder);
      }

      echo json_encode(['status' => 'success', 'message' => 'Adding Task Successful']);

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Adding Task Unsuccessful']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $taskId = $_POST['taskId'];
    $taskStatus = $_POST['taskStatus'];
    $newOrder = $_POST['newOrder'];
    $displayOrders = $_POST['displayOrders'];

    $taskUpdate = "UPDATE task_table SET task_status = '{$taskStatus}', task_order = '{$newOrder}' WHERE task_id = '{$taskId}'";


    $taskUpdateResult = $con->query($taskUpdate);

    if ($taskUpdateResult === TRUE) {
        
        foreach ($displayOrders as $taskOrder) {
          $updateOrderQuery = "UPDATE task_table SET task_order = '{$taskOrder['order']}' WHERE task_id = '{$taskOrder['task_id']}'";
          $updateOrderResult = $con->query($updateOrderQuery);

          if($updateOrderResult){
            echo json_encode(['status' => 'success', 'message' => 'Task Successfully Updated']);
          }else{
            echo json_encode(['status' => 'error', 'message' => 'Error']);
          }
      }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Updating Task Unsuccessful']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

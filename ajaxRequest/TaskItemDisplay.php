<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $taskId = $_GET['taskId'];

  $taskQuery = "SELECT * FROM task_table WHERE task_id = '{$taskId}'";

  $taskQueryResult = $con->query($taskQuery);

  if ($taskQueryResult) {
    $taskQueryData = $taskQueryResult->fetch_assoc();
    echo json_encode([
      'status' => 'success',
      'task_id' => $taskQueryData['task_id'],
      'task_name' => $taskQueryData['task_name'],
      'task_description' => $taskQueryData['task_description'],
      'task_status' => $taskQueryData['task_status'],
      'task_level' => $taskQueryData['task_level'],
      'task_start' => $taskQueryData['task_start'],
      'task_end' => $taskQueryData['task_end'],
      // Add other task details here
    ]);

  } else {
    echo json_encode(['status' => 'error', 'message' => 'Adding Task Unsuccessful']);
  }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

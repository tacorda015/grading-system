<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $deleteStudentId = $_POST['deleteStudentId'];

    $deleteStudent = "DELETE FROM student_table WHERE student_id = '$deleteStudentId'";

    $deleteStudentResult = $con->query($deleteStudent);

    if ($deleteStudentResult) {
        echo json_encode(['status' => 'success', 'message' => 'Student Successfully Deleted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Deleting Student Unsuccessful']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

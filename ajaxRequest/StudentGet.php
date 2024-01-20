<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    $studentId = $_GET['studentId'];

    $getStudent = "SELECT * FROM student_table WHERE student_id = '$studentId'";
    $getStudentResult = $con->query($getStudent);

    $getStudentData = $getStudentResult->fetch_assoc();

    if ($getStudentResult->num_rows > 0) {
        echo json_encode($getStudentData);
    } else {
        echo json_encode(['error' => 'Error fetching data from the database.']);
    }

} else {
    echo json_encode(['error' => 'Error Invalid request method.']);
}
?>

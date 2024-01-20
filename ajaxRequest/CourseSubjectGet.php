<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    $courseSubjectId = $_GET['courseSubjectId'];

    $getCourseSubject = "SELECT * FROM course_subject_table WHERE course_subject_id = '$courseSubjectId'";
    $getCourseSubjectResult = $con->query($getCourseSubject);

    $getCourseSubjectData = $getCourseSubjectResult->fetch_assoc();

    if ($getCourseSubjectResult->num_rows > 0) {
        echo json_encode($getCourseSubjectData);
    } else {
        echo json_encode(['error' => 'Error fetching data from the database.']);
    }

} else {
    echo json_encode(['error' => 'Error Invalid request method.']);
}
?>

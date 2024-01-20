<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $addSessionName = ucfirst($_POST['addSessionName']);
    $addSessionPercent = $_POST['addSessionPercent'];
    $addCourseSubjectId = $_POST['addCourseSubjectId'];

    $insertSession = "INSERT INTO grading_session_table (grading_session_name, grading_session_percentage, course_subject_id) VALUES ('$addSessionName', '$addSessionPercent', '$addCourseSubjectId')";

    $insertResult = $con->query($insertSession);

    if ($insertResult === TRUE) {
        $lastInsertId = mysqli_insert_id($con);

        $insertComponent = "INSERT INTO component_table (component_name, component_percentage, grading_session_id) VALUES ('Regular Quizez', '25', '$lastInsertId'), ('Participation', '25', '$lastInsertId'), ('Requirements', '25', '$lastInsertId'), ('Exam', '25', '$lastInsertId')";
        $insertComponentResult = $con->query($insertComponent);

        if ($insertComponentResult === TRUE){
            echo json_encode(['status' => 'success', 'message' => 'Creating Component and Adding Session Successful']);
        }else{
            echo json_encode(['status' => 'error', 'message' => 'Creating Component Unsuccessful']);
        }

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Adding Session Unsuccessful']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

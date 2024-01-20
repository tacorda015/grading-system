<?php 
session_start();
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courseSubjectId = $_POST['courseSubjectId'];
    $sessionId = $_POST['sessionId'];

    $getCourseSubjectData = "SELECT course_subject_name FROM course_subject_table WHERE course_subject_id = '$courseSubjectId'";
    $getCourseSubjectResult = $con->query($getCourseSubjectData)->fetch_assoc();

    $getGradingSessionData = "SELECT * FROM grading_session_table WHERE course_subject_id = '$courseSubjectId'";
    if($sessionId != 'NULL'){
        $getGradingSessionData .= "AND grading_session_id = '$sessionId'";
    }
    $getGradingSessionResult = $con->query($getGradingSessionData);
    
    // Check if the query was successful
    if ($getGradingSessionResult) {
        $row = $getGradingSessionResult->fetch_assoc();
    
        // Check if any row was returned
        if ($row) {
            $sessionData = array(
                'GradingSessionIdSetted' => $row['grading_session_id'],
                'CourseSubjectIdSetted' => $courseSubjectId,
                'CourseSubjectNameSetted' => $getCourseSubjectResult['course_subject_name'],
            );
        
            // Store the data in the session
            $_SESSION['SessionArray'] = $sessionData;
        }else {
            // Handle the case when no row is returned
            echo "No matching record found for course_subject_id: $courseSubjectId";
        }
    } else {
        // Handle the case when the query fails
        echo "Error in query: " . $con->error;
    }
    
}
?>
<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $CourseSubjectIdSetted = $_POST['updateCourseSubjectId'];

    // Retrieve the updated session data from the form fields
    $updateSessionNames = $_POST['updateSessionName'];
    $updateGradeBases = $_POST['updateGradeBase'];
    $updatePercentages = $_POST['updatePercentage'];
    $updateSessionIds = $_POST['updateSessionId'];

    // Assuming that the arrays have the same length
    $numSessions = count($updateSessionNames);

    // Update the database for each session
    for ($i = 0; $i < $numSessions; $i++) {
        $updateSessionName = $con->real_escape_string($updateSessionNames[$i]);
        $updateGradeBase = $con->real_escape_string($updateGradeBases[$i]);
        $updatePercentage = $con->real_escape_string($updatePercentages[$i]);
        $updateSessionId = $con->real_escape_string($updateSessionIds[$i]);

        $updateQuery = "UPDATE grading_session_table SET grading_session_name = '$updateSessionName', grading_session_base = '$updateGradeBase', grading_session_percentage = '$updatePercentage' WHERE course_subject_id = '$CourseSubjectIdSetted' AND grading_session_id = '$updateSessionId'";
        $con->query($updateQuery);
    }
}
?>

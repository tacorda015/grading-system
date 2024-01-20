<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $CourseSubjectIdSetted = $_POST['updateCourseSubjectId'];

    // Retrieve the updated session data from the form fields
    $updateComponentNames = $_POST['updateComponentName'];
    $updateComponentPercentages = $_POST['updateComponentPercentage'];
    $updateComponentIds = $_POST['updateComponentId'];
    $updateSessionId = $_POST['updateSessionId'];

    // Assuming that the arrays have the same length
    $numSessions = count($updateComponentNames);

    // Update the database for each session
    for ($i = 0; $i < $numSessions; $i++) {
        $updateComponentName = $con->real_escape_string($updateComponentNames[$i]);
        $updateComponentPercentage = $con->real_escape_string($updateComponentPercentages[$i]);
        $updateComponentId = $con->real_escape_string($updateComponentIds[$i]);

        $updateQuery = "UPDATE component_table SET component_name = '$updateComponentName', component_percentage = '$updateComponentPercentage' WHERE component_id = '$updateComponentId' AND grading_session_id = '$updateSessionId'";
        $con->query($updateQuery);
    }
}
?>

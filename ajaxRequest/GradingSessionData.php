<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $courseSubjectId = $_POST['courseSubjectId'];
    
    $getDetails = "SELECT * FROM grading_session_table WHERE course_subject_id = '$courseSubjectId'";
    $getDetailsResult = $con->query($getDetails);

    if ($getDetailsResult) {
        $sessionData = array(); // Initialize an empty array

        while ($DetailsData = $getDetailsResult->fetch_assoc()) {
            // Append details to the $sessionData array directly
            $sessionData[] = array(
                'grading_session_id' => $DetailsData['grading_session_id'],
                'grading_session_name' => $DetailsData['grading_session_name'],
                'grading_session_base' => $DetailsData['grading_session_base'],
                'grading_session_percentage' => $DetailsData['grading_session_percentage'],
            );
        }

        echo json_encode($sessionData);

    } else {
        // Handle the case where the query fails
        echo "Error: " . $con->error;
    }
}
?>

<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $GradingSessionIdSetted = $_POST['GradingSessionIdSetted'];
    
    $getDetails = "SELECT * FROM component_table WHERE grading_session_id = '$GradingSessionIdSetted'";
    $getDetailsResult = $con->query($getDetails);

    if ($getDetailsResult) {
        $componentData = array(); // Initialize an empty array

        while ($DetailsData = $getDetailsResult->fetch_assoc()) {
            // Append details to the $componentData array directly
            $componentData[] = array(
                'component_id' => $DetailsData['component_id'],
                'component_name' => $DetailsData['component_name'],
                'component_percentage' => $DetailsData['component_percentage'],
            );
        }

        echo json_encode($componentData);

    } else {
        // Handle the case where the query fails
        echo "Error: " . $con->error;
    }
}
?>

<?php
include "../database/connection.php";

if (isset($_POST['gradingSessionId'])) {
    // Fetch selected session and subject information
    $selectedSessionId = $_POST['gradingSessionId'];
    $hiddenCurrentSubjectId = $_POST['hiddenCurrentSubjectId'];

    $getSessionInfoQuery = "SELECT grading_session_base, grading_session_percentage FROM grading_session_table WHERE course_subject_id = '$hiddenCurrentSubjectId' AND grading_session_id = '$selectedSessionId'";
    $sessionInfoResult = $con->query($getSessionInfoQuery)->fetch_assoc();
    $sessionBase = $sessionInfoResult['grading_session_base'];
    $sessionPercentage = $sessionInfoResult['grading_session_percentage'];

    $remainingPercentage = 100 - $sessionBase;

    // Fetch components based on the selected grading session
    $getComponentsQuery = "SELECT * FROM component_table WHERE grading_session_id = '$selectedSessionId'";
    $componentsResult = $con->query($getComponentsQuery);

    $weightedAverage = 0;

    while ($component = $componentsResult->fetch_assoc()) {
        $componentId = $component['component_id'];
        $componentPercentage = $component['component_percentage'];

        // Fetch component values
        $getComponentValuesQuery = "SELECT * FROM component_value_table WHERE component_id = '$componentId'";
        $componentValuesResult = $con->query($getComponentValuesQuery);

        $valueCounter = 0;
        $totalValue = 0;

        while ($componentValue = $componentValuesResult->fetch_assoc()) {
            echo '<th>' . $componentValue['component_value'] . '</th>';
            $totalValue += (int)$componentValue['component_value'];
            $valueCounter++;
        }

        // Display total value if there is more than one component value
        if ($valueCounter != 1) {
            echo '<th>' . $totalValue . '</th>';
        }

        // Calculate weighted component value
        $calculatedValue = ($totalValue == 0) ? $sessionBase : ($totalValue / $totalValue) * $remainingPercentage + $sessionBase;

        // Format the calculated value
        $formattedValue = ($calculatedValue == 0) ? '00.00' : number_format($calculatedValue, 2, '.', '');
        
        // Display formatted value
        echo '<th>' . $formattedValue . '</th>';        

        // Calculate and display the weighted component value
        $weightedComponentValue = $formattedValue * ($componentPercentage / 100);
        echo '<th>' . $weightedComponentValue . '</th>';

        // Accumulate weighted component values for the weighted average
        $weightedAverage += $weightedComponentValue;
    }

    // Display the weighted average
    echo '<th>' . $weightedAverage . '</th>'; 

    // Calculate and display the final result
    $finalResult = $weightedAverage * ($sessionPercentage / 100);
    echo '<th>' . $finalResult . '</th>';

    

    // Count the number of component values not equal to 0
    $nonZeroValuesQuery = "SELECT COUNT(*) AS nonZeroCount FROM component_value_table WHERE component_id IN (SELECT component_id FROM component_table WHERE grading_session_id = '$selectedSessionId') AND component_value <> 0";
    $nonZeroValuesResult = $con->query($nonZeroValuesQuery)->fetch_assoc();
    $nonZeroCount = $nonZeroValuesResult['nonZeroCount'];

    // Get the total count of component values
    $totalCountQuery = "SELECT COUNT(*) AS totalCount FROM component_value_table WHERE component_id IN (SELECT component_id FROM component_table WHERE grading_session_id = '$selectedSessionId') AND component_value <> 0";
    $totalCountResult = $con->query($totalCountQuery)->fetch_assoc();
    $totalCount = $totalCountResult['totalCount'];
    
    // Determine the remarks
    if ($weightedAverage < 75) {
        $remarks = 'Failed';
    } elseif ($nonZeroCount != $totalCount) {
        $remarks = 'INC';
    } elseif ($weightedAverage >= 75 && $weightedAverage <= 100) {
        $remarks = 'Passed';
    }

    // Display the remarks
    echo '<th>' . $remarks . '</th>';

}
?>

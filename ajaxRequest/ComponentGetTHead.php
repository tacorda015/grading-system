<?php
include "../database/connection.php";

if (isset($_POST['currentSelectedSessionId'])) {
    $currentSelectedSessionId = $_POST['currentSelectedSessionId'];
    $GradingSessionBase = $_POST['GradingSessionBase'];

    error_log('currentSelectedSessionId' . $currentSelectedSessionId);
    error_log('GradingSessionBase' . $GradingSessionBase);

    // Get Data to component_table
    $ComponentTableQuery = "SELECT * FROM component_table WHERE grading_session_id = '$currentSelectedSessionId'";
    $ComponentTableResult = $con->query($ComponentTableQuery);

    $componentPercentages = $componentNames = $componentDataName = $componentDataValue = array();

    while ($ComponentRow = $ComponentTableResult->fetch_assoc()) {
        $ComponentId = $ComponentRow['component_id'];
        $componentPercentages[] = $ComponentRow['component_percentage'];
        $componentNames[] = $ComponentRow['component_name'];

        // Get Data to component_value_table
        $ComponentValueNameQuery = "SELECT component_value_name FROM component_value_table WHERE component_id = '$ComponentId'";
        $ComponentValueNameResult = $con->query($ComponentValueNameQuery);

        while ($componentValueNameRow = $ComponentValueNameResult->fetch_assoc()) {
            $componentDataName[$ComponentId][] = $componentValueNameRow['component_value_name'];
        }

        // Get Data to component_value_table
        $ComponentValueNameQuery = "SELECT component_value FROM component_value_table WHERE component_id = '$ComponentId'";
        $ComponentValueResult = $con->query($ComponentValueNameQuery);

        while ($componentValueRow = $ComponentValueResult->fetch_assoc()) {
            $componentDataValue[$ComponentId][] = $componentValueRow['component_value'];
        }
    }

    // Output HTML
    echo '<tr>
        <th rowspan="3">#</th>
        <th rowspan="3">Student Name</th>';

    for ($i = 0; $i < count($componentNames); $i++) {
        $colspan = ($i === 2 || $i === 3) ? 3 : 8;
        echo "<th colspan='{$colspan}' class='border'>" . $componentNames[$i] . "</th>";
    }

    echo '</tr>
    <tr>';

    $index = 0;

    foreach ($componentDataName as $componentId => $componentValueNames) {
        foreach ($componentValueNames as $value) {
            echo "<th>$value</th>";
        }

        if ($index != 2 && $index != 3) {
            echo "<th>Total</th>";
        }
        echo "<th>Grade</th>";
        echo "<th>{$componentPercentages[$index]}%</th>";

        $index++;
    }

    echo '</tr>
    <tr>';

    $indexValue = 0;
    $totalSum = 0;

    foreach ($componentDataValue as $componentId => $componentValueValues) {
        foreach ($componentValueValues as $value) {
            echo "<th>$value</th>";
            $totalSum += $value;
        }

        if ($indexValue != 2 && $indexValue != 3) {
            echo "<th>" . $totalSum . "</th>";
        }

        $multiplier = 100 - $GradingSessionBase;

        $calculatedValue = ($totalSum == 0) ? $GradingSessionBase : ($totalSum / $totalSum) * $multiplier + $GradingSessionBase;
        echo "<th>" . number_format($calculatedValue, 2, '.', '') . "</th>";
        echo "<th>{$componentPercentages[$indexValue]}</th>";

        $indexValue++;
        $totalSum = 0;
    }

    echo '</tr>';
}
?>

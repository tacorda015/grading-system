<?php
include "../database/connection.php";

if (isset($_POST['gradingSessionId'])) {
    $selectedSessionId = $_POST['gradingSessionId'];

    // Fetch components based on the selected grading session
    $getComponent = "SELECT * FROM component_table WHERE grading_session_id = '$selectedSessionId'";
    $getComponentResult = $con->query($getComponent);

    while($componentData = $getComponentResult->fetch_assoc()){

        $componentIDS = $componentData['component_id'];

        $getComponentName = "SELECT * FROM component_value_table WHERE component_id = '$componentIDS'";
        $getComponentNameResult = $con->query($getComponentName);

        $counter = 0;
        while($componentNameData = $getComponentNameResult->fetch_assoc()){
            echo '<th>'.$componentNameData['component_value_name'].'</th>';
            $counter++;
        }

        if($counter != 1){
            echo '<th>Total</th>';
        }
            
        echo '<th>Grade</th>';
        echo '<th>'.$componentData['component_percentage'].'%</th>';
        
    }
}
?>

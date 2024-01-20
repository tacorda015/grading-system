<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Retrieve the updated session data from the form fields
    $component_value_id = $_POST['component_value_id'];
    $componentId = $_POST['componentId'];
    $value = $_POST['value'];

    $updateQuery = "UPDATE component_value_table SET component_value = '$value' WHERE component_id = '$componentId' AND component_value_id = '$component_value_id'";
    
    if ($con->query($updateQuery)) {
        // If the query was successful, echo "success"
        echo "success";
    } else {
        // If the query failed, echo an error message
        echo "Update failed: " . $con->error;
    }
}
?>

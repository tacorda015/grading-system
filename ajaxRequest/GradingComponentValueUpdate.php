<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Retrieve the updated session data from the form fields
    $component_value_id = $_POST['component_value_id'];
    $componentId = $_POST['componentId'];
    $value = $_POST['value'];
    $CourseSubjectIdSetted = $_POST['CourseSubjectIdSetted'];

    $updateQuery = "UPDATE component_value_table SET component_value = '$value' WHERE component_id = '$componentId' AND component_value_id = '$component_value_id'";
    
    if ($con->query($updateQuery)) {
        // If the query was successful, echo "success"
        echo "success";
    } else {
        // If the query failed, echo an error message
        echo "Update failed: " . $con->error;
    }
    
    $getStudentId = "SELECT student_id FROM student_table WHERE course_subject_id = '$CourseSubjectIdSetted'";
    $getStudentIdResult = $con->query($getStudentId);

    while($studentId = $getStudentIdResult->fetch_assoc()){

        $checkStudentGrade = "SELECT COUNT(*) AS NumberOfStudent FROM student_grade_table WHERE student_id = '{$studentId['student_id']}' AND component_value_id = '$component_value_id'";

        $checkStudentGradeResult = $con->query($checkStudentGrade)->fetch_assoc();

        if($checkStudentGradeResult['NumberOfStudent'] != 0){
            continue;
        }else{
            $insertStudentGrade = "INSERT INTO student_grade_table (student_grade, student_id, component_value_id) VALUES (0, '{$studentId['student_id']}', '$component_value_id')";
            $insertStudentGradeResult = $con->query($insertStudentGrade);
        }
    }

    // check first all student who dont have student_grade in student_grade_table and insert a student_grade = 0 as default but if student already have student_grade dont change.
}
?>

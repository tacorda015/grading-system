<?php
include "../database/connection.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $componentValueId = $_POST['componentValueId'];
    $studentId = $_POST['studentId'];
    $editedText = (int)$_POST['editedText'];

    if($editedText != '' || $editedText != 0){
        $CheckStudentGrade = "SELECT COUNT(*) AS total, student_grade_id FROM student_grade_table WHERE student_id = '$studentId' AND component_value_id = '$componentValueId'";
        $CheckStudentGradeResult = $con->query($CheckStudentGrade)->fetch_assoc();

        if($CheckStudentGradeResult['total'] == 0 ){
            $StudentGradeQuery = "INSERT INTO student_grade_table (student_grade, student_id, component_value_id) VALUES ($editedText, '$studentId', '$componentValueId')";
        }else{
            $StudentGradeQuery = "UPDATE student_grade_table SET student_grade = '$editedText' WHERE student_grade_id = {$CheckStudentGradeResult['student_grade_id']}";
        }

        $StudentGradeResult = $con->query($StudentGradeQuery);
        if ($StudentGradeResult === TRUE) {
            echo json_encode(['status' => 'success', 'message' => 'Update/Insert successful']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Update/Insert unsuccessful']);
        }
    }else{
        echo json_encode(['status' => 'error', 'message' => 'Update/Insert unsuccessful']);
    }
}
?>
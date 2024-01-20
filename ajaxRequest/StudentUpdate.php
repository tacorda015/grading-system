<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $editStudentFirstName = ucfirst($_POST['editStudentFirstName']);
    $editStudentMiddleName = ucfirst($_POST['editStudentMiddleName']);
    $editStudentLastName = $_POST['editStudentLastName'];
    $editStudentNumber = $_POST['editStudentNumber'];
    $editStudentStatus = $_POST['editStudentStatus'];
    $editCourseSubjectId = $_POST['editCourseSubjectId'];
    $editStudentId = $_POST['editStudentId'];

    if (empty($editStudentFirstName) || empty($editStudentLastName) || empty($editStudentNumber) || empty($editStudentStatus) || empty($editCourseSubjectId)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields need to be filled up']);
        exit;
    }
    
    $middleNamePart = !empty($editStudentMiddleName) ? substr($editStudentMiddleName, 0, 1) . '.' : '';
    $addStudentFullName = $editStudentFirstName . ' ' . $middleNamePart . ' ' . $editStudentLastName;

    $updateCourseSubject = "UPDATE student_table SET student_full_name = '$addStudentFullName', student_fName = '$editStudentFirstName', student_mName = '$editStudentMiddleName', student_lName = '$editStudentLastName', student_status = '$editStudentStatus', student_number = '$editStudentNumber', course_subject_id = '$editCourseSubjectId' WHERE student_id  = '$editStudentId'";

    $updateCourseSubjectResult = $con->query($updateCourseSubject);

    if ($updateCourseSubjectResult === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Student Successfully Updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Updating Student Unsuccessful']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

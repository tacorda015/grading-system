<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $addStudentFirstName = ucfirst($_POST['addStudentFirstName']);
    $addStudentMiddleName = ucfirst($_POST['addStudentMiddleName']);
    $addStudentLastName = ucfirst($_POST['addStudentLastName']);
    $addStudentStatus = $_POST['addStudentStatus'];
    $addCourseSubjectId = $_POST['addCourseSubjectId'];
    $addStudentNumber = $_POST['addStudentNumber'];
    $addStudentGender = $_POST['addStudentGender'];

    if (empty($addStudentFirstName) || empty($addStudentLastName) || empty($addStudentNumber)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields need to be filled up']);
        exit;
    }

    $middleNamePart = !empty($addStudentMiddleName) ? substr($addStudentMiddleName, 0, 1) . '.' : '';
    $addStudentFullName = $addStudentFirstName . ' ' . $middleNamePart . ' ' . $addStudentLastName;


    $insertStudent = "INSERT INTO student_table (student_full_name, student_fName, student_mName, student_lName, student_gender, student_status, student_number, course_subject_id) VALUES ('$addStudentFullName', '$addStudentFirstName', '$addStudentMiddleName', '$addStudentLastName', '$addStudentGender', '$addStudentStatus', '$addStudentNumber', '$addCourseSubjectId')";

    $insertResult = $con->query($insertStudent);

    if ($insertResult === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Student Successfully Added']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Adding Student Unsuccessful']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

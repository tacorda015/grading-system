<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $editCourseName = ucfirst($_POST['editCourseName']);
    $editSubjectName = ucfirst($_POST['editSubjectName']);
    $editSubjectTitle = ucfirst($_POST['editSubjectTitle']);
    $editSubjectTeacher = $_POST['editSubjectTeacher'];
    $editCourseSubjectProgramHead = ucfirst($_POST['editCourseSubjectProgramHead']);
    $editTeacherId = $_POST['editTeacherId'];
    $editSyStart = $_POST['editSyStart'];
    $editSyEnd = $_POST['editSyEnd'];
    $editSySemester = $_POST['editSySemester'];
    $editMeetingDay = $_POST['editMeetingDay'];
    $editSubjectRoom = $_POST['editSubjectRoom'];
    $editMeetingTimeStart = $_POST['editMeetingTimeStart'];
    $editMeetingTimeEnd = $_POST['editMeetingTimeEnd'];
    $editCourseSubjectId = $_POST['editCourseSubjectId'];

    if (empty($editCourseName) || empty($editSubjectName) || empty($editSubjectTeacher) || empty($editSyStart) || empty($editSyEnd) || empty($editMeetingDay) || empty($editSubjectRoom) || empty($editSubjectTitle) || empty($editCourseSubjectProgramHead)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields need to be filled up']);
        exit;
    }
    $editCourseSubjectName = $editCourseName . '/' . $editSubjectName;

    $updateCourseSubject = "UPDATE course_subject_table SET course_subject_name = '$editCourseSubjectName', course_name = '$editCourseName', subject_name = '$editSubjectName', subject_title = '$editSubjectTitle', course_subject_teacher = '$editSubjectTeacher', course_subject_program_head = '$editCourseSubjectProgramHead', teacher_id = '$editTeacherId', sy_start= '$editSyStart', sy_end = '$editSyEnd', sy_semester = '$editSySemester', course_subject_day = '$editMeetingDay', course_subject_time_start = '$editMeetingTimeStart', course_subject_time_end = '$editMeetingTimeEnd', course_subject_room = '$editSubjectRoom' WHERE course_subject_id = '$editCourseSubjectId'";

    $updateCourseSubjectResult = $con->query($updateCourseSubject);

    if ($updateCourseSubjectResult === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Course/Section Successfully Updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Updating Course/Section Unsuccessful']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

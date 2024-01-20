<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $editCourseName = ucfirst($_POST['editCourseName']);
    $editSubjectName = ucfirst($_POST['editSubjectName']);
    $editSubjectTeacher = $_POST['editSubjectTeacher'];
    $editTeacherId = $_POST['editTeacherId'];
    $editSyStart = $_POST['editSyStart'];
    $editSyEnd = $_POST['editSyEnd'];
    $editMeetingDay = $_POST['editMeetingDay'];
    $editSubjectRoom = $_POST['editSubjectRoom'];
    $editMeetingTimeStart = $_POST['editMeetingTimeStart'];
    $editMeetingTimeEnd = $_POST['editMeetingTimeEnd'];
    $editCourseSubjectId = $_POST['editCourseSubjectId'];

    if (empty($editCourseName) || empty($editSubjectName) || empty($editSubjectTeacher) || empty($editSyStart) || empty($editSyEnd) || empty($editMeetingDay) || empty($editSubjectRoom)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields need to be filled up']);
        exit;
    }
    $editCourseSubjectName = $editCourseName . '/' . $editSubjectName;

    $updateCourseSubject = "UPDATE course_subject_table SET course_subject_name = '$editCourseSubjectName', course_name = '$editCourseName', subject_name = '$editSubjectName', course_subject_teacher = '$editSubjectTeacher', teacher_id = '$editTeacherId', sy_start= '$editSyStart', sy_end = '$editSyEnd', course_subject_day = '$editMeetingDay', course_subject_time_start = '$editMeetingTimeStart', course_subject_time_end = '$editMeetingTimeEnd', course_subject_room = '$editSubjectRoom' WHERE course_subject_id = '$editCourseSubjectId'";

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

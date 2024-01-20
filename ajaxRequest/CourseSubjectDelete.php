<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $deleteCourseSubjectId = $_POST['deleteCourseSubjectId'];

    // Use prepared statements
    $stmtGetSessionIds = $con->prepare("SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = ?");
    $stmtDeleteComponent = $con->prepare("DELETE FROM component_table WHERE grading_session_id = ?");
    $stmtDeleteSession = $con->prepare("DELETE FROM grading_session_table WHERE course_subject_id = ?");
    $stmtDeleteCourseSubject = $con->prepare("DELETE FROM course_subject_table WHERE course_subject_id = ?");

    if (
        $stmtGetSessionIds &&
        $stmtDeleteComponent &&
        $stmtDeleteSession &&
        $stmtDeleteCourseSubject
    ) {
        // Bind parameters
        $stmtGetSessionIds->bind_param('s', $deleteCourseSubjectId);
        $stmtDeleteComponent->bind_param('s', $gradingSessionId);
        $stmtDeleteSession->bind_param('s', $deleteCourseSubjectId);
        $stmtDeleteCourseSubject->bind_param('s', $deleteCourseSubjectId);

        // Execute queries
        $stmtGetSessionIds->execute();
        $resultGetSessionIds = $stmtGetSessionIds->get_result();

        // Loop through each session
        while ($row = $resultGetSessionIds->fetch_assoc()) {
            $gradingSessionId = $row['grading_session_id'];

            // Delete components
            $stmtDeleteComponent->execute();

            // Delete sessions
            $stmtDeleteSession->execute();
        }

        // Delete course_subject
        $stmtDeleteCourseSubject->execute();

        // Check for success
        if ($stmtDeleteCourseSubject->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Course/Section Successfully Deleted']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Deleting Course/Section Unsuccessful']);
        }

        // Close statements
        $stmtGetSessionIds->close();
        $stmtDeleteComponent->close();
        $stmtDeleteSession->close();
        $stmtDeleteCourseSubject->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Statement preparation failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>


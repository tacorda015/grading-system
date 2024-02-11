
<?php
// include "../database/connection.php";

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
//     $addCourseName = ucfirst($_POST['addCourseName']);
//     $addSubjectName = ucfirst($_POST['addSubjectName']);
//     $addSubjectTeacher = $_POST['addSubjectTeacher'];
//     $addTeacherId = $_POST['addTeacherId'];
//     $addSyStart = $_POST['addSyStart'];
//     $addSyEnd = $_POST['addSyEnd'];
//     $addMeetingDay = $_POST['addMeetingDay'];
//     $addMeetingTimeStart = $_POST['addMeetingTimeStart'];
//     $addMeetingTimeEnd = $_POST['addMeetingTimeEnd'];
//     $addSubjectRoom = $_POST['addSubjectRoom'];

//     if (empty($addCourseName) || empty($addSubjectName) || empty($addSubjectTeacher) || empty($addSyStart) || empty($addSyEnd) || empty($addMeetingDay) || empty($addMeetingTimeStart) || empty($addSubjectRoom)) {
//         echo json_encode(['status' => 'error', 'message' => 'All fields need to be filled up']);
//         exit;
//     }
//     $addCourseSubjectName = $addCourseName . '/' . $addSubjectName;

//     // Your existing code for inserting the course subject
//     $insertCourseSubject = "INSERT INTO course_subject_table (course_subject_name, course_name, subject_name, course_subject_teacher, teacher_id, sy_start, sy_end, course_subject_day, course_subject_time_start, course_subject_time_end, course_subject_room) VALUES ('$addCourseSubjectName', '$addCourseName', '$addSubjectName', '$addSubjectTeacher', '$addTeacherId', '$addSyStart', '$addSyEnd', '$addMeetingDay', '$addMeetingTimeStart', '$addMeetingTimeEnd', '$addSubjectRoom')";

//     $insertResult = $con->query($insertCourseSubject);

//     if ($insertResult === TRUE) {
//         $lastInsertId = mysqli_insert_id($con);

//         // Define an array of sessions and components
//         $sessions = [
//             ['name' => 'Preliminary', 'percentage' => '20', 'components' => [
//                 ['name' => 'Regular Quizzes', 'percentage' => '25'],
//                 ['name' => 'Participation', 'percentage' => '25'],
//                 ['name' => 'Requirements', 'percentage' => '25'],
//                 ['name' => 'Exam', 'percentage' => '25'],
//             ]],
//             ['name' => 'Midterm', 'percentage' => '20', 'components' => [
//                 ['name' => 'Regular Quizzes', 'percentage' => '25'],
//                 ['name' => 'Participation', 'percentage' => '25'],
//                 ['name' => 'Requirements', 'percentage' => '25'],
//                 ['name' => 'Exam', 'percentage' => '25'],
//             ]],
//             ['name' => 'SemiFinals', 'percentage' => '20', 'components' => [
//                 ['name' => 'Regular Quizzes', 'percentage' => '25'],
//                 ['name' => 'Participation', 'percentage' => '25'],
//                 ['name' => 'Requirements', 'percentage' => '25'],
//                 ['name' => 'Exam', 'percentage' => '25'],
//             ]],
//             ['name' => 'Finals', 'percentage' => '40', 'components' => [
//                 ['name' => 'Regular Quizzes', 'percentage' => '25'],
//                 ['name' => 'Participation', 'percentage' => '25'],
//                 ['name' => 'Requirements', 'percentage' => '25'],
//                 ['name' => 'Exam', 'percentage' => '25'],
//             ]],
//         ];

//         // Insert grading sessions and components
//         foreach ($sessions as $session) {
//             $sessionName = $session['name'];
//             $sessionPercentage = $session['percentage'];

//             // Insert grading sessions
//             $insertSession = "INSERT INTO grading_session_table (grading_session_name, grading_session_percentage, course_subject_id) VALUES ('$sessionName', '$sessionPercentage', '$lastInsertId')";

//             $insertSessionResult = $con->query($insertSession);

//             // Check if grading sessions were inserted successfully
//             if ($insertSessionResult === TRUE) {
//                 $lastGradingSessionId = mysqli_insert_id($con);

//                 // Insert components for each session
//                 foreach ($session['components'] as $component) {
//                     $componentName = $component['name'];
//                     $componentPercentage = $component['percentage'];

//                     // Insert components using the last grading session ID
//                     $insertComponent = "INSERT INTO component_table (component_name, component_percentage, grading_session_id) VALUES ('$componentName', '$componentPercentage', '$lastGradingSessionId')";

//                     $insertComponentResult = $con->query($insertComponent);

//                     // Check if components were inserted successfully
//                     if (!$insertComponentResult) {
//                         echo json_encode(['status' => 'error', 'message' => 'Adding Components Unsuccessful']);
//                         exit; // Exit the loop if any component insertion fails
//                     }
//                 }
//             } else {
//                 echo json_encode(['status' => 'error', 'message' => 'Adding Grading Sessions Unsuccessful']);
//             }
//         }

//         echo json_encode(['status' => 'success', 'message' => 'Course/Section Successfully Added']);
//     } else {
//         echo json_encode(['status' => 'error', 'message' => 'Adding Course/Section Unsuccessful']);
//     }
// } else {
//     echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
// }
?>


<?php
include "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $addCourseName = ucfirst($_POST['addCourseName']);
    $addSubjectName = ucfirst($_POST['addSubjectName']);
    $addSubjectTitle = ucfirst($_POST['addSubjectTitle']);
    $addSubjectTeacher = $_POST['addSubjectTeacher'];
    $addCourseSubjectProgramHead = ucfirst($_POST['addCourseSubjectProgramHead']);
    $addTeacherId = $_POST['addTeacherId'];
    $addSyStart = $_POST['addSyStart'];
    $addSyEnd = $_POST['addSyEnd'];
    $addSySemester = $_POST['addSySemester'];
    $addMeetingDay = $_POST['addMeetingDay'];
    $addMeetingTimeStart = $_POST['addMeetingTimeStart'];
    $addMeetingTimeEnd = $_POST['addMeetingTimeEnd'];
    $addSubjectRoom = $_POST['addSubjectRoom'];

    if (empty($addCourseName) || empty($addSubjectName) || empty($addSubjectTeacher) || empty($addSyStart) || empty($addSyEnd) || empty($addMeetingDay) || empty($addMeetingTimeStart) || empty($addSubjectRoom) || empty($addSubjectTitle) || empty($addSySemester) || empty($addCourseSubjectProgramHead)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields need to be filled up']);
        exit;
    }
    $addCourseSubjectName = $addCourseName . '/' . $addSubjectName;

    // Your existing code for inserting the course subject
    $insertCourseSubject = "INSERT INTO course_subject_table (course_subject_name, course_name, subject_name, subject_title, course_subject_teacher, course_subject_program_head, teacher_id, sy_start, sy_end, sy_semester, course_subject_day, course_subject_time_start, course_subject_time_end, course_subject_room) VALUES ('$addCourseSubjectName', '$addCourseName', '$addSubjectName', '$addSubjectTitle', '$addSubjectTeacher', '$addCourseSubjectProgramHead', '$addTeacherId', '$addSyStart', '$addSyEnd', '$addSySemester', '$addMeetingDay', '$addMeetingTimeStart', '$addMeetingTimeEnd', '$addSubjectRoom')";

    $insertResult = $con->query($insertCourseSubject);

    if ($insertResult === TRUE) {
        $lastInsertId = mysqli_insert_id($con);

        // Define an array of sessions and components
        $sessions = [
            ['name' => 'Preliminary', 'percentage' => '20', 'components' => [
                ['name' => 'Regular Quizzes', 'percentage' => '25'],
                ['name' => 'Participation', 'percentage' => '25'],
                ['name' => 'Requirements', 'percentage' => '25'],
                ['name' => 'Exam', 'percentage' => '25'],
            ]],
            ['name' => 'Midterm', 'percentage' => '20', 'components' => [
                ['name' => 'Regular Quizzes', 'percentage' => '25'],
                ['name' => 'Participation', 'percentage' => '25'],
                ['name' => 'Requirements', 'percentage' => '25'],
                ['name' => 'Exam', 'percentage' => '25'],
            ]],
            ['name' => 'SemiFinals', 'percentage' => '20', 'components' => [
                ['name' => 'Regular Quizzes', 'percentage' => '25'],
                ['name' => 'Participation', 'percentage' => '25'],
                ['name' => 'Requirements', 'percentage' => '25'],
                ['name' => 'Exam', 'percentage' => '25'],
            ]],
            ['name' => 'Finals', 'percentage' => '40', 'components' => [
                ['name' => 'Regular Quizzes', 'percentage' => '25'],
                ['name' => 'Participation', 'percentage' => '25'],
                ['name' => 'Requirements', 'percentage' => '25'],
                ['name' => 'Exam', 'percentage' => '25'],
            ]],
        ];

        // Insert grading sessions and components
        foreach ($sessions as $session) {
            $sessionName = $session['name'];
            $sessionPercentage = $session['percentage'];

            // Insert grading sessions
            $insertSession = "INSERT INTO grading_session_table (grading_session_name, grading_session_base, grading_session_percentage, course_subject_id) VALUES ('$sessionName', 60, '$sessionPercentage', '$lastInsertId')";

            $insertSessionResult = $con->query($insertSession);

            // Check if grading sessions were inserted successfully
            if ($insertSessionResult === TRUE) {
                $lastGradingSessionId = mysqli_insert_id($con);

                // Insert components for each session
                foreach ($session['components'] as $component) {
                    $componentName = $component['name'];
                    $componentPercentage = $component['percentage'];

                    // Insert components using the last grading session ID
                    $insertComponent = "INSERT INTO component_table (component_name, component_percentage, grading_session_id) VALUES ('$componentName', '$componentPercentage', '$lastGradingSessionId')";

                    $insertComponentResult = $con->query($insertComponent);

                    $lastComponentId = mysqli_insert_id($con);

                    // Check if components were inserted successfully
                    if (!$insertComponentResult) {
                        echo json_encode(['status' => 'error', 'message' => 'Adding Components Unsuccessful']);
                        exit; // Exit the loop if any component insertion fails
                    }

                    if($componentName == 'Requirements' || $componentName == 'Exam'){
                        $numberOfLoop = 1;
                    }else{
                        $numberOfLoop = 5;
                    }
                    // Insert component values for each component
                    for ($i = 1; $i <= $numberOfLoop; $i++) {

                        // component value name
                        $componentValueName = '';
                        $words = explode(' ', $componentName);
                        foreach ($words as $word) {
                            $componentValueName .= strtoupper(substr($word, 0, 1));
                        }
                        $componentValueName .= $i;

                        $insertComponentValue = "INSERT INTO component_value_table (component_value_name, component_value, component_id) VALUES ('$componentValueName', '0', '$lastComponentId')";

                        $insertComponentValueResult = $con->query($insertComponentValue);

                        $lastComponentValueId = mysqli_insert_id($con);

                        $getStudentId = "SELECT student_id FROM student_table WHERE course_subject_id = '$lastInsertId'";
                        $getStudentIdResult = $con->query($getStudentId);

                        while($studentId = $getStudentIdResult->fetch_assoc()){

                            $checkStudentGrade = "SELECT COUNT(*) AS NumberOfStudent FROM student_grade_table WHERE student_id = '{$studentId['student_id']}' AND component_value_id = '$lastComponentValueId'";

                            $checkStudentGradeResult = $con->query($checkStudentGrade)->fetch_assoc();

                            if($checkStudentGradeResult['NumberOfStudent'] != 0){
                                continue;
                            }else{
                                $insertStudentGrade = "INSERT INTO student_grade_table (student_grade, student_id, component_value_id) VALUES (0, '{$studentId['student_id']}', '$lastComponentValueId')";
                                $insertStudentGradeResult = $con->query($insertStudentGrade);
                            }
                        }

                        // Check if component values were inserted successfully
                        if (!$insertComponentValueResult) {
                            echo json_encode(['status' => 'error', 'message' => 'Adding Component Values Unsuccessful']);
                            exit; // Exit the loop if any component value insertion fails
                        }
                    }
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Adding Grading Sessions Unsuccessful']);
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Course/Section Successfully Added']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Adding Course/Section Unsuccessful']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>


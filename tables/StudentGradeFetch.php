    <?php 

    // $UserAccountId = isset($_GET['UserAccountId']) ? $_GET['UserAccountId'] : '';
    // $currentSubjectId = isset($_GET['currentSubjectId']) ? $_GET['currentSubjectId'] : '';
    // $currentSelectedSessionId = isset($_GET['currentSelectedSessionId']) ? $_GET['currentSelectedSessionId'] : '';
    // // Database connection info 

    // $dbDetails = array( 
    //     'host' => 'localhost', 
    //     'user' => 'root', 
    //     'pass' => '', 
    //     'db'   => 'gradingsystem' 
    // ); 

    // $table = <<<EOT
    // (
    //     SELECT 
    //         cSubject.course_subject_id, 
    //         student.student_full_name,
    //         student.student_gender,
    //         CONCAT('[', GROUP_CONCAT(
    //             JSON_OBJECT(
    //                 'component_value_id', cValue.component_value_id,
    //                 'student_grade', COALESCE(sGrade.student_grade, 0),
    //                 'student_id', COALESCE(student.student_id, 0),
    //                 'component_value', cValue.component_value
    //             )
    //             ORDER BY cValue.component_value_id
    //         ), ']') AS student_grades,            
    //         component.component_id, 
    //         SUM(COALESCE(cValue.component_value_id, 0)) AS total_component_value
    //     FROM course_subject_table AS cSubject
    //     LEFT JOIN grading_session_table AS gSession ON cSubject.course_subject_id = gSession.course_subject_id
    //     LEFT JOIN student_table AS student ON cSubject.course_subject_id = student.course_subject_id
    //     JOIN component_table AS component ON component.grading_session_id = gSession.grading_session_id
    //     LEFT JOIN component_value_table AS cValue ON cValue.component_id = component.component_id
    //     LEFT JOIN student_grade_table AS sGrade ON sGrade.student_id = student.student_id AND sGrade.component_value_id = cValue.component_value_id
    //     WHERE cSubject.teacher_id = '$UserAccountId' 
    //         AND cSubject.course_subject_id = '$currentSubjectId' 
    //         AND gSession.grading_session_id = '$currentSelectedSessionId'
    //     GROUP BY student.student_full_name
    // ) temp
    // EOT;
    
    // // Table's primary key 
    // $primaryKey = 'course_subject_id'; 
    
    // // error_log($table);

    // $columns = array(
    //     array('db' => 'course_subject_id', 'dt' => 0), // Counter
    //     array('db' => 'student_full_name', 'dt' => 1),
    //     array('db' => 'student_grades', 'dt' => 2),
    //     array('db' => 'total_component_value', 'dt' => 3),
    //     array('db' => 'student_gender', 'dt' => 4),
    // );

    // // Include SQL query processing class 
    // require 'ssp.php'; 
    // // Output data as json format 
    // echo json_encode(SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns));
    ?>
    <?php 
$UserAccountId = isset($_GET['UserAccountId']) ? $_GET['UserAccountId'] : '';
$currentSubjectId = isset($_GET['currentSubjectId']) ? $_GET['currentSubjectId'] : '';
$currentSelectedSessionId = isset($_GET['currentSelectedSessionId']) ? $_GET['currentSelectedSessionId'] : '';

$dbDetails = array( 
    'host' => 'localhost', 
    'user' => 'root', 
    'pass' => '', 
    'db'   => 'gradingsystem' 
);

$table = <<<EOT
(
    SELECT 
        cSubject.course_subject_id, 
        student.student_full_name,
        student.student_gender,
        CONCAT('[', GROUP_CONCAT(
            JSON_OBJECT(
                'component_value_id', cValue.component_value_id,
                'student_grade', COALESCE(sGrade.student_grade, 0),
                'student_id', COALESCE(student.student_id, 0),
                'component_value', cValue.component_value
            )
            ORDER BY cValue.component_value_id
        ), ']') AS student_grades,            
        component.component_id, 
        SUM(COALESCE(cValue.component_value_id, 0)) AS total_component_value
    FROM course_subject_table AS cSubject
    LEFT JOIN grading_session_table AS gSession ON cSubject.course_subject_id = gSession.course_subject_id
    LEFT JOIN student_table AS student ON cSubject.course_subject_id = student.course_subject_id
    JOIN component_table AS component ON component.grading_session_id = gSession.grading_session_id
    LEFT JOIN component_value_table AS cValue ON cValue.component_id = component.component_id
    LEFT JOIN student_grade_table AS sGrade ON sGrade.student_id = student.student_id AND sGrade.component_value_id = cValue.component_value_id
    WHERE cSubject.teacher_id = '$UserAccountId' 
        AND cSubject.course_subject_id = '$currentSubjectId' 
        AND gSession.grading_session_id = '$currentSelectedSessionId'
    GROUP BY student.student_full_name
) temp
EOT;

$primaryKey = 'course_subject_id'; 

$columns = array(
    array('db' => 'course_subject_id', 'dt' => 0), // Counter
    array('db' => 'student_full_name', 'dt' => 1),
    array('db' => 'student_grades', 'dt' => 2),
    array('db' => 'total_component_value', 'dt' => 3),
    array('db' => 'student_gender', 'dt' => 4),
);

require 'ssp.php';

$mysqli = new mysqli($dbDetails['host'], $dbDetails['user'], $dbDetails['pass'], $dbDetails['db']);

// Check the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$checkNumberOfStudent = "SELECT COUNT(*) AS numberOfStudent FROM student_table WHERE course_subject_id = '$currentSubjectId'";
$checkNumberOfStudentResult = $mysqli->query($checkNumberOfStudent)->fetch_assoc();

// Check if there are no records returned or if there are only records with empty student_grades
if ($checkNumberOfStudentResult['numberOfStudent'] != 0) {
    $result = SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns);
}else{
    $result['data'] = array(); // Set data to empty array
}

echo json_encode($result);
?>

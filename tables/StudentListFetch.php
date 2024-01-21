<?php

$UserAccountId = isset($_GET['UserAccountId']) ? $_GET['UserAccountId'] : '';

$dbDetails = array(
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'db'   => 'gradingsystem'
);

$table = <<<EOT
(
    SELECT
        s.student_id,
        s.student_full_name,
        s.student_status,
        s.student_number,
        s.student_gender,
        c.course_subject_name
    FROM
        course_subject_table c
    JOIN
        student_table s ON c.course_subject_id = s.course_subject_id
    WHERE
        c.teacher_id = '$UserAccountId'
) temp
EOT;

$primaryKey = 'student_id';

$columns = array(
    array('db' => 'student_id', 'dt' => 0),
    array('db' => 'student_full_name', 'dt' => 1),
    array('db' => 'student_number', 'dt' => 2),
    array('db' => 'course_subject_name', 'dt' => 3),
    array('db' => 'student_status', 'dt' => 4),
    array('db' => 'student_gender', 'dt' => 5),
);

require 'ssp.php';

echo json_encode(
    SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
);
?>

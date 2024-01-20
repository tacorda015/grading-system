    <?php 

    $UserAccountId = isset($_GET['UserAccountId']) ? $_GET['UserAccountId'] : '';
    // Database connection info 

    $dbDetails = array( 
        'host' => 'localhost', 
        'user' => 'root', 
        'pass' => '', 
        'db'   => 'gradingsystem' 
    ); 
    
    // DB table to use 
    // $table = 'members'; 
    $table = <<<EOT
    (
        SELECT 
        course_subject_id , 
        course_subject_name, 
        course_name, 
        subject_name, 
        sy_start, 
        sy_end, 
        course_subject_day, 
        course_subject_time_start, 
        course_subject_time_end, 
        course_subject_room
        FROM course_subject_table
        WHERE teacher_id = '$UserAccountId'
    ) temp
    EOT;
    
    // Table's primary key 
    $primaryKey = 'course_subject_id'; 
    

    $columns = array(
        array('db' => 'course_subject_id', 'dt' => 0), // Counter
        array('db' => 'course_subject_name', 'dt' => 1),
        array('db' => 'course_subject_day', 'dt' => 2),
        array('db' => 'course_subject_time_start', 'dt' => 3),
        array('db' => 'course_subject_room', 'dt' => 4),
        array('db' => 'course_subject_time_end', 'dt' => 5),
    );

    // Include SQL query processing class 
    require 'ssp.php'; 

    // Output data as json format 
    echo json_encode( 
        SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns)

    );
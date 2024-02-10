    <?php 

    $UserAccountId = isset($_GET['UserAccountId']) ? $_GET['UserAccountId'] : '';
    $currentSubjectId = isset($_GET['currentSubjectId']) ? $_GET['currentSubjectId'] : '';
    $currentSelectedSessionId = isset($_GET['currentSelectedSessionId']) ? $_GET['currentSelectedSessionId'] : '';
    // Database connection info 

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
                JSON_OBJECT('component_value_id', cValue.component_value_id, 'student_grade', COALESCE(sGrade.student_grade, 0), 'student_id', student.student_id, 'component_value', cValue.component_value)
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
        GROUP BY student.student_full_name
    ) temp
    EOT;
    
    // Table's primary key 
    $primaryKey = 'course_subject_id'; 
    
    // error_log($table);

    $columns = array(
        array('db' => 'course_subject_id', 'dt' => 0), // Counter
        array('db' => 'student_full_name', 'dt' => 1),
        array('db' => 'student_grades', 'dt' => 2),
        array('db' => 'total_component_value', 'dt' => 3),
        array('db' => 'student_gender', 'dt' => 4),
    );

    // Include SQL query processing class 
    require 'ssp.php'; 
    // Output data as json format 
    echo json_encode(SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns));

    // SELECT
    //   s.student_id,
    //   s.student_full_name,
      
    //   -- First Session and First Component
    //   (SELECT SUM(sg.student_grade)
    //    FROM student_grade_table sg
    //    JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //    JOIN component_table ct ON cv.component_id = ct.component_id
    //    JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //    WHERE gs.course_subject_id = 3 AND sg.student_id = s.student_id
    //    GROUP BY ct.component_id -- Add this line to group by component_id
    //    ORDER BY ct.component_id
    //    LIMIT 1
    //   ) AS FSFC,
    
    //  -- First Session and First Percent
    //   (SELECT c.component_percentage
    //    FROM component_table c
    //    WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1)
    //    LIMIT 1
    //   ) AS FSFP,
      
    //   -- First Session and Second Compnent
    //   (SELECT SUM(sg.student_grade)
    //    FROM student_grade_table sg
    //    JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //    JOIN component_table ct ON cv.component_id = ct.component_id
    //    JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //    WHERE gs.course_subject_id = 3 AND sg.student_id = s.student_id
    //    GROUP BY ct.component_id -- Add this line to group by component_id
    //    ORDER BY ct.component_id
    //    LIMIT 1 OFFSET 1
    //   ) AS FSSC,
      
    //   -- First Session and Second Percent
    //   (SELECT c.component_percentage
    //    FROM component_table c
    //    WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1)
    //    LIMIT 1 OFFSET 1
    //   ) AS FSSP,
      
    //   -- First Session and Third Compnent
    //   (SELECT SUM(sg.student_grade)
    //    FROM student_grade_table sg
    //    JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //    JOIN component_table ct ON cv.component_id = ct.component_id
    //    JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //    WHERE gs.course_subject_id = 3 AND sg.student_id = s.student_id
    //    GROUP BY ct.component_id -- Add this line to group by component_id
    //    ORDER BY ct.component_id
    //    LIMIT 1 OFFSET 2
    //   ) AS FSTC,
      
    //   -- First Session and Third Percent
    //   (SELECT c.component_percentage
    //    FROM component_table c
    //    WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1)
    //    LIMIT 1 OFFSET 2
    //   ) AS FSTP,
      
    //   -- First Session and Fourth Compnent
    //   (SELECT SUM(sg.student_grade)
    //    FROM student_grade_table sg
    //    JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //    JOIN component_table ct ON cv.component_id = ct.component_id
    //    JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //    WHERE gs.course_subject_id = 3 AND sg.student_id = s.student_id
    //    GROUP BY ct.component_id
    //    ORDER BY ct.component_id
    //    LIMIT 1 OFFSET 3
    //   ) AS FSFthC,
      
    //   -- First Session and Fourth Percent
    //   (SELECT c.component_percentage
    //    FROM component_table c
    //    WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1)
    //    LIMIT 1 OFFSET 3
    //   ) AS FSFthP,
      
    //   -- Second Session and First Component
    // (SELECT SUM(sg.student_grade)
    //  FROM student_grade_table sg
    //  JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //  JOIN component_table ct ON cv.component_id = ct.component_id
    //  JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //  WHERE gs.course_subject_id = 3 
    //    AND gs.grading_session_id = (
    //      SELECT grading_session_id
    //      FROM grading_session_table
    //      WHERE course_subject_id = 3
    //      ORDER BY grading_session_id
    //      LIMIT 1 OFFSET 1
    //    )
    //    AND sg.student_id = s.student_id
    //  GROUP BY ct.component_id
    //  ORDER BY ct.component_id
    //  LIMIT 1
    // ) AS SSFC,
    
    // -- Second Session and First Percent
    // (SELECT c.component_percentage
    //  FROM component_table c
    //  WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1 OFFSET 1)
    //  LIMIT 1
    // ) AS SSFP,
    
    // -- Second Session and Second Component
    // (SELECT SUM(sg.student_grade)
    //  FROM student_grade_table sg
    //  JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //  JOIN component_table ct ON cv.component_id = ct.component_id
    //  JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //  WHERE gs.course_subject_id = 3 
    //    AND gs.grading_session_id = (
    //      SELECT grading_session_id
    //      FROM grading_session_table
    //      WHERE course_subject_id = 3
    //      ORDER BY grading_session_id
    //      LIMIT 1 OFFSET 1
    //    )
    //    AND sg.student_id = s.student_id
    //  GROUP BY ct.component_id
    //  ORDER BY ct.component_id
    //  LIMIT 1 OFFSET 1
    // ) AS SSSC,
    
    // -- Second Session and Second Percent
    // (SELECT c.component_percentage
    //  FROM component_table c
    //  WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1 OFFSET 1)
    //  LIMIT 1 OFFSET 1
    // ) AS SSSP,
    
    // -- Second Session and Third Component
    // (SELECT SUM(sg.student_grade)
    //  FROM student_grade_table sg
    //  JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //  JOIN component_table ct ON cv.component_id = ct.component_id
    //  JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //  WHERE gs.course_subject_id = 3 
    //    AND gs.grading_session_id = (
    //      SELECT grading_session_id
    //      FROM grading_session_table
    //      WHERE course_subject_id = 3
    //      ORDER BY grading_session_id
    //      LIMIT 1 OFFSET 1
    //    )
    //    AND sg.student_id = s.student_id
    //  GROUP BY ct.component_id
    //  ORDER BY ct.component_id
    //  LIMIT 1 OFFSET 2
    // ) AS SSTC,
    
    // -- Second Session and Third Percent
    // (SELECT c.component_percentage
    //  FROM component_table c
    //  WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1 OFFSET 1)
    //  LIMIT 1 OFFSET 2
    // ) AS SSTP,
    
    // -- Second Session and Fourth Component
    // (SELECT SUM(sg.student_grade)
    //  FROM student_grade_table sg
    //  JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //  JOIN component_table ct ON cv.component_id = ct.component_id
    //  JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //  WHERE gs.course_subject_id = 3 
    //    AND gs.grading_session_id = (
    //      SELECT grading_session_id
    //      FROM grading_session_table
    //      WHERE course_subject_id = 3
    //      ORDER BY grading_session_id
    //      LIMIT 1 OFFSET 1
    //    )
    //    AND sg.student_id = s.student_id
    //  GROUP BY ct.component_id
    //  ORDER BY ct.component_id
    //  LIMIT 1 OFFSET 3
    // ) AS SSFthC,
    
    // -- Second Session and Fourth Percent
    // (SELECT c.component_percentage
    //  FROM component_table c
    //  WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1 OFFSET 1)
    //  LIMIT 1 OFFSET 3
    // ) AS SSFthP,
    
    // -- Third Session and First Component
    // (SELECT SUM(sg.student_grade)
    //  FROM student_grade_table sg
    //  JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //  JOIN component_table ct ON cv.component_id = ct.component_id
    //  JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //  WHERE gs.course_subject_id = 3 
    //    AND gs.grading_session_id = (
    //      SELECT grading_session_id
    //      FROM grading_session_table
    //      WHERE course_subject_id = 3
    //      ORDER BY grading_session_id
    //      LIMIT 1 OFFSET 2
    //    )
    //    AND sg.student_id = s.student_id
    //  GROUP BY ct.component_id
    //  ORDER BY ct.component_id
    //  LIMIT 1
    // ) AS TSFC,
    
    // -- Third Session and First Percent
    // (SELECT c.component_percentage
    //  FROM component_table c
    //  WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1 OFFSET 2)
    //  LIMIT 1
    // ) AS SSFP,
    
    // -- Third Session and Second Component
    // (SELECT SUM(sg.student_grade)
    //  FROM student_grade_table sg
    //  JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //  JOIN component_table ct ON cv.component_id = ct.component_id
    //  JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //  WHERE gs.course_subject_id = 3 
    //    AND gs.grading_session_id = (
    //      SELECT grading_session_id
    //      FROM grading_session_table
    //      WHERE course_subject_id = 3
    //      ORDER BY grading_session_id
    //      LIMIT 1 OFFSET 2
    //    )
    //    AND sg.student_id = s.student_id
    //  GROUP BY ct.component_id
    //  ORDER BY ct.component_id
    //  LIMIT 1 OFFSET 1
    // ) AS TSSC,
    
    // -- Third Session and Second Percent
    // (SELECT c.component_percentage
    //  FROM component_table c
    //  WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1 OFFSET 2)
    //  LIMIT 1 OFFSET 1
    // ) AS TSSP,
    
    // -- Third Session and Third Component
    // (SELECT SUM(sg.student_grade)
    //  FROM student_grade_table sg
    //  JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //  JOIN component_table ct ON cv.component_id = ct.component_id
    //  JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //  WHERE gs.course_subject_id = 3 
    //    AND gs.grading_session_id = (
    //      SELECT grading_session_id
    //      FROM grading_session_table
    //      WHERE course_subject_id = 3
    //      ORDER BY grading_session_id
    //      LIMIT 1 OFFSET 2
    //    )
    //    AND sg.student_id = s.student_id
    //  GROUP BY ct.component_id
    //  ORDER BY ct.component_id
    //  LIMIT 1 OFFSET 2
    // ) AS TSTC,
    
    // -- Third Session and Third Percent
    // (SELECT c.component_percentage
    //  FROM component_table c
    //  WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1 OFFSET 2)
    //  LIMIT 1 OFFSET 2
    // ) AS TSTP,
    
    // -- Third Session and Fourth Component
    // (SELECT SUM(sg.student_grade)
    //  FROM student_grade_table sg
    //  JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //  JOIN component_table ct ON cv.component_id = ct.component_id
    //  JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //  WHERE gs.course_subject_id = 3 
    //    AND gs.grading_session_id = (
    //      SELECT grading_session_id
    //      FROM grading_session_table
    //      WHERE course_subject_id = 3
    //      ORDER BY grading_session_id
    //      LIMIT 1 OFFSET 2
    //    )
    //    AND sg.student_id = s.student_id
    //  GROUP BY ct.component_id
    //  ORDER BY ct.component_id
    //  LIMIT 1 OFFSET 3
    // ) AS TSFthC,
    
    // -- Third Session and Fourth Percent
    // (SELECT c.component_percentage
    //  FROM component_table c
    //  WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1 OFFSET 2)
    //  LIMIT 1 OFFSET 3
    // ) AS TSFthP,
        
    // -- Fourth Session and First Component
    // (SELECT SUM(sg.student_grade)
    //  FROM student_grade_table sg
    //  JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //  JOIN component_table ct ON cv.component_id = ct.component_id
    //  JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //  WHERE gs.course_subject_id = 3 
    //    AND gs.grading_session_id = (
    //      SELECT grading_session_id
    //      FROM grading_session_table
    //      WHERE course_subject_id = 3
    //      ORDER BY grading_session_id
    //      LIMIT 1 OFFSET 3
    //    )
    //    AND sg.student_id = s.student_id
    //  GROUP BY ct.component_id
    //  ORDER BY ct.component_id
    //  LIMIT 1
    // ) AS FthSFC,
    
    // -- Fourth Session and First Percent
    // (SELECT c.component_percentage
    //  FROM component_table c
    //  WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1 OFFSET 3)
    //  LIMIT 1
    // ) AS FthSFP,
    
    // -- Fourth Session and Second Component
    // (SELECT SUM(sg.student_grade)
    //  FROM student_grade_table sg
    //  JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //  JOIN component_table ct ON cv.component_id = ct.component_id
    //  JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //  WHERE gs.course_subject_id = 3 
    //    AND gs.grading_session_id = (
    //      SELECT grading_session_id
    //      FROM grading_session_table
    //      WHERE course_subject_id = 3
    //      ORDER BY grading_session_id
    //      LIMIT 1 OFFSET 3
    //    )
    //    AND sg.student_id = s.student_id
    //  GROUP BY ct.component_id
    //  ORDER BY ct.component_id
    //  LIMIT 1 OFFSET 1
    // ) AS FthSSC,
    
    // -- Fourth Session and Second Percent
    // (SELECT c.component_percentage
    //  FROM component_table c
    //  WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1 OFFSET 3)
    //  LIMIT 1 OFFSET 1
    // ) AS FthSSP,
    
    // -- Fourth Session and Third Component
    // (SELECT SUM(sg.student_grade)
    //  FROM student_grade_table sg
    //  JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //  JOIN component_table ct ON cv.component_id = ct.component_id
    //  JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //  WHERE gs.course_subject_id = 3 
    //    AND gs.grading_session_id = (
    //      SELECT grading_session_id
    //      FROM grading_session_table
    //      WHERE course_subject_id = 3
    //      ORDER BY grading_session_id
    //      LIMIT 1 OFFSET 3
    //    )
    //    AND sg.student_id = s.student_id
    //  GROUP BY ct.component_id
    //  ORDER BY ct.component_id
    //  LIMIT 1 OFFSET 2
    // ) AS FthSTC,
    
    // -- Fourth Session and Third Percent
    // (SELECT c.component_percentage
    //  FROM component_table c
    //  WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1 OFFSET 3)
    //  LIMIT 1 OFFSET 2
    // ) AS FthSTP,
    
    // -- Fourth Session and Fourth Component
    // (SELECT SUM(sg.student_grade)
    //  FROM student_grade_table sg
    //  JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
    //  JOIN component_table ct ON cv.component_id = ct.component_id
    //  JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
    //  WHERE gs.course_subject_id = 3 
    //    AND gs.grading_session_id = (
    //      SELECT grading_session_id
    //      FROM grading_session_table
    //      WHERE course_subject_id = 3
    //      ORDER BY grading_session_id
    //      LIMIT 1 OFFSET 3
    //    )
    //    AND sg.student_id = s.student_id
    //  GROUP BY ct.component_id
    //  ORDER BY ct.component_id
    //  LIMIT 1 OFFSET 3
    // ) AS FthSFthC,
    
    // -- Fourth Session and Third Percent
    // (SELECT c.component_percentage
    //  FROM component_table c
    //  WHERE c.grading_session_id = (SELECT grading_session_id FROM grading_session_table WHERE course_subject_id = 3 LIMIT 1 OFFSET 3)
    //  LIMIT 1 OFFSET 3
    // ) AS FthSFthP
    
      
    // FROM
    //   student_table s
    // WHERE
    //   s.course_subject_id = 3;

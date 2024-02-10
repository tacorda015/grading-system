<?php
    session_start();
    include "./database/connection.php";

    if (!isset($_SESSION['account_id'])) {
        header("Location: index.php");
        exit();
    }
    $account_id = $_SESSION['account_id'];

    $getUser = "SELECT * FROM account_table WHERE account_id = {$account_id}";
    $getUserResult = $con->query($getUser);
    $getUserData = $getUserResult->fetch_assoc();
    $UserAccountId = $getUserData['account_id'];

    if(!isset($_SESSION['SessionArray'])){
        header("Location: home.php");
        exit();
    }

    $SessionArray = $_SESSION['SessionArray'];

    $CourseSubjectIdSetted = $SessionArray['CourseSubjectIdSetted'];
    $GradingSessionIdSetted = $SessionArray['GradingSessionIdSetted'];  
    $CourseSubjectNameSetted = $SessionArray['CourseSubjectNameSetted'];

    $sql = "SELECT 
            s.student_id,
            s.student_full_name,
            gs.grading_session_id,
            gs.grading_session_percentage,
            gs.grading_session_name,
            ct.component_id,
            ct.component_name,
            ct.component_percentage,
            gs.grading_session_base,
            GROUP_CONCAT(sg.student_grade) AS component_grades,
            GROUP_CONCAT(cv.component_value) AS component_values
        FROM 
            student_table s
            LEFT JOIN student_grade_table sg ON s.student_id = sg.student_id
            LEFT JOIN component_value_table cv ON sg.component_value_id = cv.component_value_id
            LEFT JOIN component_table ct ON cv.component_id = ct.component_id
            LEFT JOIN grading_session_table gs ON ct.grading_session_id = gs.grading_session_id
        WHERE 
            s.course_subject_id = '$CourseSubjectIdSetted'
        GROUP BY 
            s.student_id, gs.grading_session_id, ct.component_id";

    $result = $con->query($sql);

    $studentsData = array();
    if ($result->num_rows > 0) {
    
        while ($row = $result->fetch_assoc()) {
            $studentId = $row['student_full_name'];
            $gradingSessionId = $row['grading_session_id'];
            $gradingSessionPercentage = $row['grading_session_percentage'];
            $componentId = $row['component_id'];
            $componentName = $row['component_name'];
            $componentPercentage = $row['component_percentage'];
            $gradingSessionBase = (int)$row['grading_session_base'];
            $componentGrades = explode(',', $row['component_grades']);
            $componentValues = explode(',', $row['component_values']);
    
            // Initialize $allPossibleComponentIds
            $allPossibleComponentIds = range(0, count($componentGrades) - 1);
    
            // Initialize arrays for all possible component IDs with default values of 0
            $allComponentGrades = array_fill_keys($allPossibleComponentIds, 0);
            $allComponentValues = array_fill_keys($allPossibleComponentIds, 0);
    
            // Fill in actual grades and values at the correct component IDs
            foreach ($componentGrades as $key => $grade) {
                $allComponentGrades[$key] = $grade;
                $allComponentValues[$key] = $componentValues[$key];
            }
    
            // Calculate weighted grades for each component
            $weightedGrades = calculateWeightedGrades($allComponentGrades, $allComponentValues, $gradingSessionBase, $componentPercentage);
    
            // Save the data in the array
            $studentsData[$studentId][$gradingSessionId]['grading_session_percentage'] = $gradingSessionPercentage;
            $studentsData[$studentId][$gradingSessionId]['components'][$componentId][0] = $allComponentGrades;
            $studentsData[$studentId][$gradingSessionId]['components'][$componentId][1] = $allComponentValues;
            $studentsData[$studentId][$gradingSessionId]['components'][$componentId][2] = $weightedGrades;
            
        }
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <!-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script> -->
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script src="./node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="./node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    
    <script src="./node_modules/jszip/dist/jszip.min.js"></script>
    <script src="./node_modules/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="./node_modules/pdfmake/build/pdfmake.min.js"></script>
    <script src="./node_modules/pdfmake/build/vfs_fonts.js"></script>
    <script src="./node_modules/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="./node_modules/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script> -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script> // Not Needed--> 
    <!-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script> // Not Needed -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> -->
</head>
<body>
    <table id="dataTable">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>PG</th>
                <th>%</th>
                <th>MG</th>
                <th>%</th>
                <th>SG</th>
                <th>%</th>
                <th>FG</th>
                <th>%</th>
                <th>Final</th>
                <th>Equivalent</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if($studentsData != NULL || $studentsData != ''){
                foreach ($studentsData as $studentId => $data) {
                    echo "<tr>";
                    echo "<td>". $studentId ."</td>";
                    $final = 0;
                    foreach ($data as $gradingSessionId => $sessionData) {
                
                        // Calculate and output the total weighted grade for the session
                        $totalWeightedGrade = calculateTotalWeightedGrade($sessionData['components']);
                        echo "<td>". $totalWeightedGrade ."</td>";
                        echo "<td>". number_format((float)$totalWeightedGrade * ($sessionData['grading_session_percentage'] / 100), 2) ."</td>";
                        $final += number_format((float)$totalWeightedGrade * ($sessionData['grading_session_percentage'] / 100), 2);
                    }
                    echo "<td>". $final ."</td>";
    
                    $Equivalent = calculateFinalEquivalent($final);
                    echo "<td>". $Equivalent ."</td>";
    
                    $Remarks = calculateFinalRemarks($final);
                    echo "<td>". $Remarks ."</td>";
                    
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
<?php
function calculateWeightedGrades($grades, $values, $base, $componentPercentage) {
    $totalNormalizedGrades = 0;
    $totalComponentValues = 0;

    // Calculate the total sum of normalized grades and total sum of component values
    foreach ($grades as $key => $grade) {
        $totalNormalizedGrades += (int) $grade;
        $totalComponentValues += (int) $values[$key];
    }

    // Calculate the weighted grade using the total sum of normalized grades and component values
    $weightedGrade = (($totalNormalizedGrades / max(1, $totalComponentValues)) * (100 - $base) + $base) * ($componentPercentage / 100);

    return $weightedGrade;
}
// Function to calculate total weighted grade for the session
function calculateTotalWeightedGrade($sessionData) {
    $totalWeightedGrade = 0;

    foreach ($sessionData as $componentData) {
        // Check if index 2 exists in $componentData
        if (isset($componentData[2])) {
            $totalWeightedGrade += number_format((float)$componentData[2], 2);
        } else {
            echo "Debugging: Missing value at index 2\n";
            var_dump($componentData); // Output the componentData array for debugging
        }
    }

    return number_format((float)$totalWeightedGrade, 2);
}

function calculateFinalEquivalent($final) {
    $gradeMapping = [
        '99-100%' => 1.00,
        '96-98%'  => 1.25,
        '93-95%'  => 1.50,
        '90-92%'  => 1.75,
        '87-89%'  => 2.00,
        '84-86%'  => 2.25,
        '81-83%'  => 2.50,
        '78-80%'  => 2.75,
        '75-77%'  => 3.00,
        '65-74%'  => 4.00,
        'FAILED'  => 5.00,
    ];

    // Find the corresponding grade description
    foreach ($gradeMapping as $grade => $range) {
        if ($final >= $grade) {
            return $range;
        }
    }

    return 'Failed';
}

function calculateFinalRemarks($final) {
    $gradeMapping = [
        '99-100%' => 1.00,
        '96-98%'  => 1.25,
        '93-95%'  => 1.50,
        '90-92%'  => 1.75,
        '87-89%'  => 2.00,
        '84-86%'  => 2.25,
        '81-83%'  => 2.50,
        '78-80%'  => 2.75,
        '75-77%'  => 3.00,
        '65-74%'  => 4.00,
        'FAILED'  => 5.00,
    ];

    foreach ($gradeMapping as $grade => $range) {
        if ($final >= $grade) {
            // return $range;
            return ((float)$range <= 3.00) ? 'Passed' : 'Failed';
        }
    }

    return 'Failed';
}
?>
<script>
  $(document).ready(function () {
    $('#dataTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
  });
</script>

</body>
</html>
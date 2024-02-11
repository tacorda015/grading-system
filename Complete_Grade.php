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
$jsonStudentsData = json_encode($studentsData, JSON_PRETTY_PRINT);

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Grade</title>

    <!-- Jquery -->
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="./image/favicon.ico">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./node_modules/bootstrap-icons/font/bootstrap-icons.min.css">

    <!-- Manual CSS -->
    <link rel="stylesheet" href="./CSS/main.css">

    <!-- Sweet Alert 2 -->
    <link rel="stylesheet" href="./node_modules/sweetalert2/dist/sweetalert2.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="./node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="./node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="./node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css">


    <style>
        th, td {
            text-align: center !important;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        
        <!-- NavBar -->
        <?php include './Navigation/NavBar.php' ?>

        <!-- SideBar -->
        <?php include './Navigation/SideBar.php' ?>

        <section>
            <div class="row">
                <div class="col-12 my-2">
                    <!-- Add Course/Subject Button -->
                    <div class="d-flex gap-3 flex-md-row flex-column justify-content-between">

                        <div class="d-flex gap-2 flex-sm-row flex-column">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentCourseModal">
                            <i class="bi bi-person-add"></i> Student
                            </button>
                        </div>

                        <div class="px-md-3 px-2 d-flex align-items-center justify-content-center">
                            <h3 class="text-center m-0"><?php echo $CourseSubjectNameSetted ?></h3>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <section>
        <ul class="nav nav-tabs">
            <?php
                $printGradeActive = (basename($_SERVER['PHP_SELF']) == 'Complete_Grade.php') ? 'active' : '';

                if($printGradeActive != 'active'){
                    $currentGradingSessionIdSetted = isset($_SESSION['SessionArray']['GradingSessionIdSetted']) ? $_SESSION['SessionArray']['GradingSessionIdSetted'] : null;
                }else{
                    $currentGradingSessionIdSetted = null;
                }

                $getSession = "SELECT * FROM grading_session_table WHERE course_subject_id = '{$CourseSubjectIdSetted}'";
                $getSessionResult = $con->query($getSession);
                if($getSessionResult->num_rows > 0){
                    while($collectedData = $getSessionResult->fetch_assoc()){
                        $isActive = ($collectedData['grading_session_id'] == $currentGradingSessionIdSetted) ? 'active' : '';
                        echo '
                                <li class="nav-item">
                                    <a class="nav-link gradingSession ' . $isActive . '" data-grading-session-id="' . $collectedData['grading_session_id'] . '"  data-course-subject-id="' . $CourseSubjectIdSetted . '">'. $collectedData['grading_session_name'] .'</a>
                                </li>
                            ';
                    }

                    echo '
                        <li class="nav-item">
                            <a class="nav-link ' . $printGradeActive . '" id="pdfBtn" href="./Complete_Grade.php">
                                Print Grade
                            </a>
                        </li>
                    ';
                    // echo '
                    //     <li class="nav-item">
                    //         <a class="nav-link" id="updateBtn" data-bs-toggle="modal" data-bs-target="#updateSessionModal">
                    //             <i class="bi bi-file-earmark-ruled"></i> Update Session
                    //         </a>
                    //     </li>
                    // ';
                    // <li class="nav-item">
                    //     <a class="nav-link btn btn-outline-primary text-dark" data-bs-toggle="modal" data-bs-target="#addSessionModal">
                    //         <i class="bi bi-file-earmark-plus"></i> Session
                    //     </a>
                    // </li>
                }else{
                    echo '<option value="">No Current Session</option>';
                }
            ?>
        </ul>
        </section>
        <section>
            <div class="row">
                <div class="col-12">
                    <div class="border rounded shadow p-2">
                    <table id="dataTable" class="table table-hover table-striped display nowrap w-100" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
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
                                    $counter = 1; // Initialize counter variable
                                    foreach ($studentsData as $studentId => $data) {
                                        echo "<tr>";
                                        echo "<td>". $counter ."</td>"; // Display counter
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
                                        $counter++; // Increment counter for next iteration
                                    }
                                }
                                ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php 
        require "./Modals/upload_grade_addStudent.php"; // For Add Student to Course/Subject
        require "./Modals/upload_grade_addSession.php"; // For Add Manual Session (Currently But Display)
        require "./Modals/upload_grade_updateSession.php"; // For Update Session 
        require "./Modals/upload_grade_updateComponent.php"; // For Update Session 
    ?>

<!-- Bootstrap -->
<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- Sweet Alert 2 -->
<script src="./node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
<!-- DataTables -->
<script src="./node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="./node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="./node_modules/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="./node_modules/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="./node_modules/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>

<script src="./JS/StudentAdd.js"></script>
<script src="./JS/GradingSessionEdit.js"></script>
<script src="./JS/GradingComponentEdit.js"></script>
<script src="./JS/SessionTabs.js"></script>

<!-- UnComment If Needed Manual Adding Session -->
<!-- <script src="./JS/SessionAdd.js"></script> -->

<script>
$(document).ready(function () {
    // Initialize DataTables with buttons
    var dataTable = $('#dataTable').DataTable({
        "scrollX": true,
        "dom": '<"row"lfBtip>',
        "buttons": [
            {
                text: 'Print Grade',
                action: function () {
                    // Get the data from the table
                    var tableData = dataTable.rows().data().toArray();

                    // Convert the data to JSON format
                    var jsonData = JSON.stringify(tableData);

                    // Log the jsonData to the console for debugging
                    console.log('jsonData:', jsonData);

                    // Create a form dynamically
                    var form = $('<form method="post" target="_blank" action="PDF_File.php"></form>');
                    var input = $('<input type="hidden" name="jsonData" value="' + encodeURIComponent(jsonData) + '" />');
                    var inputCourseSubjectId = $('<input type="hidden" name="courseSubjectId" value="' + encodeURIComponent('<?php echo $CourseSubjectIdSetted; ?>') + '" />');
                    var inputGradingSessionId = $('<input type="hidden" name="gradingSessionId" value="' + encodeURIComponent('<?php echo $GradingSessionIdSetted; ?>') + '" />');
                    var inputCourseSubjectName = $('<input type="hidden" name="courseSubjectName" value="' + encodeURIComponent('<?php echo $CourseSubjectNameSetted; ?>') + '" />');


                    // Append the input to the form
                    form.append(input);
                    form.append(inputCourseSubjectId);
                    form.append(inputGradingSessionId);
                    form.append(inputCourseSubjectName);

                    // Log the form HTML to the console for debugging
                    console.log('form HTML:', form[0].outerHTML);

                    // Append the form to the body
                    $('body').append(form);

                    // Submit the form to open a new tab
                    form.submit();

                    // Remove the form from the body
                    form.remove();
                }
            }
        ]
    });

    // Adjust the margin using CSS
    var buttonsContainer = dataTable.buttons().container();
    $('.dataTables_length').append(buttonsContainer);
    buttonsContainer.css('margin-left', '8px'); // Adjust the value as needed
});

</script>
</body>
</html>
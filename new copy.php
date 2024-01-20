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

// Get Data to grading_session_table
$GradingSessionQuery = "SELECT * FROM grading_session_table WHERE course_subject_id = '$CourseSubjectIdSetted' AND grading_session_id = '$GradingSessionIdSetted'"; 
$GradingSessionResult = $con->query($GradingSessionQuery)->fetch_assoc();
$GradingSessionBase = $GradingSessionResult['grading_session_base'];
$GradingSessionPercentage = $GradingSessionResult['grading_session_percentage'];

$ComponentTableQuery = "SELECT * FROM component_table WHERE grading_session_id = '$GradingSessionIdSetted'";
$ComponentTableResult = $con->query($ComponentTableQuery);
while ($ComponentRow = $ComponentTableResult->fetch_assoc()) {
    $componentId = $ComponentRow['component_id'];
    $componentPercentages[] = $ComponentRow['component_percentage'];
    $componentNames[] = $ComponentRow['component_name'];

    // Get Data to component_value_table
    $ComponentValueNameQuery = "SELECT component_value_name FROM component_value_table WHERE component_id = '$componentId'";
    $ComponentValueNameResult = $con->query($ComponentValueNameQuery);

    while ($componentValueNameRow = $ComponentValueNameResult->fetch_assoc()) {
        $componentDataName[$componentId][] = $componentValueNameRow['component_value_name'];
    }

    // Get Data to component_value_table
    $ComponentValueNameQuery = "SELECT component_value, component_id FROM component_value_table WHERE component_id = '$componentId'";
    $ComponentValueResult = $con->query($ComponentValueNameQuery);

    $componentTotals = array();
    while ($componentValueRow = $ComponentValueResult->fetch_assoc()) {
        $componentDataValue[$componentId][] = $componentValueRow['component_value'];
        $componentId = $componentValueRow['component_id'];
        $componentValue = $componentValueRow['component_value'];
        // Check if the component_id is already a key in the array
        if (array_key_exists($componentId, $componentTotals)) {
            // Add the current value to the existing total
            $componentTotals[$componentId] += $componentValue;
        } else {
            // Initialize the total for the component_id
            $componentTotals[$componentId] = $componentValue;
        }
    }
    foreach ($componentTotals as $currentComponentId => $total) {

        $ComponentTotalArray[] = $total;
    }
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
                $currentGradingSessionIdSetted = isset($_SESSION['SessionArray']['GradingSessionIdSetted']) ? $_SESSION['SessionArray']['GradingSessionIdSetted'] : null;

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
                            <a class="nav-link btn btn-outline-primary text-dark" data-bs-toggle="modal" data-bs-target="#updateSessionModal">
                                <i class="bi bi-file-earmark-ruled"></i> Update Session
                            </a>
                        </li>
                        
                    ';
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
                        <table id="uploadGradeTable" class="table table-hover table-striped display nowrap w-100" style="width:100%">
                            <thead class="border">
                                <tr>
                                    <th rowspan="3" class='border'>#</th>
                                    <th rowspan="3" class='border'>Student Name</th>
                                    <?php
                                        for ($i = 0; $i < count($componentNames); $i++) {
                                            $colspan = ($i === 2 || $i === 3) ? 3 : 8;
                                            echo "<th colspan='{$colspan}' class='border border-start-0'>" . $componentNames[$i] . "</th>";
                                        }
                                    ?>
                                    <th rowspan="2" class='border border-start-0'>Grade</th>
                                    <th rowspan="2" class='border border-start-0'>Percent</th>
                                    <th rowspan="2" class='border border-start-0'>Remark</th>
                                </tr>
                                <tr>
                                    <?php
                                        $index = 0;
                                        foreach ($componentDataName as $componentId => $componentValueNames) {
                                            foreach ($componentValueNames as $value) {
                                                echo "<th class=''>$value</th>";
                                            }

                                            if($index != 2 && $index != 3){
                                                echo "<th class=''>Total</th>";
                                            }
                                            echo "<th class=''>Grade</th>";
                                            echo "<th class='border-end'>{$componentPercentages[$index]}%</th>";

                                            $index++;
                                        }
                                    ?>
                                </tr>
                                <tr>
                                    <?php
                                        $indexValue = 0;
                                        $totalSum = 0;
                                        $OverAllSum = 0;
                                        $noneZero = 0;
                                        foreach ($componentDataValue as $componentId => $componentValueValues) {
                                            foreach ($componentValueValues as $value) {
                                                echo "<th class=''>$value</th>";
                                                $totalSum += $value;
                                                if($value != 0){
                                                    $noneZero++;
                                                }
                                            }
                                            if($indexValue != 2 && $indexValue != 3){
                                                echo "<th class=''>". $totalSum ."</th>";
                                            }
                                            
                                            $multiplier = 100 - $GradingSessionBase;

                                            $calculatedValue = ($totalSum == 0) ? $GradingSessionBase : ($totalSum / $totalSum) * $multiplier + $GradingSessionBase;
                                            echo "<th class=''>". number_format($calculatedValue, 2, '.', '') ."</th>";
                                            // echo "<th class=''>{$ComponentTotalArray[$indexValue]}</th>";
                                            $OverAllSum += $componentPercentages[$indexValue];
                                            echo "<th class='border-end'>{$componentPercentages[$indexValue]}</th>";

                                            $indexValue++;
                                            $totalSum = 0;
                                        }
                                        echo "<th class=''>".number_format($OverAllSum, 2, '.', '')."</th>";
                                        echo "<th class=''>{$GradingSessionPercentage}%</th>";
                                        echo "<th class='border-end'>Passed</th>";
                                    ?>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

<!-- Add Student to Course Modal -->
<div class="modal fade" id="addStudentCourseModal" tabindex="-1" aria-labelledby="addStudentCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="addStudentCourseModalLabel">Add Student</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addStudentFirstName" class="form-label">Student First Name</label>
                    <input type="text" class="form-control" name="addStudentFirstName" id="addStudentFirstName" placeholder="First Name">
                </div>
                <div class="mb-3">
                    <label for="addStudentMiddleName" class="form-label">Student Middle Name</label>
                    <input type="text" class="form-control" name="addStudentMiddleName" id="addStudentMiddleName" placeholder="Middle Name">
                </div>
                <div class="mb-3">
                    <label for="addStudentLastName" class="form-label">Student Last Name</label>
                    <input type="text" class="form-control" name="addStudentLastName" id="addStudentLastName" placeholder="Last Name">
                </div>
                <div class="mb-3">
                    <label for="addStudentNumber" class="form-label">Student Number</label>
                    <input type="text" class="form-control" name="addStudentNumber" id="addStudentNumber" placeholder="Student Number">
                </div>
                <div class="mb-3">
                    <label for="addStudentStatus" class="form-label">Student Status</label>
                    <select name="addStudentStatus" id="addStudentStatus" class="form-select">
                        <option value="Regular">Regular Student</option>
                        <option value="Irregular">Irregular Student</option>
                    </select>
                </div>
                <input type="hidden" name="addCourseSubjectId" id="addCourseSubjectId" value="<?php echo $CourseSubjectIdSetted ?>">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="addStudentButton">Add</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- Add Course Subject Modal -->
<div class="modal fade" id="addSessionModal" tabindex="-1" aria-labelledby="addSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="addSessionModalLabel">Add Session</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addSessionName" class="form-label">Session Name</label>
                    <input type="text" class="form-control" name="addSessionName" id="addSessionName" placeholder="Example: Prelim">
                </div>
                <div class="mb-3">
                    <label for="addSessionPercent" class="form-label">Session Percentage</label>
                    <input type="text" class="form-control" name="addSessionPercent" id="addSessionPercent" placeholder="Example: 30">
                </div>
                <input type="hidden" name="addCourseSubjectId" id="addCourseSubjectId" value="<?php echo $CourseSubjectIdSetted ?>">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="addSessionButton">Add</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- Update Course Subject Modal -->
<div class="modal fade" id="updateSessionModal" tabindex="-1" aria-labelledby="updateSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="updateSessionModalLabel">Update Session</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addSessionName" class="form-label">Session Name</label>
                    <input type="text" class="form-control" name="addSessionName" id="addSessionName" placeholder="Example: Prelim">
                </div>
                <div class="mb-3">
                    <label for="addSessionPercent" class="form-label">Session Percentage</label>
                    <input type="text" class="form-control" name="addSessionPercent" id="addSessionPercent" placeholder="Example: 30">
                </div>
                <input type="hidden" name="addCourseSubjectId" id="addCourseSubjectId" value="<?php echo $CourseSubjectIdSetted ?>">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="addSessionButton">Add</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- Bootstrap -->
<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- Sweet Alert 2 -->
<script src="./node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
<!-- DataTables -->
<script src="./node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="./node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="./node_modules/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="./JS/StudentAdd.js"></script>

<!-- UnComment If Needed Manual Adding Session -->
<!-- <script src="./JS/SessionAdd.js"></script> -->

<script>
$(document).ready(function () {
    $('.nav-link.gradingSession').on('click', function (e) {
        e.preventDefault();

        // Get the section ID from the clicked tab
        var sessionId = $(this).data('grading-session-id');
        var courseSubjectId = $(this).data('course-subject-id');

        // Make an AJAX request to update the session value
        $.ajax({
            type: 'POST',
            url: './ajaxRequest/SessionSet.php', // Specify the path to your server-side script
            data: { courseSubjectId: courseSubjectId, sessionId: sessionId  },
            success: function (response) {
                location.reload();
            },
            error: function (xhr, status, error) {
                // Handle errors if any
                console.error(error);
            }
        });
    });
});
const excludedColumns = [0, 1, 7, 8, 9, 15, 16, 17, 19, 20, 22, 23];

const createdCell = function(cell, cellData, rowData) {
    let original;
    const columnIndex = cell.cellIndex;
    let IndexColumn;
    
    if(columnIndex <= 6){
        IndexColumn = columnIndex - 2;
    }else if(columnIndex <= 14 && columnIndex >= 10){
        IndexColumn = columnIndex - 5;
    }else if (columnIndex == 18){
        IndexColumn = columnIndex - 8;
    }else if (columnIndex == 21){
        IndexColumn = columnIndex - 10;
    }


    // Exclude columns based on the excludedColumns array
    if (excludedColumns.includes(columnIndex)) {
        return;
    }

    cell.setAttribute('contenteditable', true);
    cell.setAttribute('spellcheck', false);
    
    cell.addEventListener("focus", function(e) {
        original = e.target.textContent;
    });

    cell.addEventListener("input", function(e) {
        let enteredText = e.target.textContent;

        // Remove non-numeric characters
        enteredText = enteredText.replace(/\D/g, '');

        // Update the cell content with the cleaned integer value
        e.target.textContent = enteredText;
    });

    cell.addEventListener("blur", handleCellEdit);
    cell.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            handleCellEdit(e);
        }
    });

    function updateStudentGrade(componentValueId, studentId, editedText) {
        $.ajax({
            url: './ajaxRequest/StudentGradeSet.php',
            method: 'POST',
            data: {
                componentValueId: componentValueId,
                studentId: studentId,
                editedText: editedText,
            },
            success: function (response) {
                const data = JSON.parse(response);
                if (data.status === 'success') {
                    table.ajax.url("./tables/StudentGradeFetch.php?UserAccountId=<?php echo $UserAccountId; ?>&currentSubjectId=<?php echo $CourseSubjectIdSetted ?>&currentSelectedSessionId=" + <?php echo $GradingSessionIdSetted ?>).load();
                } else {
                    console.log("Unsuccess");
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }

    // Use the function in your handleCellEdit function
    function handleCellEdit(e) {
        const row = table.row(e.target.parentElement);
        const gradesArray = JSON.parse(rowData[2]);
        const componentValueId = gradesArray[IndexColumn].component_value_id;
        const studentId = gradesArray[IndexColumn].student_id;
        const componentValue = gradesArray[IndexColumn].component_value;
        const editedText = e.target.textContent;

        if (componentValue >= editedText) {
            updateStudentGrade(componentValueId, studentId, editedText);
        } else {
            e.target.classList.add('error-cell');
        }
    }

};
<?php
// Assuming $ComponentTotalArray is defined in your PHP code
echo "var componentTotalArray = " . json_encode($ComponentTotalArray) . ";\n";
?>
var table = $('#uploadGradeTable').DataTable({
    "processing": true,
    "serverSide": true,
    "scrollX": true,
    "ajax": {
        "url": "./tables/StudentGradeFetch.php?UserAccountId=<?php echo $UserAccountId; ?>&currentSubjectId=<?php echo $CourseSubjectIdSetted ?>&currentSelectedSessionId=" + <?php echo $GradingSessionIdSetted ?>,
        "type": "GET"
    },
    "columnDefs": [
        {
            targets: '_all',
            createdCell: createdCell
        },
        {
            "targets": 0,
            "data": null,
            "render": function (data, type, row, meta) {
                return meta.row + 1;
            }
        },
        {
            "targets": 1,
            "data": 1,
        },
        {
            "targets": 2,
            "data": 2,  
            orderable: false,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                return '<td>' + (parseInt(grades[0].student_grade) || 0) + '</td>';
            }
        },
        {
            "targets": 3,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                return '<td>' + (parseInt(grades[1].student_grade) || 0) + '</td>';
            }
        },
        {
            "targets": 4,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                return '<td>' + (parseInt(grades[2].student_grade) || 0) + '</td>';
            }
        },
        {
            "targets": 5,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                return '<td>' + (parseInt(grades[3].student_grade) || 0) + '</td>';
            }
        },
        {
            "targets": 6,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                return '<td>' + (parseInt(grades[4].student_grade) || 0) + '</td>';
            }
        },
        {
            "targets": 7,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                var sum = 0;

                // Sum the values from targets 2 to 6
                for (var i = 2; i <= 6; i++) {
                    sum += parseInt(grades[i - 2].student_grade) || 0;
                }

                var cell = table.cell(meta.row, 7).node();
                $(cell).css({
                    'border-left': '1px solid #dee2e6',
                    'border-right': '1px solid #dee2e6',
                    'font-weight': '700'
                });

                return '<td>' + sum + '</td>';
            }
        },
        // Computation of Grade
        {
            "targets": 8,
            "data": 2,
            "render": function (data, type, row, meta) {
                var formatNumber = parseFloat(calculateFormattedValue(data, 2, 6, 0));

                var cell = table.cell(meta.row, 8).node();
                $(cell).css({
                    'font-weight': '700'
                });

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        // Computation of Grade Percentage
        {
            "targets": 9,
            "data": 2,
            "render": function (data, type, row, meta) {
                var formatNumber = parseFloat(calculateFormattedValue(data, 2, 6, 0)) * (<?php echo $componentPercentages[0] ?> / 100);

                var cell = table.cell(meta.row, 9).node();
                $(cell).css({
                    'border-left': '1px solid #dee2e6',
                    'border-right': '1px solid #dee2e6',
                    'font-weight': '700'
                });

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        {
            "targets": 10,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                return '<td>' + (parseInt(grades[5].student_grade) || 0) + '</td>';
            }
        },
        {
            "targets": 11,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                return '<td>' + (parseInt(grades[6].student_grade) || 0) + '</td>';
            }
        },
        {
            "targets": 12,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                return '<td>' + (parseInt(grades[7].student_grade) || 0) + '</td>';
            }
        },
        {
            "targets": 13,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                return '<td>' + (parseInt(grades[8].student_grade) || 0) + '</td>';
            }
        },
        {
            "targets": 14,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                return '<td>' + (parseInt(grades[9].student_grade) || 0) + '</td>';
            },
            ordering: true,
        },
        {
            "targets": 15,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                var sum = 0;

                // Sum the values from targets 8 to 12
                for (var i = 7; i <= 11; i++) {
                    sum += parseInt(grades[i - 2].student_grade) || 0;
                }

                var cell = table.cell(meta.row, 15).node();
                $(cell).css({
                    'border-left': '1px solid #dee2e6',
                    'border-right': '1px solid #dee2e6',
                    'font-weight': '700'
                });

                return '<td>' + sum + '</td>';
            }
        },
        // Computation of Grade
        {
            "targets": 16,
            "data": 2,
            "render": function (data, type, row, meta) {
                var formatNumber = parseFloat(calculateFormattedValue(data, 7, 11, 1));

                var cell = table.cell(meta.row, 16).node();
                $(cell).css({
                    'font-weight': '700'
                });

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        // Computation of Grade Percentage
        {
            "targets": 17,
            "data": 2,
            "render": function (data, type, row, meta) {
                var formatNumber = parseFloat(calculateFormattedValue(data, 7, 11, 1)) * (<?php echo $componentPercentages[1] ?> / 100);

                var cell = table.cell(meta.row, 17).node();
                $(cell).css({
                    'border-left': '1px solid #dee2e6',
                    'border-right': '1px solid #dee2e6',
                    'font-weight': '700'
                });

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        {
            "targets": 18,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                return '<td>' + (parseInt(grades[10].student_grade) || 0) + '</td>';
            }
        },
        // Computation of Grade
        {
            "targets": 19,
            "data": 2,
            "render": function (data, type, row, meta) {
                var formatNumber = parseFloat(calculateFormattedValue(data, 12, 12, 2));

                var cell = table.cell(meta.row, 19).node();
                $(cell).css({
                    'border-left': '1px solid #dee2e6',
                    'font-weight': '700'
                });

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        // Computation of Grade Percentage
        {
            "targets": 20,
            "data": 2,
            "render": function (data, type, row, meta) {
                var formatNumber = parseFloat(calculateFormattedValue(data, 12, 12, 2)) * (<?php echo $componentPercentages[2] ?> / 100);

                var cell = table.cell(meta.row, 20).node();
                $(cell).css({
                    'border-left': '1px solid #dee2e6',
                    'border-right': '1px solid #dee2e6',
                    'font-weight': '700'
                });

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        {
            "targets": 21,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                return '<td>' + (parseInt(grades[11].student_grade) || 0) + '</td>';
            }
        },
        // Computation of Grade
        {
            "targets": 22,
            "data": 2,
            "render": function (data, type, row, meta) {
                var formatNumber = parseFloat(calculateFormattedValue(data, 13, 13, 3));

                var cell = table.cell(meta.row, 22).node();
                $(cell).css({
                    'border-left': '1px solid #dee2e6',
                    'font-weight': '700'
                });

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        // Computation of Grade Percentage
        {
            "targets": 23,
            "data": 2,
            "render": function (data, type, row, meta) {
                var formatNumber = parseFloat(calculateFormattedValue(data, 13, 13, 3)) * (<?php echo $componentPercentages[3] ?> / 100);

                var cell = table.cell(meta.row, 23).node();
                $(cell).css({
                    'border-left': '1px solid #dee2e6',
                    'border-right': '1px solid #dee2e6',
                    'font-weight': '700'
                });

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        {
            "targets": 24,
            "data": 2,
            "render": function (data, type, row, meta) {
                var SessionAverage = 0;
                var FirstComponent = parseFloat(calculateFormattedValue(data, 2, 6, 0)) * (<?php echo $componentPercentages[0] ?> / 100); // 1st
                var SecondComponent = parseFloat(calculateFormattedValue(data, 7, 11, 1)) * (<?php echo $componentPercentages[1] ?> / 100); // 2nd
                var ThirdComponent = parseFloat(calculateFormattedValue(data, 12, 12, 2)) * (<?php echo $componentPercentages[2] ?> / 100); // 3rd
                var FourthComponent = parseFloat(calculateFormattedValue(data, 13, 13, 3)) * (<?php echo $componentPercentages[3] ?> / 100); // 4th

                SessionAverage = FirstComponent + SecondComponent + ThirdComponent + FourthComponent;
                
                var formatNumber = parseFloat(SessionAverage);

                var cell = table.cell(meta.row, 24).node();
                $(cell).css({
                    'font-weight': '700'
                });

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        {
            "targets": 25,
            "data": 2,
            "render": function (data, type, row, meta) {
                var SessionAverage = 0;
                var FirstComponent = (parseFloat(calculateFormattedValue(data, 2, 6, 0)) * (<?php echo $componentPercentages[0] ?> / 100)); // 1st
                var SecondComponent = parseFloat(calculateFormattedValue(data, 7, 11, 1)) * (<?php echo $componentPercentages[1] ?> / 100); // 2nd
                var ThirdComponent = parseFloat(calculateFormattedValue(data, 12, 12, 2)) * (<?php echo $componentPercentages[2] ?> / 100); // 3rd
                var FourthComponent = parseFloat(calculateFormattedValue(data, 13, 13, 3)) * (<?php echo $componentPercentages[3] ?> / 100); // 4th

                SessionAverage = (FirstComponent + SecondComponent + ThirdComponent + FourthComponent) * (<?php echo $GradingSessionPercentage ?> / 100);
                
                var formatNumber = parseFloat(SessionAverage);

                var cell = table.cell(meta.row, 25).node();
                $(cell).css({
                    'border-left': '1px solid #dee2e6',
                    'border-right': '1px solid #dee2e6',
                    'font-weight': '700'
                });

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        {
            "targets": 26,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                var noneZeroValue = 0;
                var SessionAverage = 0;
                var FirstComponent = parseFloat(calculateFormattedValue(data, 2, 6, 0)) * (<?php echo $componentPercentages[0] ?> / 100); // 1st
                var SecondComponent = parseFloat(calculateFormattedValue(data, 7, 11, 1)) * (<?php echo $componentPercentages[1] ?> / 100); // 2nd
                var ThirdComponent = parseFloat(calculateFormattedValue(data, 12, 12, 2)) * (<?php echo $componentPercentages[2] ?> / 100); // 3rd
                var FourthComponent = parseFloat(calculateFormattedValue(data, 13, 13, 3)) * (<?php echo $componentPercentages[3] ?> / 100); // 4th

                SessionAverage = FirstComponent + SecondComponent + ThirdComponent + FourthComponent;

                for (var i = 0; i <= 11; i++) {
                    if(grades[i].student_grade != 0){
                        noneZeroValue++;
                    }
                }

                var Remarks;
                var bgColor;
                if (noneZeroValue != <?php echo $noneZero ?> || <?php echo $noneZero ?> == 0) {
                    Remarks = "<b>INC</b>";
                    bgColor = '#ffff00';
                } else {
                    if (SessionAverage >= 75) {
                        Remarks = "<b class='text-white'>Passed</b>";
                        bgColor = '#008000';
                    } else {
                        Remarks = "<b class='text-white'>Failed</b>";
                        bgColor = '#ff0000';
                    }
                }
                var cell = table.cell(meta.row, 26).node();
                $(cell).css('background-color', bgColor);
                
                return '<td>' + Remarks + '</td>';
            }
        },

    ],
    "columns": [
        { "data": 0 },
        { "data": 1 },
        { "data": 2 },
        // Repeat for the remaining grades
    ]
});



function calculateFormattedValue(data, start, end, indexs) {
    var grades = JSON.parse(data);
    var sum = 0;

    // Sum the values from targets 2 to 6
    for (var i = start; i <= end; i++) {
        // Ensure grades[i - 2] exists before accessing student_grade
        var gradeValue = grades[i - 2]?.student_grade || 0;
        sum += parseInt(gradeValue);
    }

    // Get the base grade and subtract to get the multiplier
    var multiplier = 100 - <?php echo $GradingSessionBase ?>;

    // Access the component total using the JavaScript variable
    var componentTotal = componentTotalArray[indexs];

    // Formula to get student grade with base grade
    var calculatedValue = (sum === 0) ? <?php echo $GradingSessionBase ?> : (sum / componentTotal) * multiplier + <?php echo $GradingSessionBase ?>;    

    // Format the result to two decimal places
    return (calculatedValue == 0) ? '00.00' : calculatedValue.toFixed(2);
}
</script>
</body>
</html>
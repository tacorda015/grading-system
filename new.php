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
    $ComponentValueNameQuery = "SELECT component_value, component_id, component_value_id FROM component_value_table WHERE component_id = '$componentId'";
    $ComponentValueResult = $con->query($ComponentValueNameQuery);

    $componentTotals = array();
    while ($componentValueRow = $ComponentValueResult->fetch_assoc()) {
        $componentId = $componentValueRow['component_id'];
        $componentValue = $componentValueRow['component_value'];
        $componentValueId = $componentValueRow['component_value_id'];
    
        // Store component values in the array
        $componentDataValue[$componentId][] = array(
            'component_value_id' => $componentValueId,
            'component_value' => $componentValue
        );
    
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
                            <a class="nav-link" id="updateBtn" btn btn-outline-primary text-dark" data-bs-toggle="modal" data-bs-target="#updateSessionModal">
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
                                            echo "<th colspan='{$colspan}' id='updateComponent' class='border border-start-0 updateComponent' data-bs-toggle='modal' data-bs-target='#updateComponentModal' data-grading-session-id='{$GradingSessionIdSetted}'>" 
                                                . $componentNames[$i] .
                                                
                                                "</th>";
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
                                                $uniqueId = "value_{$value['component_value_id']}_{$componentId}";
                                                // Adding contenteditable attribute
                                                echo "<th class='componentValueInput' id='{$uniqueId}' contenteditable='true' data-component-value-id='{$value['component_value_id']}' data-original-value='{$value['component_value']}' data-component-id='{$componentId}'>{$value['component_value']}</th>";
                                                $totalSum += $value['component_value'];
                                                if ($value['component_value'] != 0) {
                                                    $noneZero++;
                                                }
                                            }                                            
                                            if ($indexValue != 2 && $indexValue != 3) {
                                                echo "<th class=''>" . $totalSum . "</th>";
                                            }
                                        
                                            $multiplier = 100 - $GradingSessionBase;
                                        
                                            $calculatedValue = ($totalSum == 0) ? $GradingSessionBase : ($totalSum / $totalSum) * $multiplier + $GradingSessionBase;
                                            echo "<th class=''>" . number_format($calculatedValue, 2, '.', '') . "</th>";
                                        
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

    <?php 
        require "./Modals/upload_grade_addStudent.php"; // For Add Student to Course/Subject
        require "./Modals/upload_grade_addSession.php"; // For Add Manual Session (Currently But Display)
        require "./Modals/upload_grade_updateSession.php"; // For Update Session 
        require "./Modals/upload_grade_updateComponent.php"; // For Update Session 
    ?>
<script>
<?php
// Assuming $ComponentTotalArray is defined in your PHP code
echo "var componentTotalArray = " . json_encode($ComponentTotalArray) . ";";
echo "var UserAccountId = " . json_encode($UserAccountId) . ";";
echo "var componentPercentages = " . json_encode($componentPercentages) . ";";

// This is Save at Session
echo "var CourseSubjectIdSetted = " . json_encode($CourseSubjectIdSetted) . ";";
echo "var GradingSessionIdSetted = " . json_encode($GradingSessionIdSetted) . ";";
?>
</script>
<!-- Bootstrap -->
<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- Sweet Alert 2 -->
<script src="./node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
<!-- DataTables -->
<script src="./node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="./node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="./node_modules/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="./JS/StudentAdd.js"></script>
<script src="./JS/GradingSessionEdit.js"></script>
<script src="./JS/GradingComponentEdit.js"></script>
<script src="./JS/SessionTabs.js"></script>

<!-- UnComment If Needed Manual Adding Session -->
<!-- <script src="./JS/SessionAdd.js"></script> -->

<script>

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
                    table.ajax.url("./tables/StudentGradeFetch.php?UserAccountId=" + UserAccountId + "&currentSubjectId="+ CourseSubjectIdSetted + "&currentSelectedSessionId=" + GradingSessionIdSetted).load();
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

var table = $('#uploadGradeTable').DataTable({
    "processing": true,
    "serverSide": true,
    "scrollX": true,
    "ajax": {
        "url": "./tables/StudentGradeFetch.php?UserAccountId=" + UserAccountId + "&currentSubjectId=" + CourseSubjectIdSetted + "&currentSelectedSessionId=" + GradingSessionIdSetted ,
        "type": "GET"
    },
    "columnDefs": [

        { targets: '_all', createdCell: createdCell },
        { "targets": 0, "data": null, "render": (data, type, row, meta) => meta.row + 1 },
        { "targets": 1, "data": 1 },
        { "targets": [2, 3, 4, 5, 6], "data": 2, orderable: false, "render": function(data, type, row, meta) {return renderGrade(data, type, row, meta, 2)}},
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

                applyCellStylingWithBothSideBorders(meta.row, 7);

                return '<td>' + sum + '</td>';
            }
        },
        // Computation of Grade
        {   
            "targets": 8, 
            "data": 2, 
            "render": function (data, type, row, meta) {
                var formatNumber = parseFloat(calculateFormattedValue(data, 2, 6, 0));

                applyCellStyling(meta.row, 8);

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        // Computation of Grade Percentage
        {
            "targets": 9,
            "data": 2,
            "render": function (data, type, row, meta) {
                var formatNumber = parseFloat(calculateFormattedValue(data, 2, 6, 0)) * (componentPercentages[0] / 100);

                applyCellStylingWithBothSideBorders(meta.row, 9);

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        { "targets": [10, 11, 12, 13, 14], "data": 2, orderable: false, "render": function(data, type, row, meta) {return renderGrade(data, type, row, meta, 5)}},
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

                applyCellStylingWithBothSideBorders(meta.row, 15);

                return '<td>' + sum + '</td>';
            },
            // className: '',
        },
        // Computation of Grade
        {
            "targets": 16,
            "data": 2,
            "render": function (data, type, row, meta) {
                var formatNumber = parseFloat(calculateFormattedValue(data, 7, 11, 1));

                applyCellStyling(meta.row, 16);

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        // Computation of Grade Percentage
        {
            "targets": 17,
            "data": 2,
            "render": function (data, type, row, meta) {
                var formatNumber = parseFloat(calculateFormattedValue(data, 7, 11, 1)) * (componentPercentages[1] / 100);

                applyCellStylingWithBothSideBorders(meta.row, 17);

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

                applyCellStylingWithLeftBorders(meta.row, 19);

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        // Computation of Grade Percentage
        {
            "targets": 20,
            "data": 2,
            "render": function (data, type, row, meta) {
                var formatNumber = parseFloat(calculateFormattedValue(data, 12, 12, 2)) * (componentPercentages[2] / 100);

                applyCellStylingWithBothSideBorders(meta.row, 20);

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

                applyCellStylingWithLeftBorders(meta.row, 22);

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        // Computation of Grade Percentage
        {
            "targets": 23,
            "data": 2,
            "render": function (data, type, row, meta) {
                var formatNumber = parseFloat(calculateFormattedValue(data, 13, 13, 3)) * (componentPercentages[3] / 100);

                applyCellStylingWithBothSideBorders(meta.row, 23);

                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        {
            "targets": 24,
            "data": 2,
            "render": function (data, type, row, meta) {
                var formatNumber = calculateSessionAverage(data);
                applyCellStyling(meta.row, 24);
                return '<td>' + (isNaN(formatNumber) ? '00.00' : formatNumber.toFixed(2)) + '</td>';
            }
        },
        {
            "targets": 25,
            "data": 2,
            "render": function (data, type, row, meta) {
                var SessionAverage = 0;
                var formatNumber = calculateSessionAverage(data);

                SessionAverage = parseFloat(formatNumber * (<?php echo $GradingSessionPercentage ?> / 100));

                applyCellStylingWithBothSideBorders(meta.row, 25);

                return '<td>' + (isNaN(SessionAverage) ? '00.00' : SessionAverage.toFixed(2)) + '</td>';
            }
        },
        {
            "targets": 26,
            "data": 2,
            "render": function (data, type, row, meta) {
                var grades = JSON.parse(data);
                var noneZeroValue = 0;
                var SessionAverage = calculateSessionAverage(data);

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

function renderGrade(data, type, row, meta, GradeIndex) {
    var grades = JSON.parse(data);
    var loopIndex = meta.col - GradeIndex;
    return '<td>' + (grades[loopIndex]?.student_grade || 0) + '</td>';
}

function calculateSessionAverage(data) {
    var grades = JSON.parse(data);
    
    var FirstComponent = calculateComponentValue(grades, 2, 6, 0) * (componentPercentages[0] / 100);
    var SecondComponent = calculateComponentValue(grades, 7, 11, 1) * (componentPercentages[1] / 100);
    var ThirdComponent = calculateComponentValue(grades, 12, 12, 2) * (componentPercentages[2] / 100);
    var FourthComponent = calculateComponentValue(grades, 13, 13, 3) * (componentPercentages[3] / 100);

    return FirstComponent + SecondComponent + ThirdComponent + FourthComponent;
}

function calculateComponentValue(grades, start, end, index) {
    var sum = 0;

    for (var i = start; i <= end; i++) {
        var gradeValue = grades[i - 2]?.student_grade || 0;
        sum += parseInt(gradeValue);
    }

    var multiplier = 100 - <?php echo $GradingSessionBase ?>;
    var componentTotal = componentTotalArray[index];
    
    return (sum === 0) ? <?php echo $GradingSessionBase ?> : (sum / componentTotal) * multiplier + <?php echo $GradingSessionBase ?>;
}

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

// Table CSS
function applyCellStyling(row, col) {
    var cell = table.cell(row, col).node();
    $(cell).css({
        'font-weight': '700'
    });
}

function applyCellStylingWithLeftBorders(row, col) {
    var cell = table.cell(row, col).node();
    $(cell).css({
        'border-left': '1px solid #b0b0b0',
        'font-weight': '700'
    });
}

function applyCellStylingWithBothSideBorders(row, col) {
    var cell = table.cell(row, col).node();
    $(cell).css({
        'border-left': '1px solid #b0b0b0',
        'border-right': '1px solid #b0b0b0',
        'font-weight': '700'
    });
}


$(document).ready(function () {

$('.componentValueInput').on('input', function () {
    // Get the input value and remove non-numeric characters
    var inputValue = $(this).val();
    var sanitizedValue = inputValue.replace(/\D/g, '');

    // Update the input with the sanitized value
    $(this).val(sanitizedValue);
});


$('.componentValueInput').on('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Prevent the default behavior of Enter key
        handleEnterKeyOrBlur($(this));
    }
});

$('.componentValueInput').on('blur', function () {
    handleEnterKeyOrBlur($(this));
});

function handleEnterKeyOrBlur(element) {
    // Get the current value of the input
    var currentValue = element.text();

    // Get the original value stored as a data attribute
    var originalValue = element.data('original-value');

    // Check if the value has changed
    if (currentValue != originalValue) {
        // Trigger an AJAX request only if there are changes
        var component_value_id = element.data('component-value-id');
        var componentId = element.data('component-id');
        var value = currentValue;

        $.ajax({
            type: 'POST',
            url: './ajaxRequest/GradingComponentValueUpdate.php',
            data: {
                component_value_id: component_value_id,
                componentId: componentId,
                value: value
            },
            success: function (response) {
                if (response === "success") {
                    // If the update is successful, reload the page
                    window.location.reload();
                } else {
                    // If there's an error, display a SweetAlert with the error message
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: response, // Display the response from the server
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        animation: true,
                        customClass: {
                            timerProgressBar: 'customProgressBar',
                        },
                    });
                }
            },
            error: function (xhr, status, error) {
                // Handle AJAX errors here
                console.error('AJAX Error:', error);

                // Display a SweetAlert error message for AJAX failure
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'AJAX request failed',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    animation: true,
                    customClass: {
                        timerProgressBar: 'customeProgressBar',
                    },
                });
            }
        });
    }
}
});


</script>
</body>
</html>
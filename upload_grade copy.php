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

if($_SERVER['REQUEST_METHOD'] == "GET" && !isset($_GET['course_subject_name']) && !isset($_GET['course_subject_id'])){
    header("Location: home.php");
    exit();
}else{
    $currentSubjectName = $_GET['course_subject_name'];
    $currentSubjectId = $_GET['course_subject_id'];

    // Get Data to grading_session_table
    $GradingSessionQuery = "SELECT * FROM grading_session_table WHERE course_subject_id = '$currentSubjectId'"; 
    $GradingSessionResult = $con->query($GradingSessionQuery)->fetch_assoc();
    $GradingSessionBase = $GradingSessionResult['grading_session_base'];
    $GradingSessionId = $GradingSessionResult['grading_session_id'];

    // Get Data to component_table
    $ComponentTableQuery = "SELECT * FROM component_table WHERE grading_session_id = '$GradingSessionId'";
    $ComponentTableResult = $con->query($ComponentTableQuery);
    while ($ComponentRow = $ComponentTableResult->fetch_assoc()) {
        $componentId = $ComponentRow['component_id'];
        $componentPercentages[] = $ComponentRow['component_percentage'];
        $componentNames[] = $ComponentRow['component_name'];
    
        // Get Data to component_value_table
        $ComponentValueNameQuery = "SELECT * FROM component_value_table WHERE component_id = '$componentId'";
        $ComponentValueNameResult = $con->query($ComponentValueNameQuery);
    
        while ($componentValueNameRow = $ComponentValueNameResult->fetch_assoc()) {
            $componentDataName[$componentId][] = $componentValueNameRow['component_value_name'];
        }

        // Get Data to component_value_table
        $ComponentValueNameQuery = "SELECT * FROM component_value_table WHERE component_id = '$componentId'";
        $ComponentValueResult = $con->query($ComponentValueNameQuery);

        while ($componentValueRow = $ComponentValueResult->fetch_assoc()) {
            $componentDataValue[$componentId][] = $componentValueRow['component_value'];
        }
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
                            
                            <div class="d-flex gap-2 flex-wrap flex-md-nowrap">
                                <input type="hidden" id="hiddenCurrentSubjectId" value="<?php echo $currentSubjectId  ?>">
                                <select name="gradingSession" id="gradingSession" class="form-select w-auto flex-grow-1
">
                                    <?php
                                        $getSession = "SELECT * FROM grading_session_table WHERE course_subject_id = '{$currentSubjectId}'";
                                        $getSessionResult = $con->query($getSession);
                                        if($getSessionResult->num_rows > 0){
                                            while($collectedData = $getSessionResult->fetch_assoc()){
                                                echo '<option value="'. $collectedData['grading_session_id'] .'">'. $collectedData['grading_session_name'] .'</option>';
                                            }
                                        }else{
                                            echo '<option value="">No Current Session</option>';
                                        }
                                    ?>
                                </select>
                                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addSessionModal">
                                <i class="bi bi-file-earmark-plus"></i> Session
                                </button>
                            </div>
                        </div>

                        <div class="px-md-3 px-2 d-flex align-items-center justify-content-center">
                            <h3 class="text-center m-0"><?php echo $currentSubjectName ?></h3>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="row">
                <div class="col-12 my-2">
                    <table id="uploadGradeTable" class="table table-hover table-striped display nowrap w-100" style="width:100%">
                        <thead>
                            <!-- <tr id="firstLayerComponentThead"></tr> -->
                            <tr>
                                <th rowspan="3">#</th>
                                <th rowspan="3">Student Name</th>
                                <?php
                                    for ($i = 0; $i < count($componentNames); $i++) {
                                        $colspan = ($i === 2 || $i === 3) ? 3 : 8;
                                        echo "<th colspan='{$colspan}' class='border'>" . $componentNames[$i] . "</th>";
                                    }
                                ?>
                            </tr>
                            <tr>
                                <?php
                                $index = 0;
                                foreach ($componentDataName as $componentId => $componentValueNames) {
                                    foreach ($componentValueNames as $value) {
                                        echo "<th>$value</th>";
                                    }

                                    if($index != 2 && $index != 3){
                                        echo "<th>Total</th>";
                                    }
                                    echo "<th>Grade</th>";
                                    echo "<th>{$componentPercentages[$index]}%</th>";

                                    $index++;
                                }
                                ?>
                            </tr>
                            <tr>
                                <?php
                                $indexValue = 0;
                                $totalSum = 0;
                                foreach ($componentDataValue as $componentId => $componentValueValues) {
                                    foreach ($componentValueValues as $value) {
                                        echo "<th>$value</th>";
                                        $totalSum += $value;
                                    }

                                    if($indexValue != 2 && $indexValue != 3){
                                        echo "<th>". $totalSum ."</th>";
                                    }
                                    
                                    $multiplier = 100 - $GradingSessionBase;

                                    $calculatedValue = ($totalSum == 0) ? $GradingSessionBase : ($totalSum / $totalSum) * $multiplier + $GradingSessionBase;
                                    echo "<th>". number_format($calculatedValue, 2, '.', '') ."</th>";
                                    echo "<th>{$componentPercentages[$indexValue]}</th>";

                                    $indexValue++;
                                    $totalSum = 0;
                                }
                                ?>
                            </tr>

                        </thead>
                    </table>
                </div>
            </div>
        </section>
    </div>

<!-- Add Course Subject Modal -->
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
                <input type="hidden" name="addCourseSubjectId" id="addCourseSubjectId" value="<?php echo $currentSubjectId ?>">

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
                <input type="hidden" name="addCourseSubjectId" id="addCourseSubjectId" value="<?php echo $currentSubjectId ?>">

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
    var currentSelectedSessionId = $('#gradingSession').val();

    const createdCell = function(cell, cellData, rowData) {
        let original;
        cell.setAttribute('contenteditable', true)
        cell.setAttribute('spellcheck', false)
        
        cell.addEventListener("focus", function(e) {
            original = e.target.textContent;
        });

        cell.addEventListener("blur", function(e) {
            if (original !== e.target.textContent) {
                const row = table.row(e.target.parentElement);
                const componentValueId = rowData[4]; // Assuming component_value_id is in the 5th column
                row.invalidate();
                // console.log('Row changed: ', row.data());
                console.log('Component Value ID: ', componentValueId);
            }
        });
    };

    var table = $('#uploadGradeTable').DataTable({
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            "ajax": {
                "url": "./tables/StudentGradeFetch.php?UserAccountId=<?php echo $UserAccountId; ?>&currentSubjectId=<?php echo $currentSubjectId ?>&currentSelectedSessionId=" + currentSelectedSessionId,
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
                        return '<td>' + (parseInt(grades[0]) || ' ') + '</td>';
                    }
                },
                {
                    "targets": 3,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var grades = JSON.parse(data);
                        return '<td>' + (parseInt(grades[1]) || ' ') + '</td>';
                    }
                },
                {
                    "targets": 4,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var grades = JSON.parse(data);
                        return '<td>' + (parseInt(grades[2]) || ' ') + '</td>';
                    }
                },
                {
                    "targets": 5,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var grades = JSON.parse(data);
                        return '<td>' + (parseInt(grades[3]) || ' ') + '</td>';
                    }
                },
                {
                    "targets": 6,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var grades = JSON.parse(data);
                        return '<td>' + (parseInt(grades[4]) || ' ') + '</td>';
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
                            sum += parseInt(grades[i - 2]) || ' ';
                        }

                        return '<td>' + sum + '</td>';
                    }
                },
                // Computation of Grade
                {
                    "targets": 8,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var formattedValue = calculateFormattedValue(data, 2, 6);
                        return '<td>' + formattedValue + '</td>';
                    }
                },
                // Computation of Grade Percentage
                {
                    "targets": 9,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var formattedValue = calculateFormattedValue(data, 2, 6);
                        var weightedComponentValue = formattedValue * (<?php echo $componentPercentages[0] ?> / 100);
                        return '<td>' + weightedComponentValue + '</td>';
                    }
                },
                {
                    "targets": 10,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var grades = JSON.parse(data);
                        return '<td>' + (parseInt(grades[5]) || ' ') + '</td>';
                    }
                },
                {
                    "targets": 11,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var grades = JSON.parse(data);
                        return '<td>' + (parseInt(grades[6]) || ' ') + '</td>';
                    }
                },
                {
                    "targets": 12,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var grades = JSON.parse(data);
                        return '<td>' + (parseInt(grades[7]) || ' ') + '</td>';
                    }
                },
                {
                    "targets": 13,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var grades = JSON.parse(data);
                        return '<td>' + (parseInt(grades[8]) || ' ') + '</td>';
                    }
                },
                {
                    "targets": 14,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var grades = JSON.parse(data);
                        return '<td>' + (parseInt(grades[9]) || ' ') + '</td>';
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
                            sum += parseInt(grades[i - 2]) || 0;
                        }

                        return '<td>' + sum + '</td>';
                    }
                },
                // Computation of Grade
                {
                    "targets": 16,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var formattedValue = calculateFormattedValue(data, 7, 11);
                        return '<td>' + formattedValue + '</td>';
                    }
                },
                // Computation of Grade Percentage
                {
                    "targets": 17,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var formattedValue = calculateFormattedValue(data, 7, 11);
                        var weightedComponentValue = formattedValue * (<?php echo $componentPercentages[1] ?> / 100);
                        return '<td>' + weightedComponentValue + '</td>';
                    }
                },
                {
                    "targets": 18,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var grades = JSON.parse(data);
                        return '<td>' + (parseInt(grades[10]) || ' ') + '</td>';
                    }
                },
                // Computation of Grade
                {
                    "targets": 19,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var formattedValue = calculateFormattedValue(data, 12, 12);
                        return '<td>' + formattedValue + '</td>';
                    }
                },
                // Computation of Grade Percentage
                {
                    "targets": 20,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var formattedValue = calculateFormattedValue(data, 12, 12);
                        var weightedComponentValue = formattedValue * (<?php echo $componentPercentages[2] ?> / 100);
                        return '<td>' + weightedComponentValue + '</td>';
                    }
                },
                {
                    "targets": 21,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var grades = JSON.parse(data);
                        return '<td>' + (parseInt(grades[11]) || ' ') + '</td>';
                    }
                },
                // Computation of Grade
                {
                    "targets": 22,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var formattedValue = calculateFormattedValue(data, 13, 13);
                        return '<td>' + formattedValue + '</td>';
                    }
                },
                // Computation of Grade Percentage
                {
                    "targets": 23,
                    "data": 2,
                    "render": function (data, type, row, meta) {
                        var formattedValue = calculateFormattedValue(data, 13, 13);
                        var weightedComponentValue = formattedValue * (<?php echo $componentPercentages[3] ?> / 100);
                        return '<td>' + weightedComponentValue + '</td>';
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

        // // Hightlight Row When Click
        // table.on('click', 'tbody tr', (e) => {
        //     let classList = e.currentTarget.classList;
        
        //     if (classList.contains('selected')) {
        //         classList.remove('selected');
        //     }
        //     else {
        //         table.rows('.selected').nodes().each((row) => row.classList.remove('selected'));
        //         classList.add('selected');
        //     }
        // });
        
        // $('#button').on('click', function () {
        //     table.row('.selected').remove().draw(false);
        // });


    function calculateFormattedValue(data, start, end) {
        var grades = JSON.parse(data);
        var sum = 0;

        // Sum the values from targets 2 to 6
        for (var i = start; i <= end; i++) {
            sum += parseInt(grades[i - 2]) || 0;
        }

        // Get the base grade and subtract to get the multiplier
        var multiplier = 100 - <?php echo $GradingSessionBase ?>;

        // Formula to get student grade with base grade
        var calculatedValue = (sum == 0) ? <?php echo $GradingSessionBase ?> : (sum / sum) * multiplier + <?php echo $GradingSessionBase ?>;
        return (calculatedValue == 0) ? '00.00' : calculatedValue.toFixed(2);
    }

    // Get the initial selected value when the page loads
    updateCurrentSession();

    // Add an event listener to the gradingSession dropdown
    $('#gradingSession').on('change', function () {
        // Update the content of the CurrentSession paragraph whenever the selection changes
        updateCurrentSession();
    });

    function updateCurrentSession() {
        var selectedSessionId = $('#gradingSession').val();
        var selectedSessionName = $('#gradingSession option:selected').text();
        
        // Check if a session is selected
        if (selectedSessionId) {
            $('.CurrentSession').text(selectedSessionName);
        } else {
            $('.CurrentSession').text("No Current Session");
        }
    }
});

</script>
</body>
</html>
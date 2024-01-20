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

$fullName = $getUserData['account_fName'] . ' ' . $getUserData['account_lName'];
$UserAccountId = $getUserData['account_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course/Subject</title>

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
        .courseSubjectLink{
            text-decoration: none;
            font-size: 19px;
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
                <div class="col-12 my-4">
                    <!-- Add Course/Subject Button -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseSubjectModal">
                    <i class="bi bi-folder-plus"></i> Course/Subject
                    </button>
                </div>
            </div>
        </section>
        <section>
            <div class="row">
                <div class="col-12 my-2">
                    <table id="courseSectionTable" class="table table-hover table-striped display nowrap w-100" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Course/Section</th>
                                <th>Subject Day</th>
                                <th>Subject Time</th>
                                <th>Subject Room</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
    </div>

<!-- Add Course Subject Modal -->
<div class="modal fade" id="addCourseSubjectModal" tabindex="-1" aria-labelledby="addCourseSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="addCourseSubjectModalLabel">Add Course/Subject</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addCourseName" class="form-label">Course Name</label>
                    <input type="text" class="form-control" name="addCourseName" id="addCourseName" placeholder="Course Name">
                </div>
                <div class="mb-3">
                    <label for="addSubjectName" class="form-label">Subject Name</label>
                    <input type="text" class="form-control" name="addSubjectName" id="addSubjectName" placeholder="Subject Name">
                </div>
                <input type="hidden" name="addSubjectTeacher" id="addSubjectTeacher" value="<?php echo $fullName ?>">
                <input type="hidden" name="addTeacherId" id="addTeacherId" value="<?php echo $UserAccountId ?>">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="addSyStart" class="form-label">School Year Start</label>
                            <select class="form-select" name="addSyStart" id="addSyStart" onchange="updateEndOptions()">
                                <?php
                                $currentYear = date("Y");
                                // Display current year and previous year
                                for ($year = $currentYear; $year >= $currentYear - 1; $year--) {
                                    echo "<option value=\"$year\">$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="addSyEnd" class="form-label">End</label>
                            <select class="form-select" name="addSyEnd" id="addSyEnd">
                                <!-- Options will be dynamically updated using JavaScript -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="addMeetingDay" class="form-label">Day Meeting</label>
                            <select class="form-select" name="addMeetingDay" id="addMeetingDay" class="form-select">
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="addSubjectRoom" class="form-label">Subject Room</label>
                            <input type="text" class="form-control" name="addSubjectRoom" id="addSubjectRoom" placeholder="Subject Room">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="addMeetingTimeStart" class="form-label">Time Start</label>
                            <input type="time" name="addMeetingTimeStart" id="addMeetingTimeStart" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="addMeetingTimeEnd" class="form-label">Time End</label>
                            <input type="time" name="addMeetingTimeEnd" id="addMeetingTimeEnd" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="addCourseSubject">Add</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="EditCourseSubjectModal" tabindex="-1" aria-labelledby="EditCourseSubjectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="EditCourseSubjectModalLabel">Edit Course/Subject Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post">
                <div class="mb-3">
                    <label for="editCourseName" class="form-label">Course Name</label>
                    <input type="text" class="form-control" name="editCourseName" id="editCourseName" placeholder="Course Name">
                </div>
                <div class="mb-3">
                    <label for="editSubjectName" class="form-label">Subject Name</label>
                    <input type="text" class="form-control" name="editSubjectName" id="editSubjectName" placeholder="Subject Name">
                </div>
                <input type="hidden" name="editSubjectTeacher" id="editSubjectTeacher" value="<?php echo $fullName ?>">
                <input type="hidden" name="editTeacherId" id="editTeacherId" value="<?php echo $UserAccountId ?>">
                <input type="hidden" name="editCourseSubjectId" id="editCourseSubjectId">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="editSyStart" class="form-label">School Year Start</label>
                            <select class="form-select" name="editSyStart" id="editSyStart" onchange="updateEndOptions()">
                                <?php
                                $currentYear = date("Y");
                                // Display current year and previous year
                                for ($year = $currentYear; $year >= $currentYear - 1; $year--) {
                                    echo "<option value=\"$year\">$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="editSyEnd" class="form-label">End</label>
                            <select class="form-select" name="editSyEnd" id="editSyEnd">
                                <!-- Options will be dynamically updated using JavaScript -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="editMeetingDay" class="form-label">Day Meeting</label>
                            <select class="form-select" name="editMeetingDay" id="editMeetingDay" class="form-select">
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="editSubjectRoom" class="form-label">Subject Room</label>
                            <input type="text" class="form-control" name="editSubjectRoom" id="editSubjectRoom" placeholder="Subject Room">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="editMeetingTimeStart" class="form-label">Time Start</label>
                            <input type="time" name="editMeetingTimeStart" id="editMeetingTimeStart" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="editMeetingTimeEnd" class="form-label">Time End</label>
                            <input type="time" name="editMeetingTimeEnd" id="editMeetingTimeEnd" class="form-control">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="saveChangesBtn">Save changes</button>
        </div>
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

<!-- For Tables Display -->
<script>
var table = $('#courseSectionTable').DataTable( { 
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "./tables/CourseSectionFetch.php?UserAccountId=<?php echo $UserAccountId; ?>",
        "type": "GET"
    },
    "columnDefs": [
        {
            "targets": 0, // index of the counter column
            "data": null,
            "render": function (data, type, row, meta) {
                return meta.row + 1; // meta.row is zero-based, so add 1 for the actual row number
            }
        },
        // {
        //     "targets": 1, // Assuming control number is in the second column (index 1)
        //     "render": function (data, type, row, meta) {
        //         return '<a href="./upload_grade.php?course_subject_id=' + row[0] + '&course_subject_name=' + encodeURIComponent(row[1]) + '" class="courseSubjectLink" data-course-subject-id="' + data + '">' + data + '</a>';
        //     }
        // },
        {
            "targets": 1,
            "render": function (data, type, row, meta) {
                return '<a href="#" class="courseSubjectLink" data-course-subject-id="' + row[0] + '">' + data + '</a>';
            }
        },
        {
            "targets": 3, // index of the column you want to customize
            "render": function (data, type, row, meta) {
                var course_subject_time_start = row[3]; // replace with the correct index
                var course_subject_time_end = row[5]; // replace with the correct index

                // Concatenate start and end times
                var concatenatedTimes = course_subject_time_start + " - " + course_subject_time_end;

                return concatenatedTimes;
            }
        },
        {
            "targets": -1,
            "data": null,
            "defaultContent": "<button class='btn btn-primary btn-xs tblEdit'>Edit </button> <button class='btn btn-danger btn-xs tblDelete'>Delete</button>" 
        }
    ]
});
// Handle click event on the link
$('#courseSectionTable').on('click', '.courseSubjectLink', function(e) {
        e.preventDefault();

        // Get the data-course-subject-id attribute
        var courseSubjectId = $(this).data('course-subject-id');

        // Make an AJAX request to set the session
        $.ajax({
            type: 'POST',
            url: './ajaxRequest/SessionSet.php', // Specify the path to your server-side script
            data: { courseSubjectId: courseSubjectId, sessionId: 'NULL' },
            success: function(response) {
                console.log(response);
                // Optionally, you can redirect to the upload_grade.php page after setting the session
                window.location.href = './new.php';
            },
            error: function(xhr, status, error) {
                // Handle errors if any
                console.error(error);
            }
        });
    });
</script>
<script src="./JS/CourseSubjectAdd.js"></script>
<script src="./JS/CourseSubjectEdit.js"></script>
<script src="./JS/CourseSubjectDelete.js"></script>
</body>
</html>
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentCourseModal">
                    <i class="bi bi-person-add"></i> Student
                    </button>
                </div>
            </div>
        </section>
        <section>
            <div class="d-flex justify-content-center">
                <h2 class="text-center">Student List</h2>
            </div>
        </section>
        <section>
            <div class="row">
                <div class="col-12 my-2">
                    <table id="studentListTable" class="table table-hover table-striped display nowrap w-100" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Student Number</th>
                                <th>Course/Subject</th>
                                <th>Gender</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
    </div>

<!-- Add Student Modal -->
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
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="addStudentStatus" class="form-label">Student Status</label>
                            <select name="addStudentStatus" id="addStudentStatus" class="form-select">
                                <option value="Regular">Regular Student</option>
                                <option value="Irregular">Irregular Student</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="addStudentGender" class="form-label">Student Gender</label>
                            <select name="addStudentGender" id="addStudentGender" class="form-select">
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="addCourseSubjectId" class="form-label">Course/Subject</label>
                    <select name="addCourseSubjectId" id="addCourseSubjectId" class="form-select">
                        <?php 
                        
                        $getCourseSubject = "SELECT * FROM course_subject_table WHERE teacher_id = '$UserAccountId'";
                        $getCourseSubjectResult = $con->query($getCourseSubject);
                        
                        if($getCourseSubjectResult->num_rows > 0){
                            while($row = $getCourseSubjectResult->fetch_assoc()){
                                echo '<option value="'. $row['course_subject_id'] .'">'. $row['course_subject_name'] .'</option>';
                            }
                        }else{
                            echo '<option value="">No Course/Section</option>';
                        }
                        
                        ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="addStudentButton">Add</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="EditStudentModal" tabindex="-1" aria-labelledby="EditStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="EditStudentModalLabel">Edit Student Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post">
                <div class="mb-3">
                    <label for="editStudentFirstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="editStudentFirstName" id="editStudentFirstName" placeholder="First Name">
                </div>
                <div class="mb-3">
                    <label for="editStudentMiddleName" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" name="editStudentMiddleName" id="editStudentMiddleName" placeholder="Middle Name">
                </div>
                <div class="mb-3">
                    <label for="editStudentLastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="editStudentLastName" id="editStudentLastName" placeholder="Last Name">
                </div>
                <div class="mb-3">
                    <label for="editStudentNumber" class="form-label">Student Number</label>
                    <input type="text" class="form-control" name="editStudentNumber" id="editStudentNumber" placeholder="Student Number">
                </div>
                <input type="hidden" name="editStudentId" id="editStudentId">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="editStudentStatus" class="form-label">Student Status</label>
                            <select name="editStudentStatus" id="editStudentStatus" class="form-select">
                                <option value="Regular">Regular Student</option>
                                <option value="Irregular">Irregular Student</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="editStudentGender" class="form-label">Student Gender</label>
                            <select name="editStudentGender" id="editStudentGender" class="form-select">
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="editCourseSubjectId" class="form-label">Course/Subject</label>
                    <select name="editCourseSubjectId" id="editCourseSubjectId" class="form-select">
                        <?php 
                        
                        $getCourseSubject = "SELECT * FROM course_subject_table WHERE teacher_id = '$UserAccountId'";
                        $getCourseSubjectResult = $con->query($getCourseSubject);
                        
                        if($getCourseSubjectResult->num_rows > 0){
                            while($row = $getCourseSubjectResult->fetch_assoc()){
                                echo '<option value="'. $row['course_subject_id'] .'">'. $row['course_subject_name'] .'</option>';
                            }
                        }else{
                            echo '<option value="">No Course/Section</option>';
                        }
                        
                        ?>
                    </select>
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
<script src="./JS/StudentAdd.js"></script>

<script>
var table = $('#studentListTable').DataTable( { 
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "./tables/StudentListFetch.php?UserAccountId=<?php echo $UserAccountId; ?>",
        "type": "GET",
    },
    "columnDefs": [
        {
            "targets": 0, // index of the counter column
            "data": null,
            "render": function (data, type, row, meta) {
                return meta.row + 1; // meta.row is zero-based, so add 1 for the actual row number
            }
        },
        {
            "targets": 4, // index of the gender column
            "data": "5",
            "render": function (data, type, row, meta) {
                // Assuming "M" is for Male and "F" is for Female
                return data === 'M' ? 'Male' : 'Female';
            }
        },
        // {
        //     "targets": 1, // Assuming control number is in the second column (index 1)
        //     "render": function (data, type, row, meta) {
        //         return '<a href="./upload_grade.php?course_subject_id=' + row[0] + '&course_subject_name=' + encodeURIComponent(row[1]) + '" class="courseSubjectLink" data-control-number="' + data + '">' + data + '</a>';
        //     }
        // },
        {
            "targets": -1,
            "data": null,
            "defaultContent": "<button class='btn btn-primary btn-xs tblEdit'>Edit </button> <button class='btn btn-danger btn-xs tblDelete'>Delete</button>" 
        }
    ],
    "initComplete": function () {
    // Add sorting dropdown for student_gender
    var select = $('<label for="genderFilter" class="mx-2">Gender Filter:</label><select id="genderFilter" class="form-select"><option value="">All Genders</option><option value="M">Male</option><option value="F">Female</option></select>')
        .appendTo('#studentListTable_length')
        .on('change', function () {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            table.column(4).search(val, true, false, true).draw();
        });
},

    "order": [
        [1, 'asc'] // Default sorting by student_full_name in ascending order
    ]
});

$('#studentListTable tbody').on('click', 'button.tblDelete', function() {
    var tr = $(this).closest('tr');

    if( $(tr).hasClass('child') ){
        tr = $(tr).prev();
    }

    var data = table.row(tr).data();

    deleteStudentId = data[0];

    
    var confirmationMessage = 'Are you sure want to delete student named: ' + data[1] + '?';

    const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-success mx-1",
        cancelButton: "btn btn-danger mx-1"
    },
    buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
    title: confirmationMessage,
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!",
    cancelButtonText: "No, cancel!",
    reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: './ajaxRequest/StudentDelete.php', 
                data: {deleteStudentId: deleteStudentId},
                success: function (response) {
                    const data = JSON.parse(response);

                    if(data.status === 'success'){

                        swalWithBootstrapButtons.fire({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: "Deleted!",
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                            animation:true,
                            customClass: {
                                timerProgressBar: 'customeProgressBar',
                            },
                            willClose: () => {
                                // Change the URL to the desired destination
                                window.location.reload();
                            }
                        });
                    }else{
                        swalWithBootstrapButtons.fire({
                            toast: true,
                            position: "top-end",
                            icon: "error",
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                            animation:true,
                            customClass: {
                                timerProgressBar: 'customeProgressBar',
                            }
                        });
                    }
                },
                error: function (error) {
                    // Handle the error here
                    console.error(error);
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                title: "Cancelled",
                text: "Deletion process canceled.",
                icon: "info",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                animation: true,
                customClass: {
                    timerProgressBar: 'customeProgressBar',
                }
            });
        }
    });

});
</script>
<script src="./JS/StudentEdit.js"></script>
</body>
</html>
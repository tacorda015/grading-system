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
$userAccountId = $getUserData['account_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="./image/favicon.ico">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./node_modules/bootstrap-icons/font/bootstrap-icons.min.css">

    <!-- Manual CSS -->
    <link rel="stylesheet" href="./CSS/main.css">

    <!-- Jquery -->
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>

    <!-- Sweet Alert 2 -->
    <link rel="stylesheet" href="./node_modules/sweetalert2/dist/sweetalert2.min.css">
    <style>
        .tableListOfCourseSubject::-webkit-scrollbar{
            display: none;
        }
        .courseSubjectLink{
            cursor: pointer;
            border-bottom: 1px solid #888;
        }
        .courseSubjectLink td{
            padding: 4px;
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
            <div class="bg-body-tertiary rounded my-2" style="height: calc(100vh - 96px);">
                <div class="row rounded py-2 m-auto g-2">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="bg-primary bg-opacity-10 border border-primary shadow rounded p-2" style="height: 200px;">
                            <div class="h5 text-center">
                                List of Course/Subject
                            </div>
                            <div style="height: 150px; overflow-y: auto;" class="tableListOfCourseSubject">
                                <table id="ListOfCourseSubject" class="w-100">
                                    <thead class="sticky-top bg-primary text-white">
                                        <tr>
                                            <td class="text-center">#</td>
                                            <td class="text-center">Name</td>
                                            <td class="text-center">Day</td>
                                        </tr>
                                    </thead>
                                        <tbody class="table-group-divider">
                                            <?php
                                            $listOfCourseSubject = "SELECT * FROM course_subject_table WHERE teacher_id = '{$getUserData['account_id']}' ORDER BY course_subject_id DESC";
                                            $listOfCourseSubjectResult = $con->query($listOfCourseSubject);

                                            if ($listOfCourseSubjectResult->num_rows > 0) {
                                                $counter = 1;
                                                while ($row = $listOfCourseSubjectResult->fetch_assoc()) {
                                            ?>
                                                <tr class="courseSubjectLink" data-course-subject-id = "<?php echo $row['course_subject_id'] ?>">
                                                    <td class="text-center"><?php echo $counter ?></td>
                                                    <td class="text-center"><?php echo $row['course_subject_name'] ?></td>
                                                    <td class="text-center"><?php echo $row['course_subject_day'] ?></td>
                                                </tr>
                                            <?php
                                                    $counter++;
                                                }
                                            } else {
                                            ?>
                                                <tr class="courseSubjectLink" data-course-subject-id = "courseSubject">
                                                    <td colspan="2" class="text-center">No Course/Subject</td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <?php
                        $studentCount = "SELECT COUNT(*) AS totalStudent ,
                        SUM(CASE WHEN s.student_gender = 'M' THEN 1 ELSE 0 END) AS maleCount,
                        SUM(CASE WHEN s.student_gender = 'F' THEN 1 ELSE 0 END) AS femaleCount
                        FROM student_table s
                        LEFT JOIN course_subject_table c ON s.course_subject_id = c.course_subject_id
                        LEFT JOIN account_table a ON c.teacher_id = a.account_id
                        WHERE a.account_id = '{$userAccountId}'";
                        $studentCountResult = $con->query($studentCount)->fetch_assoc();
                        
                    ?>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="bg-success bg-opacity-10 border border-success shadow rounded p-2" style="height: 200px;">
                            <div class="h5 text-center">
                                Number of Student
                            </div>
                            <div style="height: 150px; overflow-y: auto;" class="tableListOfCourseSubject">
                                <div class="d-flex flex-column g-2">
                                    <div class="d-flex flex-column g-2 text-center mt-2">
                                        <p class="m-0">Total Number of Student</p>
                                        <p class="m-0 fw-bold"><?php echo $studentCountResult['totalStudent']; ?></p>
                                    </div>
                                    <div class="d-flex flex-column mt-4 gap-2 align-items-center">
                                        <div class="d-flex gap-3">
                                            <p class="m-0">Number of Boys: </p>
                                            <p class="m-0 fw-bold"><?php echo $studentCountResult['maleCount'] ? : '0'; ?></p>
                                        </div>
                                        <div class="d-flex gap-3">
                                            <p class="m-0">Number of Girls: </p>
                                            <p class="m-0 fw-bold"><?php echo $studentCountResult['femaleCount'] ? : '0'; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="bg-warning bg-opacity-10 border border-warning shadow rounded p-2 d-flex justify-content-center align-items-center" style="height: 200px;">
                            <div class="h5 text-center">
                                Future Use
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="bg-info bg-opacity-10 border border-info shadow rounded p-2 d-flex justify-content-center align-items-center" style="height: 200px;">
                            <div class="h5 text-center">
                                Future Use
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-body-secondary row rounded py-2 m-auto mt-2 g-2 h-auto">
                    <div class="col-12 m-0">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-center h4">Todo List</p>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="bg-info bg-opacity-10 border border-info shadow rounded p-2" style="height: 200px;">
                                    <p class="h4 text-center">
                                        To-do
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="bg-warning bg-opacity-10 border border-warning shadow rounded p-2" style="height: 200px;">
                                    <p class="h4 text-center">
                                        On-Going
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="bg-success bg-opacity-10 border border-success shadow rounded p-2" style="height: 200px;">
                                    <p class="h4 text-center">
                                        Done
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

<!-- Bootstrap -->
<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- Sweet Alert 2 -->
<script src="./node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
<script>
    $('#ListOfCourseSubject').on('click', '.courseSubjectLink', function(e) {
        e.preventDefault();

        var courseSubjectId = $(this).data('course-subject-id');

        if(courseSubjectId == 'courseSubject'){
            window.location.href = './course_subject.php';
        }else{
            $.ajax({
                type: 'POST',
                url: './ajaxRequest/SessionSet.php',
                data: { courseSubjectId: courseSubjectId, sessionId: 'NULL' },
                success: function(response) {
                    console.log(response);
                    // Optionally, you can redirect to the upload_grade.php page after setting the session
                    window.location.href = './upload_grade.php';
                },
                error: function(xhr, status, error) {
                    // Handle errors if any
                    console.error(error);
                }
            });
        }
    });
</script>
</body>
</html>
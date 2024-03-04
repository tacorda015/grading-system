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

    <link rel="stylesheet" href="./node_modules/dragula/dist/dragula.min.css">
    
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
        .itemCardCss{
            max-height: 80px;
            display: -webkit-box;
            -webkit-line-clamp: 3; 
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: background-color 0.3s ease;
        }
        .itemCardCss:not(:-webkit-any-line-clamp)::after {
            content: "..."; /* Display ellipsis */
        }
        .itemCardCss:hover{
            background-color: rgba(240, 240, 240, 0.7);
            cursor: pointer;
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
                <div class="bg-body-secondary row rounded py-2 m-auto mt-2 g-2">
                    <div class="col-12 m-0">
                        <div class="row">
                            <div class="col-12">
                                <p class="text-center h4">Todo List</p>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="bg-info bg-opacity-10 border border-info shadow rounded p-2">
                                    <div class="position-relative">
                                        <p class="h4 text-center mb-4">
                                            To-do
                                        </p>
                                        <button type="button" class="btn btn-primary position-absolute top-0 end-0" data-bs-toggle="modal" data-bs-target="#addTaskModal"><i class="bi bi-plus-circle"></i></button>
                                    </div>
                                    <div class="h-100" style="min-height: 200px;">
                                    
                                        <!-- Task items go here -->
                                        <div class="to-do-list d-flex flex-column gap-1"  style="min-height: 200px;">
                                            <?php
                                                $taskQuery = "SELECT * FROM task_table WHERE account_id = '{$userAccountId}' AND task_status = 1 ORDER BY task_order";
                                                $taskResult = $con->query($taskQuery);

                                                if($taskResult->num_rows > 0 ){
                                                    while($taskRow = $taskResult->fetch_assoc()){
                                                        $level = $taskRow['task_level'];
                                                        switch($level){
                                                            case 1 : $style = "border-left: solid 4px green; !important"; break;
                                                            case 2 : $style = "border-left: solid 4px yellow; !important"; break;
                                                            case 3 : $style = "border-left: solid 4px orange; !important"; break;
                                                            case 4 : $style = "border-left: solid 4px red; !important"; break;
                                                        }
                                                        ?>
                                                        <div class="rounded-end shadow px-3 py-2 itemCardCss" data-task-id=<?php echo $taskRow['task_id'] ?> style="<?php echo $style; ?>;"><span class="fw-bold p-0"><?php echo $taskRow['task_name'] ?></span> - <?php echo $taskRow['task_description'] ?></div>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4" id="ongoing-list">
                                <div class="bg-warning bg-opacity-10 border border-warning shadow rounded p-2">
                                    <p class="h4 text-center">
                                        On-Going
                                    </p>
                                    <!-- Task items go here -->
                                    <div class="on-going h-100 d-flex flex-column gap-1"  style="min-height: 200px;">
                                        <?php
                                            $taskQuery = "SELECT * FROM task_table WHERE account_id = '{$userAccountId}' AND task_status = 2 ORDER BY task_order";
                                            $taskResult = $con->query($taskQuery);

                                            if($taskResult->num_rows > 0 ){
                                                while($taskRow = $taskResult->fetch_assoc()){
                                                    $level = $taskRow['task_level'];
                                                    switch($level){
                                                        case 1 : $style = "border-left: solid 4px green; !important"; break;
                                                        case 2 : $style = "border-left: solid 4px yellow; !important"; break;
                                                        case 3 : $style = "border-left: solid 4px orange; !important"; break;
                                                        case 4 : $style = "border-left: solid 4px red; !important"; break;
                                                    }
                                                    ?>
                                                        <div class="rounded-end shadow px-3 py-2 itemCardCss" data-task-id=<?php echo $taskRow['task_id'] ?> style="<?php echo $style; ?>"><span class="fw-bold p-0"><?php echo $taskRow['task_name'] ?></span> - <?php echo $taskRow['task_description'] ?></div>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4" id="done-list">
                                <div class="bg-success bg-opacity-10 border border-success shadow rounded p-2">
                                    <p class="h4 text-center">
                                        Done
                                    </p>
                                    <!-- Task items go here -->
                                    <div class="done h-100 d-flex flex-column gap-1"  style="min-height: 200px;">
                                        <?php
                                            $taskQuery = "SELECT * FROM task_table WHERE account_id = '{$userAccountId}' AND task_status = 3 ORDER BY task_order";
                                            $taskResult = $con->query($taskQuery);

                                            if($taskResult->num_rows > 0 ){
                                                while($taskRow = $taskResult->fetch_assoc()){
                                                    $level = $taskRow['task_level'];
                                                    switch($level){
                                                        case 1 : $style = "border-left: solid 4px green; !important"; break;
                                                        case 2 : $style = "border-left: solid 4px yellow; !important"; break;
                                                        case 3 : $style = "border-left: solid 4px orange; !important"; break;
                                                        case 4 : $style = "border-left: solid 4px red; !important"; break;
                                                    }
                                                    ?>
                                                        <div class="rounded-end shadow px-3 py-2 itemCardCss" data-task-id=<?php echo $taskRow['task_id'] ?> style="<?php echo $style; ?>"><span class="fw-bold p-0"><?php echo $taskRow['task_name'] ?></span> - <?php echo $taskRow['task_description'] ?></div>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<!-- Modal -->
<?php include "./Modals/home_AddTask.php" ?>

<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Task Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="display_task_id" id="display_task_id" class="display_task_id">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="display_task_status" class="form-label">Task Status</label>
                            <select class="form-select" name="display_task_status" id="display_task_status" aria-label="Default select example" disabled>
                            <option value="" selected>Loading ...</option>
                            <option value="1">To-do</option>
                            <option value="2">On-Going</option>
                            <option value="3">Done</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="display_task_level" class="form-label">Task Level</label>
                            <select class="form-select" name="display_task_level" id="display_task_level" aria-label="Default select example" disabled>
                            <option value="" selected>Loading ...</option>
                            <option value="1">Low Priority</option>
                            <option value="2">Meduim Priority</option>
                            <option value="3">High Priority</option>
                            <option value="4">Urgent</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="display_task_name" class="form-label">Task Title</label>
                    <input type="text" name="display_task_name" class="form-control" placeholder="Task Title" id="display_task_name" value="Loading ..." readonly>
                </div>
                <div class="mb-3">
                    <label for="display_task_description" class="form-label">Task Description</label>
                    <textarea class="form-control" name="display_task_description" placeholder="Task description" id="display_task_description" style="height: 100px" readonly>Loading ...</textarea>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="display_task_start" class="form-label">Task Start</label>
                            <input type="datetime-local" class="form-control" name="display_task_start" id="display_task_start" placeholder="Task Start" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="display_task_end" class="form-label">Task End</label>
                            <input type="datetime-local" class="form-control" name="display_task_end" id="display_task_end" placeholder="Task End" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap -->
<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- Sweet Alert 2 -->
<script src="./node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
<!-- Drag and Drop -->
<script src="./node_modules/dragula/dist/dragula.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.3/dragula.min.js"></script>
<script>
    // Define containers using class names
    var containers = [
        document.querySelector('.to-do-list'),
        document.querySelector('.on-going'),
        document.querySelector('.done')
    ];

    // Assign numerical values to your containers
    const containerValues = {
        'to-do-list': 1,
        'ongoing-list': 2,
        'done-list': 3
    };

    // Initialize Dragula
    var drake = dragula(containers, {
    });

    // Add a custom drop event handler (optional)
    // Add a custom drop event handler (optional)
    drake.on('drop', function (el, target, source, sibling) {

    // Check if target is defined
    if (target) {
        const taskId = el.dataset.taskId;

        // Check the class name of the target container
        const targetContainerClassName = target.className;

        // Use the container class name to determine the container value
        let targetContainerValue;
        if (targetContainerClassName.includes('to-do-list')) {
            targetContainerValue = 1;
        } else if (targetContainerClassName.includes('on-going')) {
            targetContainerValue = 2;
        } else if (targetContainerClassName.includes('done')) {
            targetContainerValue = 3;
        }

        // Get the new Task Order
        var newOrder = Array.from(el.parentNode.children).indexOf(el) + 1;

        // Return all task Order
        var displayOrders = Array.from(el.parentNode.children).map(function(task) {
            return { task_id: task.getAttribute('data-task-id'), order: Array.from(task.parentNode.children).indexOf(task) + 1 };
        });

        $.ajax({
            type: 'POST',
            url: './ajaxRequest/TaskItemUpdate.php',
            data: {
                taskId: taskId,
                taskStatus: targetContainerValue,
                newOrder: newOrder,
                displayOrders: displayOrders,
            },
            success: function(response){
                console.log(response);
            },
            error: function(xhr, status, error){
                console.error(error);
            }
        });
    } else {
        console.log('Drop target is undefined');
    }
});
document.addEventListener('DOMContentLoaded', function () {
    var taskCards = document.querySelectorAll('.itemCardCss');

    taskCards.forEach(function (card) {
        card.addEventListener('click', function () {

            var taskId = this.dataset.taskId;

            $.ajax({
                type: 'GET',
                url: './ajaxRequest/TaskItemDisplay.php',
                data: {taskId: taskId},
                dataType: 'json',
                success: function(response){
                    $('#display_task_id').val(response.task_id);
                    $('#display_task_name').val(response.task_name);
                    $('#display_task_description').val(response.task_description);
                    $('#display_task_status').val(response.task_status);
                    $('#display_task_level').val(response.task_level);
                    $('#display_task_start').val(response.task_start);
                    $('#display_task_end').val(response.task_end);

                },
                error: function(xhr, status, error){
                    console.error(error);
                }
            });
            $('#taskModal').modal('show');
        });
    });
});

$('#taskModal').on('hidden.bs.modal', function () {
    // Reset values when the modal is hidden
    $('#display_task_id').val('');
    $('#display_task_name').val('Loading ...');
    $('#display_task_description').val('Loading ...');
});

// $('.itemCardCss').on('click', function (e) {
//     e.preventDefault();

//     // Retrieve the task ID from the clicked task card
//     var taskId = $(this).data('task-id');

//     // Set the task ID in the modal content
//     $('#taskDetails').text('Task ID: ' + taskId);

//     // Open the modal
//     $('#taskModal').modal('show');
// });


</script>
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

    $('.addTaskSaveButton').on('click', function(e){
        e.preventDefault();

        if(inputValidation()){
            let addTaskFormData = $('#addTaskModal form').serialize();

           $.ajax({
            url: './ajaxRequest/TaskItemAdd.php',
            method: "POST",
            data: addTaskFormData,
            success: function (response){
                const data = JSON.parse(response);

                if (data.status === 'success') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        animation: true,
                        customClass: {
                        timerProgressBar: 'customeProgressBar',
                        },
                        willClose: () => {
                        // Change the URL to the desired destination
                        window.location.reload();
                        },
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        animation: true,
                        customClass: {
                        timerProgressBar: 'customeProgressBar',
                        },
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Error Adding Student: ', xhr.responseText);
            },
           });
        }else {
            Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Please fill in all required fields',
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
    
    function inputValidation(){
        let isValid = false;

        $('#addTaskModal input, #addTaskModal textarea').each(function () {
            let inputValue = $(this).val().trim();

            if(inputValue === ''){
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
                isValid = true;
            }
        });            

        return isValid;
    }
</script>
</body>
</html>
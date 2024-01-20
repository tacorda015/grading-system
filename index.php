<?php
session_start();

if (isset($_SESSION['account_id'])) {
    header("Location: home.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Login</title>

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

</head>
<!-- <body style="background-image: url(./image/nature.jpg);"> -->
<body>
    <div class="container">
        <div class="d-flex justify-content-center align-items-center flex-column p-2">
            <p class="fs-3 fw-bolder text-center mt-3">Login Account</p>
            <div class="border shadow rounded mt-3 px-3 py-4 w-100" style="max-width: 400px; height: auto;">
                <div class="d-flex justify-content-center">
                    <img src="./image/android-chrome-192x192.png" style="height: 90px; width: auto;" class="m-auto" alt="">
                </div>
                <form method="post">
                    <div class="form-floating mt-3">
                        <input type="text" class="form-control" id="userName" name="userName" placeholder="Enter your username here" autocomplete="off" autofocus aria-autocomplete="none">
                        <label for="userName"><i class="fi fi-ss-user"></i> Username</label>
                    </div>
                    <div class="form-floating mt-3">
                        <input type="password" class="form-control" id="userPassword" name="userPassword" placeholder="Enter your password here" autocomplete="off" autofocus aria-autocomplete="none">
                        <label for="userPassword"><i class="fi fi-rr-lock"></i> Password</label>
                    </div>
                    <hr>        
                    <div class="mt-3 d-flex flex-column gap-3">
                        <button type="button" class="btn btn-primary m-auto col-5" id="loginBtn">Login</button>
                        <a href="./register.php" class="btn btn-outline-primary m-auto col-5">Register</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- Bootstrap -->
<script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Sweet Alert 2 -->
<script src="./node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
<script>

    $(document).ready(function () {
        const inputFields = $('input[name="userName"], input[name="userPassword"]');

        inputFields.on('input', function () {
            $(this).removeClass('hightlights');
        });

        $('#loginBtn').on('click', function () {
            inputFields.removeClass('hightlights');

            inputFields.each(function () {
                if ($(this).val().trim() === '') {
                    $(this).addClass('hightlights');
                }
            });

            if ($('.hightlights').length > 0) {

                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "error",
                    title: "Please fill in all required fields",
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    animation:true,
                    customClass: {
                        timerProgressBar: 'customeProgressBar',
                    }
                });

                return;
            }

            const userName = $('input[name="userName"]').val();
            const userPassword = $('input[name="userPassword"]').val();

            $.ajax({
                url: './ajaxRequest/AccountCheck.php',
                method: 'POST',
                data: {
                    userName: userName,
                    userPassword: userPassword,
                },
                success: function (response) {
                    const data = JSON.parse(response);

                    if (data.status === 'success') {

                        Swal.fire({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                            animation:true,
                            customClass: {
                                timerProgressBar: 'customeProgressBar',
                            },
                            willClose: () => {
                                // Change the URL to the desired destination
                                window.location.href = 'home.php';
                            }
                        });

                    } else {
                        Swal.fire({
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
                    console.error('Error occurred while logging in', error);
                },
            });
        });
    });
</script>
</body>
</html>

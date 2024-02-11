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
    <title>System Registration</title>

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
            <p class="fs-3 fw-bolder text-center mt-3">Register Account</p>
            <div class="border shadow rounded mt-3 px-3 py-4 w-100" style="max-width: 500px; height: auto;">
                <div class="d-flex justify-content-center">
                    <img src="./image/android-chrome-192x192.png" style="height: 90px; width: auto;" class="m-auto" alt="">
                </div>
                <form method="post">

                    <div class="form-floating mt-3">
                        <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter your first name here" autocomplete="off" autofocus aria-autocomplete="none">
                        <label for="fname"><i class="fi fi-ss-user"></i> First Name</label>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter your Last Name here" autocomplete="off" autofocus aria-autocomplete="none">
                        <label for="lname"><i class="fi fi-ss-user"></i> Last Name</label>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="text" class="form-control" id="nameTitle" name="nameTitle" placeholder="Example: LPT" autocomplete="off" autofocus aria-autocomplete="none">
                        <label for="nameTitle"><i class="fi fi-ss-user"></i> Name Title: <sup>Example: LPT</sup></label>
                    </div>

                    <div class="form-floating mt-3">
                        <input type="text" class="form-control" id="userName" name="userName" placeholder="Enter your username here" autocomplete="off" autofocus aria-autocomplete="none">
                        <label for="userName"><i class="fi fi-ss-user"></i> Username</label>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mt-3">
                                <input type="password" class="form-control" id="userPassword" name="userPassword" placeholder="Enter your password here" autocomplete="off" autofocus aria-autocomplete="none">
                                <label for="userPassword"><i class="fi fi-rr-lock"></i> Password</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mt-3">
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Enter your password here" autocomplete="off" autofocus aria-autocomplete="none">
                                <label for="confirmPassword"><i class="fi fi-rr-lock"></i> Confirm Password</label>
                            </div>
                        </div>
                    </div>
                    <hr>        
                    <div class="mt-3 d-flex flex-column gap-3">
                        <button type="submit" class="btn btn-primary m-auto col-5" id="registerBtn">Register</button>
                        <a href="./index.php" class="btn btn-outline-primary m-auto col-5">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- Bootstrap -->
<script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Sweet Alert 2 -->
<script src="./node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="./JS/RegisterAccount.js"></script>
</body>
</html>

<div class="offcanvas offcanvas-end w-auto" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel"><?= 'Hello ' . $getUserData['account_fName'] ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="home.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="course_subject.php">Course/Subject</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="student_list.php">Student List</a>
            </li>
            <hr>
            <li>
                <a href="./logout.php" class="btn btn-primary w-100 d-flex gap-2"><i class="bi bi-power"></i> Logout</a>
            </li>
        </ul>
    </div>
</div>
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
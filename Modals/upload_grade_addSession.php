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
                <input type="hidden" name="addCourseSubjectId" id="addCourseSubjectId" value="<?php echo $CourseSubjectIdSetted ?>">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="addSessionButton">Add</button>
            </div>
        </form>
        </div>
    </div>
</div>
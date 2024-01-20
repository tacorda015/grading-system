<!-- Update Course Subject Modal -->
<div class="modal fade" id="updateSessionModal" tabindex="-1" aria-labelledby="updateSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="updateSessionModalLabel">Update Session</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" id="updateSessionForm">
            <div class="modal-body">
                <input type="hidden" name="updateCourseSubjectId" id="updateCourseSubjectId" value="<?php echo $CourseSubjectIdSetted ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="updateSessionButton">Save Changes</button>
            </div>
        </form>
        </div>
    </div>
</div>
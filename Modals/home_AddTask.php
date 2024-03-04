<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addTaskModal">Add Task</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="#" method="post">
          <input type="hidden" name="account_id" value="<?php echo $userAccountId ?>">
          <div class="mb-3">
            <label for="task_name" class="form-label">Task Title <sup class="text-danger">*</sup></label>
            <input type="text" name="task_name" class="form-control" placeholder="Task Title" id="task_name">
          </div>
          <div class="mb-3">
            <label for="task_description" class="form-label">Task Description <sup class="text-danger">*</sup></label>
            <textarea class="form-control" name="task_description" placeholder="Task description" id="task_description" style="height: 100px"></textarea>
          </div>
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="mb-3">
                <label for="task_status" class="form-label">Task Status <sup class="text-danger">*</sup></label>
                <select class="form-select" name="task_status" aria-label="Default select example">
                  <option value="1">To-do</option>
                  <option value="2">On-Going</option>
                  <option value="3">Done</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="mb-3">
                <label for="task_level" class="form-label">Task Level <sup class="text-danger">*</sup></label>
                <select class="form-select" name="task_level" aria-label="Default select example">
                  <option value="1">Low Priority</option>
                  <option value="2">Meduim Priority</option>
                  <option value="3">High Priority</option>
                  <option value="4">Urgent</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="mb-3">
                <label for="task_start" class="form-label">Task Start <sup class="text-danger">*</sup></label>
                <input type="datetime-local" class="form-control" name="task_start" id="task_start" placeholder="Task Start" >
              </div>
            </div>
            <div class="col-12 col-md-6">
            <div class="mb-3">
                <label for="task_end" class="form-label">Task End</label>
                <input type="datetime-local" class="form-control" name="task_end" id="task_end" placeholder="Task Start" >
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary addTaskSaveButton">Save Task</button>
      </div>
    </div>
  </div>
</div>
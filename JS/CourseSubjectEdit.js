// For Edit Button Get the Primary Key
$('#courseSectionTable tbody').on('click', 'button.tblEdit', function () {
  var tr = $(this).closest('tr');

  if ($(tr).hasClass('child')) {
    tr = $(tr).prev();
  }

  var data = table.row(tr).data();

  fetchAdditionalData(data[0]);
});

function fetchAdditionalData(courseSubjectId) {
  $.ajax({
    url: './ajaxRequest/CourseSubjectGet.php',
    method: 'GET',
    data: { courseSubjectId: courseSubjectId },
    dataType: 'json',
    success: function (response) {
      // Display modal with the fetched data
      table.ajax.reload(null, false);
      $('#EditCourseSubjectModal').modal('show');

      // Populate the modal with the fetched data
      populateModal(response);
    },
    error: function (xhr, status, error) {
      console.error('Error fetching data:', xhr.responseText);
    },
  });
}

function populateModal(data) {
  $('#editCourseName').val(data.course_name || 'N/A');
  $('#editSubjectName').val(data.subject_name || 'N/A');
  $('#editSubjectTitle').val(data.subject_title || 'N/A');
  $('#editSubjectTeacher').val(data.course_subject_teacher || 'N/A');
  $('#editCourseSubjectProgramHead').val(
    data.course_subject_program_head || 'N/A'
  );
  $('#editTeacherId').val(data.teacher_id || 'N/A');
  $('#editSyStart').val(data.sy_start || 'N/A');
  $('#editSyEnd').val(data.sy_end || 'N/A');
  $('#editSySemester').val(data.sy_semester || 'N/A');
  $('#editMeetingDay').val(data.course_subject_day || 'N/A');
  $('#editSubjectRoom').val(data.course_subject_room || 'N/A');
  $('#editMeetingTimeStart').val(data.course_subject_time_start || 'N/A');
  $('#editMeetingTimeEnd').val(data.course_subject_time_end || 'N/A');
  $('#editCourseSubjectId').val(data.course_subject_id || 'N/A');
}

function editSyEnd() {
  var startYear = parseInt(document.getElementById('editSyStart').value);
  var endDropdown = document.getElementById('editSyEnd');

  // Clear existing options
  endDropdown.innerHTML = '';

  // Add the selected value in "Start"
  var optionStart = document.createElement('option');
  optionStart.value = startYear;
  optionStart.text = startYear;
  endDropdown.add(optionStart);

  // Add the selected value in "Start" plus one year
  var optionEnd = document.createElement('option');
  optionEnd.value = startYear + 1;
  optionEnd.text = startYear + 1;
  endDropdown.add(optionEnd);
}

$('#EditCourseSubjectModal').on('shown.bs.modal', function (e) {
  editSyEnd();
});

$('#saveChangesBtn').on('click', function () {
  // Check input validation before proceeding
  if (validateForm()) {
    // Serialize form data
    var formData = $('#EditCourseSubjectModal form').serialize();

    // Perform Ajax request to update data
    $.ajax({
      url: './ajaxRequest/CourseSubjectUpdate.php', // Replace with your update script
      method: 'POST',
      data: formData,
      success: function (response) {
        const data = JSON.parse(response);

        if (data.status === 'success') {
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: data.message,
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            animation: true,
            customClass: {
              timerProgressBar: 'customeProgressBar',
            },
            willClose: () => {
              // Change the URL to the desired destination
              window.location.href = 'course_subject.php';
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
        console.error('Error updating data:', xhr.responseText);
      },
    });
  } else {
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

function validateForm() {
  var isValid = true;

  // Iterate over form inputs and selects and check if they are empty
  $('#EditCourseSubjectModal input, #EditCourseSubjectModal select').each(
    function () {
      if ($(this).val() == null || $(this).val().trim() === '') {
        // Add a class to is-invalid the empty input/select
        $(this).addClass('is-invalid');
        isValid = false;
      } else {
        // Remove the is-invalid class if the input/select is not empty
        $(this).removeClass('is-invalid');
      }
    }
  );

  return isValid;
}

$('#EditCourseSubjectModal input, #EditCourseSubjectModal select').on(
  'input',
  function () {
    $(this).removeClass('is-invalid');
  }
);

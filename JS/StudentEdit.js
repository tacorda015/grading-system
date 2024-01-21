// For Edit Button Get the Primary Key
$('#studentListTable tbody').on('click', 'button.tblEdit', function () {
  var tr = $(this).closest('tr');

  if ($(tr).hasClass('child')) {
    tr = $(tr).prev();
  }

  var data = table.row(tr).data();

  fetchAdditionalData(data[0]);
});

function fetchAdditionalData(studentId) {
  $.ajax({
    url: './ajaxRequest/StudentGet.php',
    method: 'GET',
    data: { studentId: studentId },
    dataType: 'json',
    success: function (response) {
      console.log(response);
      // Display modal with the fetched data
      table.ajax.reload(null, false);
      $('#EditStudentModal').modal('show');

      // Populate the modal with the fetched data
      populateModal(response);
    },
    error: function (xhr, status, error) {
      console.error('Error fetching data:', xhr.responseText);
    },
  });
}

function populateModal(data) {
  $('#editStudentFirstName').val(data.student_fName || 'N/A');
  $('#editStudentMiddleName').val(data.student_mName);
  $('#editStudentLastName').val(data.student_lName || 'N/A');
  $('#editStudentNumber').val(data.student_number || 'N/A');
  $('#editStudentStatus').val(data.student_status || 'N/A');
  $('#editCourseSubjectId').val(data.course_subject_id || 'N/A');
  $('#editStudentId').val(data.student_id || 'N/A');
  $('#editStudentGender').val(data.student_gender || 'N/A');
}

$('#saveChangesBtn').on('click', function () {
  // Check input validation before proceeding
  if (validateForm()) {
    // Serialize form data
    var formData = $('#EditStudentModal form').serialize();

    // Perform Ajax request to update data
    $.ajax({
      url: './ajaxRequest/StudentUpdate.php', // Replace with your update script
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

  $('#EditStudentModal input, #EditStudentModal select').each(function () {
    // Exclude the middle name field from validation
    if (
      $(this).attr('id') !== 'editStudentMiddleName' &&
      $(this).val().trim() === ''
    ) {
      // Add a class to is-invalid the empty input/select
      $(this).addClass('is-invalid');
      isValid = false;
    } else {
      // Remove the is-invalid class if the input/select is not empty
      $(this).removeClass('is-invalid');
    }
  });
  return isValid;
}

$('#EditStudentModal input, #EditStudentModal select').on('input', function () {
  $(this).removeClass('is-invalid');
});

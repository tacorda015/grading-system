$('#addStudentButton').on('click', function (e) {
  e.preventDefault();

  if (validateAddingForm()) {
    var addFormData = $('#addStudentCourseModal form').serialize();

    $.ajax({
      url: './ajaxRequest/StudentAdd.php',
      method: 'POST',
      data: addFormData,
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
        console.error('Error Adding Student: ', xhr.responseText);
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

function validateAddingForm() {
  var isValid = true;

  $('#addStudentCourseModal input, #addStudentCourseModal select').each(
    function () {
      // Exclude middle name from validation if it's empty
      if (
        $(this).attr('name') !== 'addStudentMiddleName' ||
        $(this).val().trim() !== ''
      ) {
        if ($(this).val().trim() === '') {
          $(this).addClass('is-invalid');
          isValid = false;
        } else {
          $(this).removeClass('is-invalid');
        }
      }
    }
  );

  return isValid;
}

$('#addStudentCourseModal input, #addStudentCourseModal select').on(
  'input',
  function () {
    $(this).removeClass('is-invalid');
  }
);

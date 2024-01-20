$('#addSessionButton').on('click', function (e) {
  e.preventDefault();

  if (validationAddingSession()) {
    var addFormData = $('#addSessionModal form').serialize();

    $.ajax({
      url: './ajaxRequest/SessionAdd.php',
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

function validationAddingSession() {
  var isValid = true;

  $('#addSessionModal input').each(function () {
    var inputValue = $(this).val().trim();

    if (inputValue === '') {
      $(this).addClass('is-invalid');
      isValid = false;
    } else {
      $(this).removeClass('is-invalid');

      // Validate session percentage
      if ($(this).attr('name') === 'addSessionPercent') {
        if (
          !isValidNumber(inputValue) ||
          !isValidSessionPercentage(inputValue)
        ) {
          $(this).addClass('is-invalid');
          displayPercentageError();
          isValid = false;
        }
      }
    }
  });

  return isValid;
}

function isValidNumber(value) {
  return !isNaN(parseFloat(value)) && isFinite(value);
}

function isValidSessionPercentage(value) {
  var numericValue = parseFloat(value);
  return isValidNumber(value) && numericValue >= 0 && numericValue <= 100;
}

function displayPercentageError() {
  $('#addSessionPercent').val(''); // Clear the input value
  $('#addSessionPercent').attr(
    'placeholder',
    'Please enter a valid number (0-100)'
  );
}

$('#addSessionModal input').on('input', function () {
  $(this).removeClass('is-invalid');
});

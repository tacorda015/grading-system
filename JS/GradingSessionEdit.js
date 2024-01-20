$('.nav-link#updateBtn').on('click', function (e) {
  $.ajax({
    type: 'POST',
    url: './ajaxRequest/GradingSessionData.php',
    data: { courseSubjectId: CourseSubjectIdSetted },
    success: function (response) {
      // Parse the JSON response
      var gradingSessionData = JSON.parse(response);

      // Log the array in the console
      console.log('Grading Session Data:', gradingSessionData);

      // Clear existing modal content
      var modalBody = $('#updateSessionModal .modal-body').empty();

      // Add labels row
      var labelsRow = $(
        '<div class="row"><div class="col">Session Name</div><div class="col">Grade Base</div><div class="col">Percentage</div></div>'
      );
      modalBody.append(labelsRow);

      // Iterate through the array and add input fields to the modal
      gradingSessionData.forEach(function (session, index) {
        // Create a new row
        var row = $('<div class="row InputValidation"></div>');

        // Session Name
        var sessionNameCol = $(
          '<div class="col"><div class="mb-3"><input type="text" class="form-control" name="updateSessionName[]" value="' +
            session.grading_session_name +
            '"></div></div>'
        );
        row.append(sessionNameCol);

        // Grade Base
        var gradeBaseCol = $(
          '<div class="col"><div class="mb-3"><input type="text" class="form-control" name="updateGradeBase[]" value="' +
            session.grading_session_base +
            '"></div></div>'
        );
        row.append(gradeBaseCol);

        // Percentage
        var percentageCol = $(
          '<div class="col"><div class="mb-3"><input type="text" class="form-control" name="updatePercentage[]" value="' +
            session.grading_session_percentage +
            '"></div></div>'
        );
        row.append(percentageCol);

        var sessionId = $(
          '<input type="hidden" name="updateSessionId[]" value="' +
            session.grading_session_id +
            '">'
        );
        row.append(sessionId);
        // Append the row to the modal body
        modalBody.append(row);
      });
      // Add the hidden input only once after the loop
      var hiddenInput = $(
        '<input type="hidden" name="updateCourseSubjectId" id="updateCourseSubjectId" value="' +
          CourseSubjectIdSetted +
          '">'
      );
      modalBody.append(hiddenInput);

      // Show the modal
      $('#updateSessionModal').modal('show');
    },
    error: function (xhr, status, error) {
      // Handle errors if any
      console.error(error);
    },
  });
});

$('#updateSessionButton').on('click', function (e) {
  // Prevent the default form submission
  e.preventDefault();

  // Perform input validation
  if (validateForm()) {
    // If validation passes, proceed with the AJAX request
    $.ajax({
      type: 'POST',
      url: './ajaxRequest/GradingSessionUpdate.php',
      data: $('#updateSessionForm').serialize(), // Serialize the form data
      success: function (response) {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: 'Update Successfully Recorded.',
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
      },
      error: function (xhr, status, error) {
        // Handle errors if any
        console.error(error);
      },
    });
  }
});

function validateForm() {
  // Validate each row dynamically
  var isValid = true;
  var isPercentage = false;
  var percentageTotal = 0;

  $('.modal-body .row.InputValidation').each(function () {
    var sessionNameInput = $(this).find('[name^="updateSessionName"]');
    var gradeBaseInput = $(this).find('[name^="updateGradeBase"]');
    var percentageInput = $(this).find('[name^="updatePercentage"]');

    var sessionName = sessionNameInput.val();
    var gradeBase = gradeBaseInput.val();
    var percentage = percentageInput.val();

    // Remove previous invalid class
    sessionNameInput.removeClass('is-invalid');
    gradeBaseInput.removeClass('is-invalid');
    percentageInput.removeClass('is-invalid');

    if (sessionName === '' || gradeBase === '' || percentage === '') {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: 'All fields are required',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        animation: true,
        customClass: {
          timerProgressBar: 'customeProgressBar',
        },
      });
      isValid = false;

      // Add is-invalid class to empty inputs
      if (sessionName === '') sessionNameInput.addClass('is-invalid');
      if (gradeBase === '') gradeBaseInput.addClass('is-invalid');
      if (percentage === '') percentageInput.addClass('is-invalid');

      // return false; // exit the loop
    }

    if (
      !isValidInteger(gradeBase) ||
      parseInt(gradeBase) > 100 ||
      parseInt(gradeBase) < 0
    ) {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title:
          'Grade Base should be a valid integer less than or equal to 100.',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        animation: true,
        customClass: {
          timerProgressBar: 'customeProgressBar',
        },
      });
      isValid = false;

      // Add is-invalid class to gradeBase input
      gradeBaseInput.addClass('is-invalid');

      //   return false; // exit the loop
    }

    if (
      !isValidInteger(percentage) ||
      parseInt(percentage) < 0 ||
      parseInt(percentage) > 100
    ) {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: 'Percentage should be a valid integer between 0 and 100.',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        animation: true,
        customClass: {
          timerProgressBar: 'customeProgressBar',
        },
      });
      isValid = false;

      // Add is-invalid class to percentage input
      percentageInput.addClass('is-invalid');

      //   return false; // exit the loop
      isPercentage = true;
    } else {
      percentageTotal += parseInt(percentage);
    }
  });

  if (!isPercentage) {
    if (percentageTotal != 100) {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: 'Percentage should be equal to 100.',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        animation: true,
        customClass: {
          timerProgressBar: 'customeProgressBar',
        },
      });
      isValid = false;
      $('.modal-body .row.InputValidation [name^="updatePercentage"]').addClass(
        'is-invalid'
      );
    }

    return isValid;
  }
}

function isValidInteger(value) {
  return /^\d+$/.test(value);
}

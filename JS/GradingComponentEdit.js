$('.updateComponent').on('click', function (e) {
  var gradingSessionId = $(this).data('grading-session-id');
  $.ajax({
    type: 'POST',
    url: './ajaxRequest/GradingComponentData.php',
    data: { GradingSessionIdSetted: gradingSessionId }, // make this data-grading-session-id
    success: function (response) {
      // Parse the JSON response
      var gradingComponentData = JSON.parse(response);

      // Log the array in the console
      console.log('Grading Session Data:', gradingComponentData);

      // Clear existing modal content
      var modalBody = $('#updateComponentModal .modal-body').empty();

      // Add labels row
      var labelsRow = $(
        '<div class="row"><div class="col">Component Name</div><div class="col">Percentage</div></div>'
      );
      modalBody.append(labelsRow);

      // Iterate through the array and add input fields to the modal
      gradingComponentData.forEach(function (component, index) {
        // Create a new row
        var row = $('<div class="row InputValidation"></div>');

        // Component Name
        var componentNameCol = $(
          '<div class="col"><div class="mb-3"><input type="text" class="form-control" name="updateComponentName[]" value="' +
            component.component_name +
            '"></div></div>'
        );
        row.append(componentNameCol);

        // Component Percentage
        var componentPercentage = $(
          '<div class="col"><div class="mb-3"><input type="text" class="form-control" name="updateComponentPercentage[]" value="' +
            component.component_percentage +
            '"></div></div>'
        );
        row.append(componentPercentage);

        var componentId = $(
          '<input type="hidden" name="updateComponentId[]" value="' +
            component.component_id +
            '">'
        );
        row.append(componentId);
        // Append the row to the modal body
        modalBody.append(row);
      });
      // Add the hidden input only once after the loop
      var hiddenInput = $(
        '<input type="hidden" name="updateSessionId" id="updateSessionId" value="' +
          GradingSessionIdSetted +
          '">'
      );
      modalBody.append(hiddenInput);

      // Show the modal
      $('#updateComponentModal').modal('show');
    },
    error: function (xhr, status, error) {
      // Handle errors if any
      console.error(error);
    },
  });
});

$('#updateComponentButton').on('click', function (e) {
  // Prevent the default form submission
  e.preventDefault();

  // Perform input validation
  if (validateComponentForm()) {
    // If validation passes, proceed with the AJAX request
    $.ajax({
      type: 'POST',
      url: './ajaxRequest/GradingComponentUpdate.php',
      data: $('#updateComponentForm').serialize(), // Serialize the form data
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

function validateComponentForm() {
  // Validate each row dynamically
  var isValid = true;
  var isPercentage = false;
  var componentPercentageTotal = 0;

  $('.modal-body .row.InputValidation').each(function () {
    var componentNameInput = $(this).find('[name^="updateComponentName"]');
    var percentageInput = $(this).find('[name^="updateComponentPercentage"]');

    var componentName = componentNameInput.val();
    var Componentpercentage = percentageInput.val();

    // Remove previous invalid class
    componentNameInput.removeClass('is-invalid');
    percentageInput.removeClass('is-invalid');

    if (componentName === '' || Componentpercentage === '') {
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
      if (componentName === '') componentNameInput.addClass('is-invalid');
      if (Componentpercentage === '') percentageInput.addClass('is-invalid');

      //   return false; // exit the loop
    }

    if (
      !isValidInteger(Componentpercentage) ||
      parseInt(Componentpercentage) < 0 ||
      parseInt(Componentpercentage) > 100
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
      componentPercentageTotal += parseInt(Componentpercentage);
    }
  });

  if (!isPercentage) {
    if (componentPercentageTotal != 100) {
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
      $(
        '.modal-body .row.InputValidation [name^="updateComponentPercentage"]'
      ).addClass('is-invalid');
    }

    return isValid;
  }
}

function isValidInteger(value) {
  return /^\d+$/.test(value);
}

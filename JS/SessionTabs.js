$(document).ready(function () {
  $('.nav-link.gradingSession').on('click', function (e) {
    e.preventDefault();

    // Get the section ID from the clicked tab
    var sessionId = $(this).data('grading-session-id');
    var courseSubjectId = $(this).data('course-subject-id');

    var currentURL = window.location.href;
    console.log(currentURL);

    // Make an AJAX request to update the session value
    $.ajax({
      type: 'POST',
      url: './ajaxRequest/SessionSet.php', // Specify the path to your server-side script
      data: { courseSubjectId: courseSubjectId, sessionId: sessionId },
      success: function (response) {
        if (currentURL == './print_grade.php') {
          location.reload();
        } else {
          location.href = './upload_grade.php';
        }
      },
      error: function (xhr, status, error) {
        // Handle errors if any
        console.error(error);
        // Optionally, you can show a user-friendly error message
        alert('An error occurred while updating the session.');
      },
    });
  });
});

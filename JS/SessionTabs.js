$(document).ready(function () {
    $('.nav-link.gradingSession').on('click', function (e) {
        e.preventDefault();

        // Get the section ID from the clicked tab
        var sessionId = $(this).data('grading-session-id');
        var courseSubjectId = $(this).data('course-subject-id');

        // Make an AJAX request to update the session value
        $.ajax({
            type: 'POST',
            url: './ajaxRequest/SessionSet.php', // Specify the path to your server-side script
            data: { courseSubjectId: courseSubjectId, sessionId: sessionId  },
            success: function (response) {
                location.reload();
            },
            error: function (xhr, status, error) {
                // Handle errors if any
                console.error(error);
            }
        });
    });
});
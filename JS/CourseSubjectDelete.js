// For Delete Button Get the Primary Key
$('#courseSectionTable tbody').on('click', 'button.tblDelete', function () {
  var tr = $(this).closest('tr');

  if ($(tr).hasClass('child')) {
    tr = $(tr).prev();
  }

  var data = table.row(tr).data();

  deleteCourseSubjectId = data[0];

  var confirmationMessage = 'Are you sure you want to delete ' + data[1] + '?';

  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success mx-1',
      cancelButton: 'btn btn-danger mx-1',
    },
    buttonsStyling: false,
  });
  swalWithBootstrapButtons
    .fire({
      title: confirmationMessage,
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'No, cancel!',
      reverseButtons: true,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: 'POST',
          url: './ajaxRequest/CourseSubjectDelete.php',
          data: { deleteCourseSubjectId: deleteCourseSubjectId },
          success: function (response) {
            const data = JSON.parse(response);

            if (data.status === 'success') {
              swalWithBootstrapButtons.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Deleted!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500,
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
              swalWithBootstrapButtons.fire({
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
          error: function (error) {
            // Handle the error here
            console.error(error);
          },
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        swalWithBootstrapButtons.fire({
          title: 'Cancelled',
          text: 'Deletion process canceled.',
          icon: 'info',
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
});

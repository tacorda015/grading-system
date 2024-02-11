const inputFields = $('input[name="userName"], input[name="userPassword"]');

inputFields.on('input', function () {
  $(this).removeClass('hightlights');
});

$('#loginBtn').on('click', function (e) {
  e.preventDefault();
  inputFields.removeClass('hightlights');

  inputFields.each(function () {
    if ($(this).val().trim() === '') {
      $(this).addClass('hightlights');
    }
  });

  if ($('.hightlights').length > 0) {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'error',
      title: 'Please fill in all required fields',
      showConfirmButton: false,
      timer: 1000,
      timerProgressBar: true,
      animation: true,
      customClass: {
        timerProgressBar: 'customeProgressBar',
      },
    });

    return;
  }

  const userName = $('input[name="userName"]').val();
  const userPassword = $('input[name="userPassword"]').val();

  $.ajax({
    url: './ajaxRequest/AccountCheck.php',
    method: 'POST',
    data: {
      userName: userName,
      userPassword: userPassword,
    },
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
            window.location.href = 'home.php';
          },
        });
      } else {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'error',
          title: data.message,
          showConfirmButton: false,
          timer: 1000,
          timerProgressBar: true,
          animation: true,
          customClass: {
            timerProgressBar: 'customeProgressBar',
          },
        });
      }
    },
    error: function (error) {
      console.error('Error occurred while logging in', error);
    },
  });
});

const inputFields = $(
  'input[name="fname"], input[name="lname"], input[name="userName"], input[name="nameTitle"], input[name="userPassword"], input[name="confirmPassword"]'
);

inputFields.on('input', function () {
  $(this).removeClass('hightlights');
});

$('#registerBtn').on('click', function (e) {
  e.preventDefault();
  $(this).prop('disabled', true);

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
      timer: 1500,
      timerProgressBar: true,
      animation: true,
      customClass: {
        timerProgressBar: 'customeProgressBar',
      },
    });

    return;
  }

  const fname = $('input[name="fname"]').val();
  const lname = $('input[name="lname"]').val();
  const userName = $('input[name="userName"]').val();
  const nameTitle = $('input[name="nameTitle"]').val();
  const userPassword = $('input[name="userPassword"]').val();
  const confirmPassword = $('input[name="confirmPassword"]').val();

  if (userPassword === confirmPassword) {
    $.ajax({
      url: './ajaxRequest/AccountCreate.php',
      method: 'POST',
      data: {
        fname: fname,
        lname: lname,
        userName: userName,
        nameTitle: nameTitle,
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
              window.location.href = 'index.php';
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
          setTimeout(function () {
            $('#registerBtn').prop('disabled', false);
          }, 1000);
        }
      },
      error: function (error) {
        console.error('Error Accure while logging in', error);
      },
    });
  } else {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'warning',
      title: 'Password not match',
      showConfirmButton: false,
      timer: 100,
      timerProgressBar: true,
      animation: true,
      customClass: {
        timerProgressBar: 'customeProgressBar',
      },
    });
  }
});

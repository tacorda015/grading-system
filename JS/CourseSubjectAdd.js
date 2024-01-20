const addInputFields = $('input[name="addCourseName"], input[name="addSubjectName"], input[name="addSyStart"], input[name="addSyEnd"], input[name="addMeetingDay"], input[name="addMeetingTimeStart"], input[name="addMeetingTimeEnd"], input[name="addSubjectRoom"]');

addInputFields.on('input', function () {
    $(this).removeClass('is-invalid');
});


$('#addCourseSubject').on('click', function (e) {
    e.preventDefault(); 

    addInputFields.removeClass('is-invalid');

    addInputFields.each(function () {
        if ($(this).val().trim() === '') {
            $(this).addClass('is-invalid');
        }
    });

    if ($('.is-invalid').length > 0) {

        Swal.fire({
            toast: true,
            position: "top-end",
            icon: "error",
            title: "Please fill in all required fields",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
            animation:true,
            customClass: {
                timerProgressBar: 'customeProgressBar',
            }
        });

        return;
    }

    var formData = {
        addCourseName: $('#addCourseName').val(),
        addSubjectName: $('#addSubjectName').val(),
        addSubjectTeacher: $('#addSubjectTeacher').val(),
        addTeacherId: $('#addTeacherId').val(),
        addSyStart: $('#addSyStart').val(),
        addSyEnd: $('#addSyEnd').val(),
        addMeetingDay: $('#addMeetingDay').val(),
        addMeetingTimeStart: $('#addMeetingTimeStart').val(),
        addMeetingTimeEnd: $('#addMeetingTimeEnd').val(),
        addSubjectRoom: $('#addSubjectRoom').val()
    };

    // Make AJAX request
    $.ajax({
        type: 'POST',
        url: './ajaxRequest/CourseSubjectAdd.php', 
        data: formData,
        success: function (response) {
            const data = JSON.parse(response);

            if(data.status === 'success'){

                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "success",
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    animation:true,
                    customClass: {
                        timerProgressBar: 'customeProgressBar',
                    },
                    willClose: () => {
                        // Change the URL to the desired destination
                        window.location.href = 'course_subject.php';
                    }
                });
            }else{
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "error",
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    animation:true,
                    customClass: {
                        timerProgressBar: 'customeProgressBar',
                    }
                });
            }
        },
        error: function (error) {
            // Handle the error here
            console.error(error);
        }
    });
});

function updateEndOptions() {
    var startYear = parseInt(document.getElementById("addSyStart").value);
    var endDropdown = document.getElementById("addSyEnd");

    // Clear existing options
    endDropdown.innerHTML = "";

    // Add the selected value in "Start"
    var optionStart = document.createElement("option");
    optionStart.value = startYear;
    optionStart.text = startYear;
    endDropdown.add(optionStart);

    // Add the selected value in "Start" plus one year
    var optionEnd = document.createElement("option");
    optionEnd.value = startYear + 1;
    optionEnd.text = startYear + 1;
    endDropdown.add(optionEnd);
}

$('#addCourseSubjectModal').on('shown.bs.modal', function (e) {
    updateEndOptions();
});
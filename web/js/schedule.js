$(document).ready(function () {
    $('#examsScheduleCheck, #preparingScheduleCheck').on('change', function () {
        let examsCheck = $('#examsScheduleCheck').prop('checked');
        let preparingCheck = $('#preparingScheduleCheck').prop('checked');
        $.ajax({
            url: 'get-schedule-exams-by-type',
            method: 'post',
            type: 'json',
            data: {
                'exams': examsCheck,
                'preparing': preparingCheck
            },
            success: function (data) {
                let schedule = JSON.parse(data);
                initCalendar(schedule);
            }
        });
    });
});

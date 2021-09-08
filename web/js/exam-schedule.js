$(document).ready(function () {
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

    $('#clear-inputs').on('click', function () {
        $('#examName').val('');
        $('#examDate').val('');
        $('#preparingDays').val('');
    });

    $('#createSchedulePreparing').on('click', function () {
        $.ajax({
            url: 'create-schedule',
            success: function (data) {
                $('#schedule-preparing').html(data);
            }
        });
    })
});

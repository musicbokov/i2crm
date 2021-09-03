function initCalendar(schedule)
{
    let options = {};
    options.viewRender = function () {
        console.log('test');
        $.each(JSON.parse(schedule), function (date, exam) {
            let cellHtml = '<div class="fc-day-cell">' +
                '<h6>Экзамен:</h6>' +
                '<p><b>' + exam + '</b></p>' +
                '</div>'
            $('.fc-day[data-date="' + date + '"]').html(cellHtml);
        });
    }

    $('#calendar').fullCalendar({});
}

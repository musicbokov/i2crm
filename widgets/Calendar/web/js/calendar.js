function initCalendar(schedule)
{
    $('#calendar').replaceWith('<div id="calendar"></div>');
    let options = {};
    options.viewRender = function () {
        $.each(schedule, function (date, exam) {
            let div = exam.scheduleType === 'Экзамен' ?
                '<div class="fc-day-cell fc-day-exam">' :
                '<div class="fc-day-cell">';

            let cellHtml = div +
                '<h6>' + exam.scheduleType + ':</h6>' +
                '<p><b>' + exam.examName + '</b></p>' +
                '</div>'

            $('.fc-day[data-date="' + date + '"]').html(cellHtml);
        });
    }

    $('#calendar').fullCalendar(options);
}

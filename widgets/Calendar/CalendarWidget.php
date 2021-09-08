<?php

namespace app\widgets\Calendar;

use yii\base\Widget;

/**
 * Class CalendarWidget
 * Виджет "Календарь с расписанием экзаменов и дней подготовки к ним
 * @package app\widgets\Calendar
 */
class CalendarWidget extends Widget
{
    /**
     * Рендер виджета
     * @return string
     */
    public function run()
    {
        return $this->render('calendar');
    }
}

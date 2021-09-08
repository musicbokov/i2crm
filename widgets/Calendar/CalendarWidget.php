<?php

namespace app\widgets\Calendar;

use yii\base\Widget;

/**
 * Class CalendarWidget
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

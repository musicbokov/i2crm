<?php

namespace app\widgets\Calendar\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class CalendarAsset
 * Подключение внешних файлов для виджета "Календарь с расписанием"
 * @package app\widgets\Calendar\assets
 */
class CalendarAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/widgets/Calendar/web/';

    /**
     * @var string[]
     */
    public $css = [
        'css/fullcalendar/fullcalendar.css',
        'css/calendar.css'
    ];

    /**
     * @var string[]
     */
    public $js = [
        'js/moment/moment.js',
        'js/moment/ru.js',
        'js/fullcalendar/fullcalendar.js',
        'js/fullcalendar/ru.js',
        'js/calendar.js',
    ];

    /**
     * @var string[]
     */
    public $depends = [
        JqueryAsset::class,
    ];
}

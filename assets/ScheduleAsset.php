<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class ScheduleAsset
 * Подключение внешних файлов для календаря с расписанием
 * @package app\assets
 */
class ScheduleAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $basePath = '@webroot';
    /**
     * @var string
     */
    public $baseUrl = '@web';

    /**
     * @var array
     */
    public $js = [
        'js/schedule.js',
    ];

    /**
     * @var string[]
     */
    public $depends = [
        JqueryAsset::class,
    ];
}

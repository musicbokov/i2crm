<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class ExamScheduleAsset
 * Подключение внешних файлов для планировщика
 * @package app\assets
 */
class ExamScheduleAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/exam-schedule.js',
    ];
    public $depends = [
        JqueryAsset::class,
    ];
}

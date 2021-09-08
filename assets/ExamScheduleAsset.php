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
    /**
     * @var string
     */
    public $basePath = '@webroot';
    /**
     * @var string
     */
    public $baseUrl = '@web';
    /**
     * @var string[]
     */
    public $js = [
        'js/exam-schedule.js',
    ];
    /**
     * @var string[]
     */
    public $depends = [
        JqueryAsset::class,
    ];
}

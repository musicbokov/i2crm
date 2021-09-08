<?php

/**
 * @var $this View;
 * @var array $schedule
 */

use app\widgets\Calendar\assets\CalendarAsset;
use yii\web\View;

CalendarAsset::register($this);
?>

<div id="calendar"></div>

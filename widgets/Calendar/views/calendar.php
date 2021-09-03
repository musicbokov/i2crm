<?php

/**
 * @var $this View;
 */

use app\widgets\Calendar\assets\CalendarAsset;
use yii\web\View;

CalendarAsset::register($this);
?>
<div id="calendar"></div>

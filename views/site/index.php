<?php

/* @var $this yii\web\View */

use app\widgets\Calendar\CalendarWidget;

$this->title = 'My Yii Application';
?>

<?php
echo CalendarWidget::widget();
?>
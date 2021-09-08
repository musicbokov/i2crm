<?php

/**
 * @var $this View;
 * @var array $examsSchedule Сформированное расписание
 * @var string $title Заголовок страницы
 */

use app\assets\ScheduleAsset;
use app\widgets\Calendar\CalendarWidget;
use yii\helpers\Html;
use yii\web\View;

$this->title = $title;
ScheduleAsset::register($this);

$this->registerJs("var examsSchedule = '" . json_encode($examsSchedule) . "';", View::POS_BEGIN);
?>

<div class="form-group">
    <div class="custom-control custom-switch">
        <?=
        Html::checkbox('examsScheduleCheck', false, [
            'id' => 'examsScheduleCheck',
            'class' => 'custom-control-input',
        ]);
        ?>
        <label class="custom-control-label" for="examsScheduleCheck">Расписание дней проведения экзаменов</label>
    </div>
</div>
<div class="form-group">
    <div class="custom-control custom-switch">
        <?=
        Html::checkbox('preparingScheduleCheck', false, [
            'id' => 'preparingScheduleCheck',
            'class' => 'custom-control-input',
        ]);
        ?>
        <label class="custom-control-label" for="preparingScheduleCheck">Расписание дней для подготовки</label>
    </div>
</div>

<?php echo CalendarWidget::widget(); ?>


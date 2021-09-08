<?php

/**
 * @var $this yii\web\View
 * @var ExamScheduleModel $examScheduleModel Модель для работы с расписанием
 * @var string $title Заголовок страницы
 * @var string $errorMessage Сообщение об ошибке
 */

use app\assets\ExamScheduleAsset;
use app\models\ExamScheduleModel;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $title;
ExamScheduleAsset::register($this);
?>

<?php $form = ActiveForm::begin([
    'method' => 'POST',
    'id' => 'schedulerForm'
]); ?>
<?php
echo $form->errorSummary(
    $examScheduleModel,
    [
        'class' => 'text-danger',
        'header' => ' <h5> <i class="ace-icon fa fa-exclamation-triangle"></i>&nbsp;&nbsp;' .
            'Найдены ошибки при заполнении формы:' .
            '</h5>'
    ]
); ?>
<?php if (!empty($errorMessage)) : ?>
    <div class="alert alert-danger">
        <p style="text-align: center"><?= $errorMessage ?></p>
    </div>
<?php endif; ?>
<div class="form-group">
    <label> <?= $examScheduleModel->getAttributeLabel('examName') ?>: </label>
    <?= Html::activeTextInput($examScheduleModel, 'examName', [
        'class' => 'form-control',
        'placeholder' => 'Введите название экзамена',
        'id' => 'examName',
        'autocomplete' => 'off',
    ]); ?>
</div>
<div class="form-group">
    <lavel> <?= $examScheduleModel->getAttributeLabel('examDate') ?>: </lavel>
    <?= Html::activeTextInput($examScheduleModel, 'examDate', [
        'class' => 'form-control',
        'type' => 'date',
        'placeholder' => 'Выберите дату проведения экзамена',
        'id' => 'examDate',
        'autocomplete' => 'off',
    ]); ?>
</div>
<div class="form-group">
    <label> <?= $examScheduleModel->getAttributeLabel('preparingDays') ?>:</label>
    <?= Html::activeTextInput($examScheduleModel, 'preparingDays', [
        'class' => 'form-control',
        'type' => 'number',
        'placeholder' => 0,
        'id' => 'preparingDays',
        'autocomplete' => 'off',
    ]); ?>
</div>
<div class="form-group">
    <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']); ?>
    <?=
    Html::button('Рассчитать', [
        'class' => 'btn btn-success',
        'id' => 'createSchedulePreparing',
    ]); ?>
    <?= Html::button('Очистить', ['id' => 'clear-inputs', 'class' => 'btn btn-light']) ?>
</div>

<?php ActiveForm::end(); ?>

<div id="schedule-preparing"></div>
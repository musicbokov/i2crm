<?php

/**
 * @var $this yii\web\View
 * @var string $title Заголовок страницы
 * @var array $dataProvider
 */

use app\components\entity\Schedule;
use app\components\entity\ScheduleTypes;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

$this->title = $title;
?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute'=>'id',
                'label' => 'ID',
            ],
            [
                'attribute'=>'name',
                'label' => 'Название',
            ],
            [
                'label' => 'Статус расписания подготовки',
                'content' => function ($model) {
                    $types = ArrayHelper::getColumn($model->schedule, 'type_id');
                    return ArrayHelper::isIn(ScheduleTypes::TYPE_PREPARING, $types) ?
                        Schedule::STATUS_DONE :
                        Schedule::STATUS_NOT_DONE;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'headerOptions' => ['width' => '30'],
                'template' => '{delete}',
            ],
        ],
    ]); ?>

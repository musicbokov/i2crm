<?php

/**
 * @var ArrayDataProvider $dataProvider Сформированное расписание дней для подготовки к экзаменам
 */

use yii\data\ArrayDataProvider;
use yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        'datePreparing'
    ],
]);

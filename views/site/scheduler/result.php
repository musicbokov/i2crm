<?php

/**
 * @var DataProvider $dataProvider
 */

use yii\debug\models\timeline\DataProvider;
use yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        'datePreparing'
    ],
]);
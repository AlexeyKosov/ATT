<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Letters\Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Letters';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letters-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Letters', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
//            'content:ntext',
            'type',
            'order',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

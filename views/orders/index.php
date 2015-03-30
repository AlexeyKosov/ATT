<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Orders;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Orders\Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Orders', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'imei',
            'make',
            'phone_model',
            [
                'label' =>'Этап',
                'value' => function(Orders $model) { return $model->getStageName();},
            ],
            'request_number',
            'additional_info',
            // 'date_create',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

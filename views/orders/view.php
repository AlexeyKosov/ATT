<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'imei',
            'make',
            'phone_model',
            [
                'label' =>'Этап',
                'value' =>  $model->getStageName(),
            ],
            'date_create',
            'request_number',
            [
                'label' =>'Доп. инфо',
                'format' =>'raw',
                'value' =>  $model->additional_info,
            ],
        ],
    ]) ?>

    <?= $this->render('//letters/_index', [
        'modelList' => $model->getLetterList(),
    ]); ?>

</div>

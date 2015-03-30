<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Letters */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Letters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letters-view">

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
            [
                'label' =>'type',
                'value' =>  $model->getName(),
            ],
            [
                'label' =>'Сожержимое',
                'format' => 'raw',
                'value' =>  $model->content,
            ],
            [
                'label' =>'Заказ',
                'format' => 'raw',
                'value' =>  '<a href="' . $model->getOrderLink() . '">' . $model->order . '</a>',
            ],
        ],
    ]) ?>

</div>

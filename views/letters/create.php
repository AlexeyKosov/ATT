<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Letters */

$this->title = 'Create Letters';
$this->params['breadcrumbs'][] = ['label' => 'Letters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letters-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

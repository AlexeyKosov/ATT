<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Orders\OrdersNameProvider;
use app\models\Orders;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */
/* @var $form yii\widgets\ActiveForm */
/* @var $captchaContent string */
/* @var $needCaptcha bool */
/* @var $availableStage bool */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'imei')->textInput(['maxlength' => 15]) ?>
    <? if ($availableStage): ?>
        <?= $form->field($model, 'stage')->textInput(['maxlength' => 15]) ?>
        <ul>
            <? for ($i = Orders::SENT_FAIL_DATA_TO_CLIENT; $i <= Orders::SENT_SUCCESS_DATA_TO_CLIENT; $i++):?>
                <li><?= $i . ' - ' . OrdersNameProvider::getNameByType($i);?></li>
            <? endfor; ?>
        </ul>
    <? endif; ?>

    <? if ($needCaptcha): ?>
        <img src="data:image/jpeg;base64,<?= $captchaContent; ?>" style="border: 1px solid">

        <?= $form->field($model, 'captchaId')->hiddenInput() ?>

        <?= $form->field($model, 'captchaResult')->textInput(['maxlength' => 15]) ?>
    <? endif; ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

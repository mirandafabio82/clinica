<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TratamentoRealizado */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tratamento-realizado-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_agendamento')->textInput() ?>

    <?= $form->field($model, 'dente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tratamento_realizado')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

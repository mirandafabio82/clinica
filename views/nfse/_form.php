<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Nfse */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nfse-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'processo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nota_fiscal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_emissao')->textInput() ?>

    <?= $form->field($model, 'data_entrega')->textInput() ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pendencia')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'nf_devolvida')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comentario_devolucao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'data_pagamento')->textInput() ?>

    <?= $form->field($model, 'usuario_pendencia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cnpj_emitente')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

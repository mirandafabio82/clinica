<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\NfseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nfse-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'processo') ?>

    <?= $form->field($model, 'nota_fiscal') ?>

    <?= $form->field($model, 'data_emissao') ?>

    <?= $form->field($model, 'data_entrega') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'pendencia') ?>

    <?php // echo $form->field($model, 'nf_devolvida') ?>

    <?php // echo $form->field($model, 'comentario_devolucao') ?>

    <?php // echo $form->field($model, 'data_pagamento') ?>

    <?php // echo $form->field($model, 'usuario_pendencia') ?>

    <?php // echo $form->field($model, 'cnpj_emitente') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

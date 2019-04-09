<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\PagamentoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pagamento-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nota_fiscal') ?>

    <?= $form->field($model, 'tipo_documento') ?>

    <?= $form->field($model, 'data_emissao') ?>

    <?= $form->field($model, 'data_lancamento') ?>

    <?php // echo $form->field($model, 'data_pagamento') ?>

    <?php // echo $form->field($model, 'valor_bruto') ?>

    <?php // echo $form->field($model, 'rentencoes') ?>

    <?php // echo $form->field($model, 'abatimentos') ?>

    <?php // echo $form->field($model, 'valor_liquido') ?>

    <?php // echo $form->field($model, 'forma_pagamento') ?>

    <?php // echo $form->field($model, 'conta') ?>

    <?php // echo $form->field($model, 'documento_contabil') ?>

    <?php // echo $form->field($model, 'compensacao') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

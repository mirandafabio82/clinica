<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\FrsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="frs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'contrato') ?>

    <?= $form->field($model, 'pedido') ?>

    <?= $form->field($model, 'frs') ?>

    <?= $form->field($model, 'criador') ?>

    <?php // echo $form->field($model, 'data_criacao') ?>

    <?php // echo $form->field($model, 'aprovador') ?>

    <?php // echo $form->field($model, 'data_aprovacao') ?>

    <?php // echo $form->field($model, 'cnpj_emitente') ?>

    <?php // echo $form->field($model, 'valor') ?>

    <?php // echo $form->field($model, 'nota_fiscal') ?>

    <?php // echo $form->field($model, 'referencia') ?>

    <?php // echo $form->field($model, 'texto_breve') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\EscopoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="escopo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nome') ?>

    <?= $form->field($model, 'item') ?>

    <?= $form->field($model, 'descricao') ?>

    <?= $form->field($model, 'horas') ?>

    <?php // echo $form->field($model, 'executado') ?>

    <?php // echo $form->field($model, 'interno') ?>

    <?php // echo $form->field($model, 'criado') ?>

    <?php // echo $form->field($model, 'modificado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

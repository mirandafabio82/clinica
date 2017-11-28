<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\ConfigSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'vl_hh') ?>

    <?= $form->field($model, 'vl_km') ?>

    <?= $form->field($model, 'qtd_km_dia') ?>

    <?= $form->field($model, 'pasta') ?>

    <?php // echo $form->field($model, 'ultimo_bm') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

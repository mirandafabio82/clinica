<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\BmSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bm-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'projeto_id') ?>

    <?= $form->field($model, 'contrato') ?>

    <?= $form->field($model, 'objeto') ?>

    <?= $form->field($model, 'contratada') ?>

    <?php // echo $form->field($model, 'cnpj') ?>

    <?php // echo $form->field($model, 'contato') ?>

    <?php // echo $form->field($model, 'numero_bm') ?>

    <?php // echo $form->field($model, 'data') ?>

    <?php // echo $form->field($model, 'de') ?>

    <?php // echo $form->field($model, 'para') ?>

    <?php // echo $form->field($model, 'descricao') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

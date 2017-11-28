<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\DocumentoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="documento-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'projeto_id') ?>

    <?= $form->field($model, 'cliente_id') ?>

    <?= $form->field($model, 'nome') ?>

    <?= $form->field($model, 'revisao') ?>

    <?php // echo $form->field($model, 'data') ?>

    <?php // echo $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'criado') ?>

    <?php // echo $form->field($model, 'modificado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

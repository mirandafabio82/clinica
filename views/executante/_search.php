<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\ExecutanteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="executante-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'usuario_id') ?>

    <?= $form->field($model, 'tipo_executante_id') ?>

    <?= $form->field($model, 'nome') ?>

    <?= $form->field($model, 'cidade') ?>

    <?= $form->field($model, 'uf') ?>

    <?php // echo $form->field($model, 'cpf') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'telefone') ?>

    <?php // echo $form->field($model, 'celular') ?>

    <?php // echo $form->field($model, 'criado') ?>

    <?php // echo $form->field($model, 'modificado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

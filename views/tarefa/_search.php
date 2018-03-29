<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\TarefaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tarefa-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'projeto_id') ?>

    <?= $form->field($model, 'executante_id') ?>

    <?= $form->field($model, 'atividade_id') ?>

    <?= $form->field($model, 'data') ?>

    <?php // echo $form->field($model, 'horas_as') ?>

    <?php // echo $form->field($model, 'horas_executada') ?>

    <?php // echo $form->field($model, 'horas_acumulada') ?>

    <?php // echo $form->field($model, 'horas_saldo') ?>

    <?php // echo $form->field($model, 'descricao') ?>

    <?php // echo $form->field($model, 'criado') ?>

    <?php // echo $form->field($model, 'modificado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\TratamentoRealizadoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tratamento-realizado-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_tratamento') ?>

    <?= $form->field($model, 'id_agendamento') ?>

    <?= $form->field($model, 'dente') ?>

    <?= $form->field($model, 'tratamento_realizado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

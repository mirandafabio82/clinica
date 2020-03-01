<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\TratamentoPlanejamentoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tratamento-planejamento-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_tratamento_planejamento') ?>

    <?= $form->field($model, 'id_paciente') ?>

    <?= $form->field($model, 'primeira_opcao') ?>

    <?= $form->field($model, 'segunda_opcao') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

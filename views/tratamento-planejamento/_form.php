<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TratamentoPlanejamento */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tratamento-planejamento-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_paciente')->textInput() ?>

    <?= $form->field($model, 'primeira_opcao')->textInput() ?>

    <?= $form->field($model, 'segunda_opcao')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

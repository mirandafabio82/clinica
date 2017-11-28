<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Escopo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="escopo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item')->textInput() ?>

    <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'horas')->textInput() ?>

    <?= $form->field($model, 'executado')->textInput() ?>

    <?= $form->field($model, 'interno')->textInput() ?>

    <?= $form->field($model, 'criado')->textInput() ?>

    <?= $form->field($model, 'modificado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

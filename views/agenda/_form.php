<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Agenda */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agenda-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
    <div class="box-header with-border">
    <div class="row">
    <div class="col-md-4">    
        <?= $form->field($model, 'projeto_id')->textInput() ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'data')->textInput() ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'local')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'quem')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'assunto')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'hr_inicio')->textInput() ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'hr_final')->textInput() ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>
    </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

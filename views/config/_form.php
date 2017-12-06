<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Config */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
        <div class="box-header with-border">
    <?= $form->field($model, 'vl_hh')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vl_km')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'qtd_km_dia')->textInput() ?>

    <?= $form->field($model, 'pasta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ultimo_bm')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

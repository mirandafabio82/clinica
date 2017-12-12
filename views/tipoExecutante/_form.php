<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipoExecutante */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-executante-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
        <div class="box-header with-border">

    <?= $form->field($model, 'cargo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_hora')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_pago')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

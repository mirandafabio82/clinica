<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Contato */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contato-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'usuario_id')->textInput() ?>

    <?= $form->field($model, 'cliente_id')->textInput() ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tratamento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contato')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'setor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'criado')->textInput() ?>

    <?= $form->field($model, 'modificado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

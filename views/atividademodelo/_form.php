<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Atividademodelo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="atividademodelo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'escopopadrao_id')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'isPrioritaria')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'isEntregavel')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

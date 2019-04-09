<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Pagamento */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pagamento-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nota_fiscal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tipo_documento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_emissao')->textInput() ?>

    <?= $form->field($model, 'data_lancamento')->textInput() ?>

    <?= $form->field($model, 'data_pagamento')->textInput() ?>

    <?= $form->field($model, 'valor_bruto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rentencoes')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'abatimentos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_liquido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'forma_pagamento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'conta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'documento_contabil')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'compensacao')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

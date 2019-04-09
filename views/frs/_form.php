<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Frs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="frs-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'contrato')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pedido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'frs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'criador')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_criacao')->textInput() ?>

    <?= $form->field($model, 'aprovador')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_aprovacao')->textInput() ?>

    <?= $form->field($model, 'cnpj_emitente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nota_fiscal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'referencia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'texto_breve')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Atividade */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="atividade-form">
<div class="box box-primary">
        <div class="box-header with-border">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'projeto_id')->textInput() ?>

    <?= $form->field($model, 'executante_id')->textInput() ?>

    <?= $form->field($model, 'local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'acao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data')->textInput() ?>

    <?= $form->field($model, 'comentario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'solicitante')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hr_inicio')->textInput() ?>

    <?= $form->field($model, 'hr_final')->textInput() ?>

    <?= $form->field($model, 'hr100_inicio')->textInput() ?>

    <?= $form->field($model, 'hr100_final')->textInput() ?>

    <?= $form->field($model, 'horas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_hh')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_km')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_total')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'criado')->textInput() ?>

    <?= $form->field($model, 'modificado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Criar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
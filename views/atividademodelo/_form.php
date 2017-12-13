<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Atividademodelo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="atividademodelo-form">

    <?php $form = ActiveForm::begin(); ?>
     <div class="box box-primary">
    <div class="box-header with-border">
     
    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'escopopadrao_id')->dropDownList($listEscopo,['prompt'=>'Selecione um Escopo']) ?>
    <input type="checkbox" name="Atividademodelo[isPrioritaria]">Atividade Prioritária
    <br>
    <input type="checkbox" name="Atividademodelo[isEntregavel]">Atividade Entregável

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
</div>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Planta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="planta-form">
<div class="box box-primary">
        <div class="box-header with-border">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'site_id')->dropDownList($listSites,['prompt'=>'Selecione um Cliente']) ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
</div>
</div>

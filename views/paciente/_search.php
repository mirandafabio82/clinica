<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\PacienteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="paciente-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_paciente') ?>

    <?= $form->field($model, 'nome') ?>

    <?= $form->field($model, 'telefone') ?>

    <?= $form->field($model, 'celular') ?>

    <?= $form->field($model, 'nascimento') ?>

    <?php // echo $form->field($model, 'rg') ?>

    <?php // echo $form->field($model, 'cpf') ?>

    <?php // echo $form->field($model, 'profissao_empresa') ?>

    <?php // echo $form->field($model, 'cor_pele') ?>

    <?php // echo $form->field($model, 'indicacao') ?>

    <?php // echo $form->field($model, 'endereco') ?>

    <?php // echo $form->field($model, 'nacionalidade') ?>

    <?php // echo $form->field($model, 'naturalidade') ?>

    <?php // echo $form->field($model, 'estado_civil') ?>

    <?php // echo $form->field($model, 'nome_mae') ?>

    <?php // echo $form->field($model, 'nome_pai') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

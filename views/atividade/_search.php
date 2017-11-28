<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\AtividadeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="atividade-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'projeto_id') ?>

    <?= $form->field($model, 'executante_id') ?>

    <?= $form->field($model, 'local') ?>

    <?= $form->field($model, 'acao') ?>

    <?php // echo $form->field($model, 'data') ?>

    <?php // echo $form->field($model, 'comentario') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'solicitante') ?>

    <?php // echo $form->field($model, 'hr_inicio') ?>

    <?php // echo $form->field($model, 'hr_final') ?>

    <?php // echo $form->field($model, 'hr100_inicio') ?>

    <?php // echo $form->field($model, 'hr100_final') ?>

    <?php // echo $form->field($model, 'horas') ?>

    <?php // echo $form->field($model, 'valor_hh') ?>

    <?php // echo $form->field($model, 'valor_km') ?>

    <?php // echo $form->field($model, 'valor_total') ?>

    <?php // echo $form->field($model, 'criado') ?>

    <?php // echo $form->field($model, 'modificado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

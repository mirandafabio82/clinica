<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\ProjetoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="projeto-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'cliente_id') ?>

    <?= $form->field($model, 'contato_id') ?>

    <?= $form->field($model, 'escopo_id') ?>

    <?= $form->field($model, 'descricao') ?>

    <?php // echo $form->field($model, 'codigo') ?>

    <?php // echo $form->field($model, 'site') ?>

    <?php // echo $form->field($model, 'planta') ?>

    <?php // echo $form->field($model, 'municipio') ?>

    <?php // echo $form->field($model, 'uf') ?>

    <?php // echo $form->field($model, 'cnpj') ?>

    <?php // echo $form->field($model, 'tratamento') ?>

    <?php // echo $form->field($model, 'contato') ?>

    <?php // echo $form->field($model, 'setor') ?>

    <?php // echo $form->field($model, 'fone_contato') ?>

    <?php // echo $form->field($model, 'celular') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'documentos') ?>

    <?php // echo $form->field($model, 'proposta') ?>

    <?php // echo $form->field($model, 'rev_proposta') ?>

    <?php // echo $form->field($model, 'data_proposta') ?>

    <?php // echo $form->field($model, 'qtd_hh') ?>

    <?php // echo $form->field($model, 'vl_hh') ?>

    <?php // echo $form->field($model, 'total_horas') ?>

    <?php // echo $form->field($model, 'qtd_dias') ?>

    <?php // echo $form->field($model, 'qtd_km') ?>

    <?php // echo $form->field($model, 'vl_km') ?>

    <?php // echo $form->field($model, 'total_km') ?>

    <?php // echo $form->field($model, 'valor_proposta') ?>

    <?php // echo $form->field($model, 'valor_consumido') ?>

    <?php // echo $form->field($model, 'valor_saldo') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'pendencia') ?>

    <?php // echo $form->field($model, 'comentarios') ?>

    <?php // echo $form->field($model, 'data_entrega') ?>

    <?php // echo $form->field($model, 'cliente_fatura') ?>

    <?php // echo $form->field($model, 'site_fatura') ?>

    <?php // echo $form->field($model, 'municipio_fatura') ?>

    <?php // echo $form->field($model, 'uf_fatura') ?>

    <?php // echo $form->field($model, 'cnpj_fatura') ?>

    <?php // echo $form->field($model, 'criado') ?>

    <?php // echo $form->field($model, 'modificado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

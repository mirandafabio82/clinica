<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Projeto */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="projeto-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'cliente_id')->textInput() ?>

    <?= $form->field($model, 'contato_id')->textInput() ?>

    <?= $form->field($model, 'escopo_id')->textInput() ?>

    <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'planta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uf')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cnpj')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tratamento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contato')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'setor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fone_contato')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'documentos')->textInput() ?>

    <?= $form->field($model, 'proposta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rev_proposta')->textInput() ?>

    <?= $form->field($model, 'data_proposta')->textInput() ?>

    <?= $form->field($model, 'qtd_hh')->textInput() ?>

    <?= $form->field($model, 'vl_hh')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_horas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'qtd_dias')->textInput() ?>

    <?= $form->field($model, 'qtd_km')->textInput() ?>

    <?= $form->field($model, 'vl_km')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_km')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_proposta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_consumido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_saldo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pendencia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comentarios')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_entrega')->textInput() ?>

    <?= $form->field($model, 'cliente_fatura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'site_fatura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'municipio_fatura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uf_fatura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cnpj_fatura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'criado')->textInput() ?>

    <?= $form->field($model, 'modificado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

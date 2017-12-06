<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Projeto */
/* @var $form yii\widgets\ActiveForm */
?>

<?php 
$this->registerJs('
$("#projeto-cliente_id").change(function(ev){
    var id = $(this).val();    
    $.ajax({ 
        url: "index.php?r=projeto/preencheformcliente",
        data: {id: id},
        type: "POST",
        success: function(response){
           console.log(response);
           var resposta = $.parseJSON(response);
           $("#projeto-uf").val(resposta["uf"]);
           $("#projeto-municipio").val(resposta["cidade"]);
           $("#projeto-cnpj").val(resposta["cnpj"]);
           $("#projeto-site").val(resposta["site"]);
       },
       error: function(){
        console.log("failure");
    }
    });
});

$("#projeto-contato_id").change(function(ev){
    var id = $(this).val();    
    $.ajax({ 
        url: "index.php?r=projeto/preencheformcontato",
        data: {id: id},
        type: "POST",
        success: function(response){
           console.log(response);
           var resposta = $.parseJSON(response);
           $("#projeto-tratamento").val(resposta["tratamento"]);
           $("#projeto-setor").val(resposta["setor"]);
           $("#projeto-fone_contato").val(resposta["telefone"]);
           $("#projeto-celular").val(resposta["celular"]);
           $("#projeto-email").val(resposta["email"]);
       },
       error: function(){
        console.log("failure");
    }
    });
});
');
?>

<div class="projeto-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
        <div class="box-header with-border">
    <div class="row">    
    <div class="col-md-1"> 
    <?= $form->field($model, 'cliente_id')->dropDownList($listClientes,['prompt'=>'Selecione um Cliente']) ?>

    <?= $form->field($model, 'contato_id')->dropDownList($listContatos,['prompt'=>'Selecione um Contato']) ?>

    <?= $form->field($model, 'escopo_id')->dropDownList($listEscopo,['prompt'=>'Selecione um Escopo']) ?>

    <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>

</div>
        <div class="col-md-1">
    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'planta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uf')->textInput(['maxlength' => true]) ?>

    </div>
        <div class="col-md-1">
    <?= $form->field($model, 'municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cnpj')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tratamento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contato')->textInput(['maxlength' => true]) ?>

</div>
        <div class="col-md-1">
    <?= $form->field($model, 'setor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fone_contato')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

</div>
        <div class="col-md-1">
   
    <?= $form->field($model, 'proposta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rev_proposta')->textInput() ?>

    <?= $form->field($model, 'data_proposta')->textInput() ?>

    <?= $form->field($model, 'qtd_hh')->textInput() ?>
</div>
        <div class="col-md-1">

    <?= $form->field($model, 'vl_hh')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_horas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'qtd_dias')->textInput() ?>

    <?= $form->field($model, 'qtd_km')->textInput() ?>
</div>
        <div class="col-md-1">

    <?= $form->field($model, 'vl_km')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_km')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_proposta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_consumido')->textInput(['maxlength' => true]) ?>
</div>
        <div class="col-md-1">

    <?= $form->field($model, 'valor_saldo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pendencia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comentarios')->textInput(['maxlength' => true]) ?>
    </div>
        <div class="col-md-1">

    <?= $form->field($model, 'data_entrega')->textInput() ?>

    <?= $form->field($model, 'cliente_fatura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'site_fatura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'municipio_fatura')->textInput(['maxlength' => true]) ?>
</div>
        <div class="col-md-1">

    <?= $form->field($model, 'uf_fatura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cnpj_fatura')->textInput(['maxlength' => true]) ?>

    </div>
    </div>

     <?= $form->field($model, 'documentos[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

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
           // $("#projeto-site").val(resposta["site"]);
     },
     error: function(){
      console.log("failure");
    }
  });

  $.ajax({ 
    url: "index.php?r=projeto/preenchepreenchecontatos",
    data: {id: id},
    type: "POST",
    success: function(response){
     var resposta = $.parseJSON(response);
     console.log(resposta);
     var myOptions = resposta;

     $("#projeto-contato_id").children("option:not(:first)").remove();
     var mySelect = $("#projeto-contato_id");
     $.each(myOptions, function(val, text) {
      mySelect.append(
      $("<option></option>").val(text["id"]).html(text["nome"])
      );
    });
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
     $("#projeto-contato").val(resposta["contato"]);
   },
   error: function(){
    console.log("failure");
  }
});
});

$("#projeto-site").change(function(ev){
  var id = $(this).val();    
  $.ajax({ 
    url: "index.php?r=projeto/preencheformsite",
    data: {id: id},
    type: "POST",
    success: function(response){

     var resposta = $.parseJSON(response);
     console.log(resposta);
     var myOptions = resposta;

     $("#projeto-planta").children("option:not(:first)").remove();
     var mySelect = $("#projeto-planta");
     $.each(myOptions, function(val, text) {
      mySelect.append(
      $("<option></option>").val(text["id"]).html(text["nome"])
      );
    });
  },
  error: function(){
    console.log("failure");
  }
});
});
');
?>

<!-- mask so funciona com isso -->
<?php $this->head() ?>


<div class="projeto-form">

  <?php $form = ActiveForm::begin(); ?>
  <div class="box box-primary">
    <div class="box-header with-border">
      <div class="row">    
        <div class="col-md-2"> 
          <?= $form->field($model, 'projeto_nome_id')->dropDownList($listNomes,['prompt'=>'Selecione um Nome Projeto']) ?>

          <?= $form->field($model, 'cliente_id')->dropDownList($listClientes,['prompt'=>'Selecione um Cliente']) ?>

          <?php if($model->isNewRecord){ ?>
            <?= $form->field($model, 'contato_id')->dropDownList(['prompt'=>'Selecione um Contato']) ?>
          <?php } else{ ?>
            <?= $form->field($model, 'contato_id')->dropDownList($listContatos,['prompt'=>'Selecione um Contato']) ?>
           <?php } ?>

          <?= $form->field($model, 'site')->dropDownList($listSites,['prompt'=>'Selecione um Site']) ?>

          <?php if($model->isNewRecord){ ?>
              <?= $form->field($model, 'planta')->dropDownList(['prompt'=>'Selecione uma Planta']) ?>
            
           <?php } else{ ?>
              <?= $form->field($model, 'planta')->dropDownList($listPlantas,['prompt'=>'Selecione uma Planta']) ?>
           <?php } ?>

          <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>    
        </div>
        <div class="col-md-2">

          <?= $form->field($model, 'descricao')->textarea(['maxlength' => true]) ?>

          <?= $form->field($model, 'uf')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'municipio')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'cnpj')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'tratamento')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'contato')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">

          <?= $form->field($model, 'setor')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'fone_contato')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'proposta')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'rev_proposta')->textInput() ?>
        </div>
        <div class="col-md-2">

          <?= $form->field($model, 'data_proposta')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '99/99/9999',
            ]) ?>

            <?= $form->field($model, 'qtd_hh')->textInput() ?>

            <?= $form->field($model, 'vl_hh')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'total_horas')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'qtd_dias')->textInput() ?>

            <?= $form->field($model, 'qtd_km')->textInput() ?>
          </div>
          <div class="col-md-2">
            <?= $form->field($model, 'vl_km')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'total_km')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'valor_proposta')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'valor_consumido')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'valor_saldo')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'status')->dropDownList($listStatus) ?>
          </div>
          <div class="col-md-2">

            <?= $form->field($model, 'pendencia')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'comentarios')->textarea(['maxlength' => true]) ?>

            <?= $form->field($model, 'data_entrega')->widget(\yii\widgets\MaskedInput::className(), [
              'mask' => '99/99/9999',
              ]) ?>
            </div>

          </div>
          <HR SIZE=7>
          <h3>Fatura</h3>
          
            <div class="col-md-6">
            <div class="col-md-6">
              <?= $form->field($model, 'cliente_fatura')->textInput(['maxlength' => true]) ?>
              </div>
              <div class="col-md-6">
              <?= $form->field($model, 'site_fatura')->textInput(['maxlength' => true]) ?>
              </div>
            </div>
            <div class="col-md-6">            
              <div class="col-md-2">
                <?= $form->field($model, 'uf_fatura')->textInput(['maxlength' => true]) ?>
              </div>
              <div class="col-md-4">
                <?= $form->field($model, 'municipio_fatura')->textInput(['maxlength' => true]) ?>
              </div>
              <div class="col-md-4">
                <?= $form->field($model, 'cnpj_fatura')->textInput(['maxlength' => true]) ?>
              </div>
            </div>
            <div class="form-group">
              <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
          </div>
        </div>
        <?php ActiveForm::end(); ?>

      </div>

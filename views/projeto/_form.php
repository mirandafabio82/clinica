<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model app\models\Projeto */
/* @var $form yii\widgets\ActiveForm */
?>

<?php 
$this->registerJs('

  $( document ).ready(function() {
    console.log("aa");
    $("input").removeClass("form-control");
    $(".messages-inline").text("");
  });

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
       $("#projeto-codigo").val(resposta["codigo"]);
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

<style>
  .control-label{
    font-size:10px;
  }
</style>

<div class="projeto-form">

  <?php $form = ActiveForm::begin(); ?>
  <div class="form-group">
    <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Novo Projeto', ['create'], ['class' => 'btn btn-success']); ?>
    <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
  </div>
  <div class="box box-primary">
    <div class="box-header with-border">
      <div class="row">    
        <div class="col-md-2"> 
          <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>  
        </div>
        <div class="col-md-4">
          <?= $form->field($model, 'descricao')->textarea(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
          <?= $form->field($model, 'escopo_id')->dropDownList($listEscopo,['prompt'=>'Selecione um Escopo']) ?>
        </div>
        <div class="col-md-2">
          <?= $form->field($model, 'escopo_id')->dropDownList($listEscopo,['prompt'=>'Selecione uma Disciplina']) ?>
        </div>
        
      </div>
      <div class="row">
        <div class="col-md-2">
          <?= $form->field($model, 'cliente_id')->dropDownList($listClientes,['prompt'=>'Selecione um Cliente']) ?>
        </div>

        <div class="col-md-1">
          <?= $form->field($model, 'codigo')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>   
        </div>

        <div class="col-md-2">
          <?= $form->field($model, 'site')->dropDownList($listSites,['prompt'=>'Selecione um Site']) ?>
        </div>

        <div class="col-md-2">
          <?php if($model->isNewRecord){ ?>
          <?= $form->field($model, 'planta')->dropDownList(['prompt'=>'Selecione uma Área'])->label('Área') ?>

          <?php } else{ ?>
          <?= $form->field($model, 'planta')->dropDownList($listPlantas,['prompt'=>'Selecione uma Área'])->label('Área') ?>
          <?php } ?>
        </div>

        <div class="col-md-1" style="width: 1em">
          <?= $form->field($model, 'uf')->textInput(['maxlength' => true,'style'=>'width:2em']) ?>
        </div>

        <div class="col-md-2">
          <?= $form->field($model, 'municipio')->textInput(['maxlength' => true,'style'=>'margin-right:-2em']) ?>
        </div>
        <div class="col-md-1">
          <?= $form->field($model, 'cnpj')->textInput(['maxlength' => true]) ?>
        </div>

      </div>
      <div class="row">
       <div class="col-md-2">
        <?php if($model->isNewRecord){ ?>
        <?= $form->field($model, 'contato_id')->dropDownList(['prompt'=>'Selecione um Contato']) ?>
        <?php } else{ ?>
        <?= $form->field($model, 'contato_id')->dropDownList($listContatos,['prompt'=>'Selecione um Contato']) ?>
        <?php } ?>
      </div>

      <div class="col-md-1">
        <?= $form->field($model, 'tratamento')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>
      </div>

      <div class="col-md-2">
        <?= $form->field($model, 'contato')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'setor')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'fone_contato')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>
      </div>
    </div>

    <div class="row">
     <div class="col-md-2">
      <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-1" >
      <?= $form->field($model, 'documentos')->textInput(['maxlength' => true,'style'=>'width:2em']) ?>
    </div>
    <div class="col-md-2">
      <?= $form->field($model, 'proposta')->textInput(['maxlength' => true]) ?>        
    </div>
    <div class="col-md-2">
      <?= $form->field($model, 'rev_proposta')->textInput() ?>
    </div>
    <div class="col-md-1">
      <?= $form->field($model, 'data_proposta')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '99/99/9999',
        ])->textInput(['style'=>'width:6em']) ?>
      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'qtd_hh')->textInput(['style'=>'width:4em']) ?>
      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'vl_hh')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>
      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'total_horas')->textInput(['maxlength' => true]) ?>
      </div>
    </div>

    <div class="row">
      <div class="col-md-1">
        <?= $form->field($model, 'qtd_dias')->textInput(['style'=>'width:6em']) ?>
      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'qtd_km')->textInput(['style'=>'width:6em']) ?>
      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'vl_km')->textInput(['maxlength' => true, 'style'=>'width:6em']) ?>
      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'total_km')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'valor_proposta')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>

      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'valor_consumido')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>

      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'valor_saldo')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>

      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'status')->dropDownList($listStatus) ?>

      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'pendencia')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>

      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'data_pendencia')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '99/99/9999',])->textInput(['maxlength' => true,'style'=>'width:5em']) ?>
      </div>

    </div>

    <div class="row">
      <div class="col-md-10">
        <?= $form->field($model, 'comentarios')->textarea(['maxlength' => true]) ?>

      </div>
      <div class="col-md-2">

        <?= $form->field($model, 'data_entrega')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '99/99/9999',
          ]) ?>
        </div>

      </div>

      
      <div class="row">      
        
          <div class="col-md-6"> 
          <div class="col-md-12">
          <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            
            'export' => [
            'fontAwesome' => true
            ],
            'hover' => true,
            
            'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{update} {delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            'nome',
            [
            'attribute' => 'status',      
            'class' => 'kartik\grid\EditableColumn',        
            'format' => 'raw',
            'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
            'value' => function ($data) {

              $status = Yii::$app->db->createCommand('SELECT status, cor FROM projeto_status WHERE id='.$data->status)->queryOne();

              return '<span style="color:'.$status['cor'].' "><i class="fa fa-circle" aria-hidden="true"></i> '.$status['status'].'</span>';

            },
            ],
            [
            'attribute' => 'cliente_id',   
            'class' => 'kartik\grid\EditableColumn',           
            'format' => 'raw',
            'contentOptions' => ['style' => 'width:10em;'],
            'value' => function ($data) {

              $nome = Yii::$app->db->createCommand('SELECT nome FROM cliente WHERE id='.$data->cliente_id)->queryScalar();


              return $nome;

            },
            ],
            [
            'attribute' => 'contato_id',              
            'format' => 'raw',
            'contentOptions' => ['style' => 'width:10em;  min-width:10em;'],
            'value' => function ($data) {

              $nome = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.$data->contato_id)->queryScalar();                

              return $nome;

            },
            ],
            // 'descricao',
            'codigo',            
            'municipio',
            'uf',
            
            // 'tratamento',
            // 'contato',
            // 'setor',
            [
            'attribute' => 'fone_contato',
            'format' => 'raw',
            'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
            ],
            [
            'attribute' => 'celular',
            'format' => 'raw',
            'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
            ],
            'email:email',
           
            ],
            ]); ?>
            </div>
            </div>
            <div class="col-md-4"> 
            
          </div>

          <h4>Fatura</h4>
          <div class="col-md-2">
            <?= $form->field($model, 'cliente_fatura')->textInput(['maxlength' => true]) ?>
          </div>
          <div class="col-md-2">
            <?= $form->field($model, 'site_fatura')->textInput(['maxlength' => true]) ?>
          </div>           

          <div class="col-md-2">
            <?= $form->field($model, 'uf_fatura')->textInput(['maxlength' => true]) ?>
          </div>
          <div class="col-md-2">
            <?= $form->field($model, 'municipio_fatura')->textInput(['maxlength' => true]) ?>
          </div>
          <div class="col-md-2">
            <?= $form->field($model, 'cnpj_fatura')->textInput(['maxlength' => true]) ?>
          </div>
          </div>
        </div>
      </div>
    </div>


    <?php ActiveForm::end(); ?>


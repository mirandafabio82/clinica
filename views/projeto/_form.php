<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Escopo;
use yii\helpers\Url;
use kartik\money\MaskMoney;
/* @var $this yii\web\View */
/* @var $model app\models\Projeto */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.table-bordered > tbody > tr > td{
  padding-top: 0px !important;
  padding-bottom: 0px !important;
}
</style>
<?php 
$this->registerJs('

  $( document ).ready(function() {
    
    $("input").removeClass("form-control");
    $(".messages-inline").text("");
  });

  $("#Automação_checkbox").change(function(ev){
    if($(this).prop("checked")){
      $("#disciplina_Automação_div").removeAttr("hidden");
    }else{
      $("#disciplina_Automação_div").attr("hidden", "hidden");
    }
  });

  $("#Processo_checkbox").change(function(ev){
    if($(this).prop("checked")){
      $("#disciplina_Processo_div").removeAttr("hidden");
    }else{
      $("#disciplina_Processo_div").attr("hidden", "hidden");
    }
  });

  $("#Instrumentação_checkbox").change(function(ev){
    if($(this).prop("checked")){
      $("#disciplina_Instrumentação_div").removeAttr("hidden");
    }else{
      $("#disciplina_Instrumentação_div").attr("hidden", "hidden");
    }
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
       $("#projeto-site").val(resposta["site"]);
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

/*$("#projeto-site").change(function(ev){
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
});*/
');
?>
<?php
$this->registerJs("

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
          if(e.target == this)
              location.href = '" . Url::to(['projeto/update']) . "&id='+id;
        }
    });

");
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
        <input type="radio" name="Projeto[tipo]" value="A" checked> Autorização de Serviço<br>
        <input type="radio" name="Projeto[tipo]" value="P"> Proposta<br>
      </div>
        <div class="col-md-2"> 
          <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>  
        </div>
        <div class="col-md-4">
          <?= $form->field($model, 'descricao')->textarea(['maxlength' => true]) ?>
        </div>

        <div class="col-md-4">
        <b> Disciplinas </b>
        <br>
          <?php 

        foreach ($listDisciplina as $key => $disciplina) { 
          
          $existeDisciplina = '';
          if(!$model->isNewRecord)
            $existeDisciplina = Yii::$app->db->createCommand('SELECT escopopadrao_id FROM atividademodelo JOIN escopo ON escopo.atividademodelo_id=atividademodelo.id WHERE escopopadrao_id='.$key.' AND projeto_id='.$model->id)->queryScalar();
          ?>
          
           <label id="<?=$disciplina?>_checkbox"> <?=$disciplina?> </label>  
           
           <?php if(!$model->isNewRecord){ ?>
           <br>
            <div style="margin-left: 1em" id="disciplina_<?=$disciplina?>_div">
            <?php } else{?>
            <br>
            <div style="margin-left: 1em" id="disciplina_<?=$disciplina?>_div" >
            <?php } ?>
           <?php     

            foreach ($listEscopo as $key2 => $escopo) { 

              $existeEscopo = '';
              if(!$model->isNewRecord){
                $existeEscopo = Yii::$app->db->createCommand('SELECT escopopadrao_id FROM atividademodelo JOIN escopo ON escopo.atividademodelo_id=atividademodelo.id WHERE escopopadrao_id='.$key2.' AND projeto_id='.$model->id.' AND disciplina_id='.$key)->queryScalar();
              }

              ?>
              <?php if(!empty($existeEscopo)){ ?>
                <input type="checkbox" name="Escopos[<?=$disciplina."][".$key2?>]" value="<?= $key2?>" checked="1"><?= $escopo ?>
              <?php } else{ ?>
                <input type="checkbox" name="Escopos[<?=$disciplina."][".$key2?>]" value="<?= $key2?>"><?= $escopo ?>
              <?php } ?>
            <?php } ?>
            </div>
          
        <?php } ?>
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
          <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-2">
          
          <?= $form->field($model, 'planta')->textInput(['maxlength' => true]) ?>
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
      <!-- <div class="col-md-1">
        <?//= $form->field($model, 'qtd_hh')->textInput(['style'=>'width:4em']) ?>
      </div>
      <div class="col-md-1">
        <?//= $form->field($model, 'vl_hh')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>
      </div>
      <div class="col-md-2">
        <?//= $form->field($model, 'total_horas')->textInput(['maxlength' => true]) ?>
      </div>
    </div> -->

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
      </div>
      </div>
      <div class="row">
      <div class="col-md-2">
        <?= $form->field($model, 'total_km')->textInput(['maxlength' => true]) ?>
      </div>
      
      <div class="col-md-2">
        <?= $form->field($model, 'valor_proposta')->textInput(['maxlength' => true,'style'=>'width:1em'])->widget(MaskMoney::classname(), [
          'pluginOptions' => [
              'prefix' => 'R$ ',
              'thousands' => '.',
              'decimal' => ',',
              // 'suffix' => ' ¢',
              'allowNegative' => false

          ]
      ]); ?>

      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'valor_consumido')->textInput(['maxlength' => true,'style'=>'width:6em'])->widget(MaskMoney::classname(), [
          'pluginOptions' => [
              'prefix' => 'R$ ',
              'thousands' => '.',
              'decimal' => ',',
              // 'suffix' => ' ¢',
              'allowNegative' => false

          ]
      ]); ?>

      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'valor_saldo')->textInput(['maxlength' => true,'style'=>'width:6em'])->widget(MaskMoney::classname(), [
          'pluginOptions' => [
              'prefix' => 'R$ ',
              'thousands' => '.',
              'decimal' => ',',
              // 'suffix' => ' ¢',
              'allowNegative' => false

          ]
      ]); ?>

      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'status')->dropDownList($listStatus) ?>

      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'pendencia')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>

      </div>
      </div>
      
      <div class="row">
        <div class="col-md-2">
          <?= $form->field($model, 'data_pendencia')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '99/99/9999',])->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
          <?= $form->field($model, 'total_horas')->textInput(['maxlength' => true]) ?>
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

   <!-- <div class="row">
     <div class="col-md-12"> 
       <h4>Fatura</h4>
          <div class="col-md-2">
            <?//= $form->field($model, 'cliente_fatura')->textInput(['maxlength' => true]) ?>
          </div>
          <div class="col-md-2">
            <?//= $form->field($model, 'site_fatura')->textInput(['maxlength' => true]) ?>
          </div>           

          <div class="col-md-2">
            <?//= $form->field($model, 'uf_fatura')->textInput(['maxlength' => true]) ?>
          </div>
          <div class="col-md-2">
            <?//= $form->field($model, 'municipio_fatura')->textInput(['maxlength' => true]) ?>
          </div>
          <div class="col-md-2">
            <?//= $form->field($model, 'cnpj_fatura')->textInput(['maxlength' => true]) ?>
          </div>
        
      </div></div> -->
      <?= Html::submitButton($model->isNewRecord ? 'Add Escopo' : 'Add Escopo', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
      </div>
      </div>
      </div>
      <?php ActiveForm::end(); ?>

      <?php
      if(!$model->isNewRecord){ ?>
     <!-- escopo -->
     <div class="box box-primary">
    <div class="box-header with-border">
     <div class="form-group">
       <?php $form2 = ActiveForm::begin(); ?>
       <?= Html::submitButton('Salvar Escopo', ['class' =>'btn btn-primary']) ?>
     </div>
     <div class="col-md-12">
      Escopo  
      <br>
      <div class="row">
      <div class="col-md-2">
        <label> Descrição </label>
      </div>
      <div class="col-md-1" style="margin-left: -1em;margin-right: 2em">
        <label> Disciplina </label>
      </div>
      <div class="col-md-2" style="margin-left: -2em">
        <label> horas_TP </label>
      </div>
      <div class="col-md-2" style="margin-left: -2em">
        <label> horas_EJ </label>
      </div>
      <div class="col-md-2" style="margin-left: -2em">
        <label> horas_EP </label>
      </div>
      <div class="col-md-2" style="margin-left: -2em">
        <label> horas_ES </label>
      </div>
      <div class="col-md-2" style="margin-left: -2em">
        <label> horas_EE </label>
      </div>
      </div>

      <div style="height: 30em; overflow-y: scroll;">
        <?php foreach ($escopoArray as $key => $esc) { 
          $escopoModel =  Escopo::findOne($esc['id']);
        ?>
          <div class="row" >
                   
          <div class="col-md-2">
            <label> <?= $esc['descricao'] ?> </label>
          </div>
          <div class="col-md-1" style="margin-left: -1em;margin-right: 2em">
            <label> <?= Yii::$app->db->createCommand('SELECT disciplina.nome FROM disciplina JOIN atividademodelo ON atividademodelo.disciplina_id=disciplina.id WHERE atividademodelo.id='.$esc['atividademodelo_id'])->queryScalar() ?> </label>
          </div>
          <div class="col-md-2" style="margin-left: -2em">
            <?= $form2->field($escopoModel, 'horas_tp')->textInput(['style'=>'', 'name' => 'Escopo['.$esc["id"].'][horas_tp]'])->label(false) ?> 
            <?= $form2->field($escopoModel, 'exe_tp_id')->dropDownList($listExecutantes_tp,['prompt'=>'Selecione um Executante', 'name' => 'Escopo['.$esc["id"].'][exe_tp_id]', 'value'=>$esc['exe_tp_id']])->label(false) ?>          
          </div>
          <div class="col-md-2" style="margin-left: -2em">
             <?= $form2->field($escopoModel, 'horas_ej')->textInput(['style'=>'', 'name' =>'Escopo['.$esc["id"].'][horas_ej]'])->label(false) ?> 
             <?= $form2->field($escopoModel, 'exe_ej_id')->dropDownList($listExecutantes_ej,['prompt'=>'Selecione um Executante', 'name' => 'Escopo['.$esc["id"].'][exe_ej_id]', 'value'=>$esc['exe_ej_id']])->label(false) ?>         
          </div>
          <div class="col-md-2" style="margin-left: -2em">
             <?= $form2->field($escopoModel, 'horas_ep')->textInput(['style'=>'', 'name' => 'Escopo['.$esc["id"].'][horas_ep]'])->label(false) ?>
             <?= $form2->field($escopoModel, 'exe_ep_id')->dropDownList($listExecutantes_ep,['prompt'=>'Selecione um Executante', 'name' => 'Escopo['.$esc["id"].'][exe_ep_id]', 'value'=>$esc['exe_ep_id']])->label(false) ?>          
          </div>
          <div class="col-md-2" style="margin-left: -2em">
             <?= $form->field($escopoModel, 'horas_es')->textInput(['style'=>'', 'name' => 'Escopo['.$esc["id"].'][horas_es]'])->label(false) ?>
             <?= $form2->field($escopoModel, 'exe_es_id')->dropDownList($listExecutantes_es,['prompt'=>'Selecione um Executante', 'name' => 'Escopo['.$esc["id"].'][exe_es_id]', 'value'=>$esc['exe_es_id']])->label(false) ?>          
          </div>
          <div class="col-md-2" style="margin-left: -2em">
             <?= $form2->field($escopoModel, 'horas_ee')->textInput(['style'=>'', 'name' => 'Escopo['.$esc["id"].'][horas_ee]'])->label(false) ?>
             <?= $form2->field($escopoModel, 'exe_ee_id')->dropDownList($listExecutantes_ee,['prompt'=>'Selecione um Executante', 'name' => 'Escopo['.$esc["id"].'][exe_ee_id]', 'value'=>$esc['exe_ee_id']])->label(false) ?>          
          </div>
          </div>
          <br>

      <?php } ?>
      </div>
      </div>
      </div>
    </div>

    <?php ActiveForm::end(); ?>
      <?php } ?>

    <div class="box box-primary">
    <div class="box-header with-border">
      <div class="row">   
          <div class="col-md-12"> 
          <div class="col-md-12">
          Projetos
          <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,        
            'options' => ['style' => 'font-size:12px;'],                
            'hover' => true,            
            'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
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

              'editableOptions' => [
              'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
              'data' => $listStatus                
              ]
            ],
            [
            'attribute' => 'cliente_id',   
            'format' => 'raw',
            'contentOptions' => ['style' => 'width:10em;'],
            'value' => function ($data) {
               $nome = '';
              if(isset($data->cliente_id) && !empty($data->cliente_id))
                $nome = Yii::$app->db->createCommand('SELECT nome FROM cliente WHERE id='.$data->cliente_id)->queryScalar();


              return $nome;

            },
            ],
            [
            'attribute' => 'contato_id',              
            'format' => 'raw',
            'contentOptions' => ['style' => 'width:10em;  min-width:10em;'],
            'value' => function ($data) {
              $nome = '';
              if(isset($data->contato_id) && !empty($data->contato_id))
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

            </div>
            </div>
            
          </div>
          
         </div>
        </div>
      </div>
    </div>
    

    


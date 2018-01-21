<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Escopo;
use yii\helpers\Url;
use kartik\money\MaskMoney;
use kartik\tabs\TabsX;
use kartik\popover\PopoverX;
use yii\bootstrap\Modal;
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

$(".ld-create").click(function(ev){              
    $.get("index.php?r=ldpreliminar/create",function(data){      
            $("#modal").modal("show").find("#modalContent").html(data);
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
    $('.poptp').change(function(){
      var linha = this.name.split(']')[0];
      linha = linha.split('[')[1];
      console.log(linha);
     $(\"#escopotp-\"+linha).val($(this).val());
      console.log(this.name);
    });

    $('.popej').change(function(){
      var linha = this.name.split(']')[0];
      linha = linha.split('[')[1];
      console.log(linha);
     $(\"#escopoej-\"+linha).val($(this).val());
      console.log(this.name);
    });

    $('.popep').change(function(){
      var linha = this.name.split(']')[0];
      linha = linha.split('[')[1];
      console.log(linha);
     $(\"#escopoep-\"+linha).val($(this).val());
      console.log(this.name);
    });

    $('.popes').change(function(){
      var linha = this.name.split(']')[0];
      linha = linha.split('[')[1];
      console.log(linha);
     $(\"#escopoes-\"+linha).val($(this).val());
      console.log(this.name);
    });

    $('.popee').change(function(){
      var linha = this.name.split(']')[0];
      linha = linha.split('[')[1];
      console.log(linha);
     $(\"#escopoee-\"+linha).val($(this).val());
      console.log(this.name);
    });

    $('#w0 td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
          if(e.target == this)
              location.href = '" . Url::to(['projeto/update']) . "&id='+id;
        }
    });

    $('#w185 td').click(function (e) {
      var id = $(this).closest('tr').attr('data-key');
        if(id != null){
          if(e.target == this)
              $.get('index.php?r=ldpreliminar/update&id='+id,function(data){      
                  $('#modal').modal('show').find('#modalContent').html(data);
              });
        }
        
    });

");
?>
<!-- mask so funciona com isso -->
<?php $this->head() ?>

<?php 
Modal::begin(['header' => '<h4>LD-Preliminar</h4>', 'id' => 'modal', 'size' => 'modal-lg',]);
  echo '<div id="modalContent"></div>';
  Modal::end();
  ?>
<style>
  .form-group{
    margin-bottom: 0;

  }

  .help-block {    
     margin-bottom: 0; 
  }

  .control-label{
    font-size:10px;
  }

#tabela-escopo {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#tabela-escopo td, #tabela-escopo th {
    border: 1px solid #ddd;
    padding-left: 8px;
}

#tabela-escopo tr:nth-child(even){background-color: #f2f2f2;}

#tabela-escopo tr:hover {background-color: #ddd;}

#tabela-escopo th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #337ab7;
    color: white;
}

/*thead {   
    display:table;
   
    width:100%;
}
tbody {
    height:350px;
    overflow:auto;
    overflow-x:hidden;
    display:block;
    
}
*/


</style>
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
<div class="projeto-form">

  <?php $form = ActiveForm::begin(); ?>
  <div class="form-group">
    <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Novo Projeto', ['create'], ['class' => 'btn btn-success']); ?>
    <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
  </div>
  <div class="box box-primary">
    <div class="box-header with-border">
    <div style="border:1px solid black;padding: 2px; margin-bottom: 1em">
    <div class="col-md-4">
        <b> Executantes </b>
    </div>    
    <br>
       <div class="row">    
    <?php 

        $existeExecutante = '';
    foreach ($listExecutantes as $key => $exe) { 

              if(!$model->isNewRecord){
                $existeExecutante = Yii::$app->db->createCommand('SELECT executante_id FROM projeto_executante WHERE executante_id='.$key.' AND projeto_id='.$model->id)->queryScalar();
              }
      ?>     
      <div class="col-md-2"> 
      <?php if(!empty($existeExecutante)){ ?>
        <input type="checkbox" name="ProjetoExecutante[<?=$key ?>]" value="<?= $key?>" checked> <?= $exe ?>
      <?php }  else{ ?>
        <input type="checkbox" name="ProjetoExecutante[<?=$key ?>]" value="<?= $key?>" > <?= $exe ?>
      <?php } ?>
      </div>
    <?php } ?>
      </div>
      </div>
      <div class="row">    
      <div class="col-md-2"> 
      <?= $form->field($model, 'tipo')->radioList(array('A'=>'Autorização de Serviço','P'=>'Proposta')); ?>
        
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
        <div class="col-md-2">
          <?= $form->field($model, 'contrato')->textInput(['maxlength' => true]) ?>
        </div>
      </div>
    

    <div class="row">
      <div class="col-md-8">
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

      <?= Html::a('<span class="btn-label">Gerar Relatório</span>', ['gerarrelatorio', 'id' => $model->id], ['class' => 'btn btn-primary', 'target'=>'_blank', 'style'=> 'float:right']) ?>
  
     </div>


     
    <?php 
    $descricao ='';
    $disciplina = '';
    $horas_tp = '';
    $exe_tp_id = '';
    $horas_ej = '';

    $header = ' 
    <table style="width:100%" id="tabela-escopo">
    <col width="600">
    <thead>
        <tr>
          <th width="5em" style="text-align:center;">Descrição</th>
          <th>Qtd</>
          <th>FOR</>
          <th colspan="10" style="text-align:center;">Horas</th>
        </tr>
        <tr>
          <th style="width:4.3em;"></th>
          <th style="width:4.3em;"></th>
          <th style="width:4.3em;"></th>
          <th style="width:4.3em;">EE</th>
          <th style="width:4.3em;">ES</th>
          <th style="width:4.3em;">EP</th>
          <th style="width:4.3em;">EJ</th>
          <th style="width:4.3em;">TP</th>
          <th style="width:4.3em;"></th>
          <th style="width:4.3em;"></th>
          <th style="width:4.3em;"></th>
          <th style="width:4.3em;"></th>
          <th style="width:4.3em;">Total</th>
        </tr>
        </thead> ';

    $bodyA = '';
    $bodyP = '';
    $bodyI = '';
    $exe_tp ='';
    $exe_ej ='';
    $exe_ep ='';
    $exe_es ='';
    $exe_ee ='';
    foreach ($escopoArray as $key => $esc) { 
        $escopoModel =  Escopo::findOne($esc['id']);  
        if(!empty($exe_tp))
          $exe_tp = Yii::$app->db->createCommand('SELECT nome FROM executante WHERE id='.$esc['exe_tp_id'])->queryScalar();
        if(!empty($exe_ej))
          $exe_ej = Yii::$app->db->createCommand('SELECT nome FROM executante WHERE id='.$esc['exe_ej_id'])->queryScalar();
        if(!empty($exe_ep))
          $exe_ep = Yii::$app->db->createCommand('SELECT nome FROM executante WHERE id='.$esc['exe_ep_id'])->queryScalar();
        if(!empty($exe_es))
          $exe_es = Yii::$app->db->createCommand('SELECT nome FROM executante WHERE id='.$esc['exe_es_id'])->queryScalar();
        if(!empty($exe_ee))
          $exe_ee = Yii::$app->db->createCommand('SELECT nome FROM executante WHERE id='.$esc['exe_ee_id'])->queryScalar();

       //==============================COLUNAS========================================================
      $descricao = '<tr><td style="font-size: 10px">'.$esc['descricao'].'</td>'; 
      
      $disciplina = '<td style="font-size: 10px">'.Yii::$app->db->createCommand('SELECT disciplina.nome FROM disciplina JOIN atividademodelo ON atividademodelo.disciplina_id=disciplina.id WHERE atividademodelo.id='.$esc['atividademodelo_id'])->queryScalar().'</td>';

      $isEntregavel = Yii::$app->db->createCommand('SELECT isEntregavel FROM atividademodelo WHERE atividademodelo.id='.$esc['atividademodelo_id'])->queryScalar();
      $qtd='<td style="font-size: 10px; padding: 1px; background-color: #337ab7"></td>';
      $for='<td style="font-size: 10px; padding: 1px; background-color: #337ab7"></td>';

      if($isEntregavel){
        $qtd = '<td style="font-size: 10px; padding: 1px;">'.$form2->field($escopoModel, 'qtd')->textInput(['style'=>' width:4em', 'name' => 'Escopo['.$esc["id"].'][qtd]', 'type' => 'number'])->label(false).'</td>'; 
        $for = '<td style="font-size: 10px; padding: 1px;">'.$form2->field($escopoModel, 'for')->textInput(['style'=>' width:4em', 'name' => 'Escopo['.$esc["id"].'][for]'])->label(false).'</td>';
      }
      
      $contentTP = '<p class="text-justify">' .$form2->field($escopoModel, 'exe_tp_id')->dropDownList($listExecutantes_tp,['name' => 'Escopo['.$esc["id"].'][exe_tp_id]', 'value'=>$esc['exe_tp_id'], 'class'=> 'form-control poptp'])->label(false) .'</p>';
      $popTP = PopoverX::widget([
        'placement' => PopoverX::ALIGN_TOP,
        'content' => $contentTP,
        'toggleButton' => ['label'=>'<i class="fa fa-caret-up" aria-hidden="true"></i>', 'class'=>'btn btn-default', 'style'=>'padding:1px'],
      ]);

      $contentEJ = '<p class="text-justify">' .$form2->field($escopoModel, 'exe_ej_id')->dropDownList($listExecutantes_ej,['name' => 'Escopo['.$esc["id"].'][exe_ej_id]', 'value'=>$esc['exe_ej_id'], 'class'=> 'form-control popej'])->label(false) .'</p>';
      $popEJ = PopoverX::widget([
        'placement' => PopoverX::ALIGN_TOP,
        'content' => $contentEJ,
        'toggleButton' => ['label'=>'<i class="fa fa-caret-up" aria-hidden="true"></i>', 'class'=>'btn btn-default', 'style'=>'padding:1px'],
      ]);

      $contentEP = '<p class="text-justify">' .$form2->field($escopoModel, 'exe_ep_id')->dropDownList($listExecutantes_ep,['name' => 'Escopo['.$esc["id"].'][exe_ep_id]', 'value'=>$esc['exe_ep_id'], 'class'=> 'form-control popep'])->label(false) .'</p>';
      $popEP = PopoverX::widget([
        'placement' => PopoverX::ALIGN_TOP,
        'content' => $contentEP,
        'toggleButton' => ['label'=>'<i class="fa fa-caret-up" aria-hidden="true"></i>', 'class'=>'btn btn-default', 'style'=>'padding:1px'],
      ]);

      $contentES = '<p class="text-justify">' .$form2->field($escopoModel, 'exe_es_id')->dropDownList($listExecutantes_es,['name' => 'Escopo['.$esc["id"].'][exe_es_id]', 'value'=>$esc['exe_es_id'], 'class'=> 'form-control popes'])->label(false) .'</p>';
      $popES = PopoverX::widget([
        'placement' => PopoverX::ALIGN_TOP,
        'content' => $contentES,
        'toggleButton' => ['label'=>'<i class="fa fa-caret-up" aria-hidden="true"></i>', 'class'=>'btn btn-default', 'style'=>'padding:1px'],
      ]);

      $contentEE = '<p class="text-justify">' .$form2->field($escopoModel, 'exe_ee_id')->dropDownList($listExecutantes_ee,['name' => 'Escopo['.$esc["id"].'][exe_ee_id]', 'value'=>$esc['exe_ee_id'], 'class'=> 'form-control popee'])->label(false) .'</p>';
      $popEE = PopoverX::widget([
        'placement' => PopoverX::ALIGN_TOP,
        'content' => $contentEE,
        'toggleButton' => ['label'=>'<i class="fa fa-caret-up" aria-hidden="true"></i>', 'class'=>'btn btn-default', 'style'=>'padding:1px'],
      ]);

      $horas_tp = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_tp')->textInput(['style'=>'width:4em', 'name' => 'Escopo['.$esc["id"].'][horas_tp]', 'type' => 'number'])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popTP.'</div></td>'; 

      echo $form2->field($escopoModel, 'exe_tp_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_tp_id]','id' => 'escopotp-'.$esc["id"], 'type' => 'number','hidden'=>'hidden'])->label(false);

      $horas_ej = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_ej')->textInput(['style'=>'width:4em', 'name' =>'Escopo['.$esc["id"].'][horas_ej]', 'type' => 'number'])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popEJ.'</div></td>'; 

      echo $form2->field($escopoModel, 'exe_ej_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_ej_id]','id' => 'escopoej-'.$esc["id"], 'type' => 'number', 'hidden'=>'hidden'])->label(false);

      $horas_ep = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_ep')->textInput(['style'=>'width:4em', 'name' =>'Escopo['.$esc["id"].'][horas_ep]', 'type' => 'number'])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popEP.'</div></td>';

      echo $form2->field($escopoModel, 'exe_ep_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_ep_id]','id' => 'escopoep-'.$esc["id"], 'type' => 'number', 'hidden'=>'hidden'])->label(false);

      $horas_es = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_es')->textInput(['style'=>'width:4em', 'name' =>'Escopo['.$esc["id"].'][horas_es]', 'type' => 'number'])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popES.'</div></td>';

      echo $form2->field($escopoModel, 'exe_es_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_es_id]','id' => 'escopoes-'.$esc["id"], 'type' => 'number', 'hidden'=>'hidden'])->label(false);

      $horas_ee = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_ee')->textInput(['style'=>'width:4em', 'name' =>'Escopo['.$esc["id"].'][horas_ee]', 'type' => 'number'])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popEE.'</div></td>';

      echo $form2->field($escopoModel, 'exe_ee_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_ee_id]','id' => 'escopoee-'.$esc["id"], 'type' => 'number', 'hidden'=>'hidden'])->label(false);
      

      $disciplina_id = Yii::$app->db->createCommand('SELECT disciplina_id FROM atividademodelo WHERE id='.$esc['atividademodelo_id'])->queryScalar();

      


      $total = $esc["horas_tp"]+$esc["horas_ej"]+$esc["horas_ep"]+$esc["horas_es"]+$esc["horas_ee"];
      $total = '<td class="total-td['.$esc['id'].']" style="font-size: 12px">'.$total.'</div>';
      
      if($disciplina_id == 1){
        $bodyA .=  $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;  
      }
      if($disciplina_id == 2){
        $bodyP .=  $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
      }
      if($disciplina_id == 3){
        $bodyI .=  $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total; 
      }       
          
 } 
    $automacao = '<div style="height: 50em; overflow-y: scroll;">'.$header.''.$bodyA.'</table></div>';
    $processo = '<div style="height: 50em; overflow-y: scroll;">'.$header.''.$bodyP.'</table></div>';
    $instrumentacao = '<div style="height: 50em; overflow-y: scroll;">'.$header.''.$bodyI.'</table></div>';
    $as = '<div>'. $form->field($model, "nota_geral")->textArea() .'</div>';
    $resumo = '<div>'. $form->field($model, "resumo_escopo")->textArea() .'</div>
                <div>'. $form->field($model, "resumo_exclusoes")->textArea() .'</div>
                <div>'. $form->field($model, "resumo_premissas")->textArea() .'</div>
                <div>'. $form->field($model, "resumo_restricoes")->textArea() .'</div>
                <div>'. $form->field($model, "resumo_normas")->textArea() .'</div>
                <div>'. $form->field($model, "resumo_documentos")->textArea() .'</div>
                <div>'. $form->field($model, "resumo_itens")->textArea() .'</div>
                <div>'. $form->field($model, "resumo_prazo")->textArea() .'</div>
                <div>'. $form->field($model, "resumo_observacoes")->textArea() .'</div>
    ';

    $ld_preliminar = '<p>'.
        '<a class="btn btn-success ld-create">Novo</a>'.
    '</p>'.GridView::widget([
        'dataProvider' => $ldPreliminarDataProvider,
            'filterModel' => $ldPreliminarSearchModel,
            'pjax' => true,        
            'options' => ['style' => 'font-size:12px;'],                
            'hover' => true,            
        'columns' => [
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
              'buttons' => [
                'delete' => function ($url, $model) {
                  $url = str_replace('projeto','ldpreliminar', $url);
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'lead-delete'),
                    ]);
                },
            ]
            ],
            
            'id',
            'hcn',
            'cliente',
            'titulo',
        ],
    ]);


    if(!empty($bodyA))
      $visibleA = true;
    else
      $visibleA = false;
    if(!empty($bodyP))
      $visibleP = true;
    else
      $visibleP = false;
    if(!empty($bodyI))
      $visibleI = true;
    else
      $visibleI = false;


$items = [
[
    'label'=>'Automação',
    'content'=>$automacao,
    'active'=>true,
    'visible' => $visibleA
],
[
    'label'=>'Processo',
    'content'=>$processo,   
    'visible' => $visibleP     
],
[
    'label'=>'Instrumentação',
    'content'=>$instrumentacao, 
    'visible' => $visibleI       
],
[
    'label'=>'AS',
    'content'=>$as,        
],
[
    'label'=>'Resumo',
    'content'=>$resumo,        
],
[
    'label'=>'LD-Preliminar',
    'content'=>$ld_preliminar,        
],
/*[
    'label'=>'<i class="glyphicon glyphicon-king"></i> Disabled',
    'headerOptions' => ['class'=>'disabled']
],*/
]; 
?>
<div class="col-md-12">      

<?php
echo TabsX::widget([
    'items'=>$items,
    'position'=>TabsX::POS_ABOVE,
    'encodeLabels'=>false
]);
 ?>    
 
  </div>
  </div>
</div>

    <?php ActiveForm::end(); ?>
      <?php } ?>

   </div>
  </div>
</div>
</div>
    

  
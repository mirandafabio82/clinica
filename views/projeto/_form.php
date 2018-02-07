<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
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

.summary{
  display: none;
}

input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0; 
}
</style>
<?php 

$scroll='';
if(!$model->isNewRecord){
  $scroll = 'window.scrollTo(0, 1200);';
}

$this->registerJs('

  $( document ).ready(function() {
    '.$scroll.'

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

  $(".nao-prioritarios").click(function(ev){              
     
      $("#nao-prioritarios_div").removeAttr("hidden");    
      
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

$("#projeto-qtd_dias").focusout(function() {
    dias = this.value;

    $.ajax({ 
    url: "index.php?r=projeto/preenchekm",
    type: "POST",
    success: function(response){
     var resposta = $.parseJSON(response);     
     var qtd_km = resposta["qtd_km_dia"] * dias;
     var valor_km = qtd_km * resposta["vl_km"];
     
     $("#projeto-qtd_km").val(qtd_km);
     $("#projeto-vl_km").val(valor_km);


  },
  error: function(){
    console.log("failure");
  }
});

});

$(".horas").focusout(function() {
  id = this.className.split("[")[1];
  id = id.split("]")[0];

  tipo = this.id.split("_")[1];
  tipo = tipo.split("-")[0];

  disc = this.id.split("-")[1];
  disc = disc.split("-")[0];

  horas_ee = isNaN(parseInt($("#horas_ee-"+disc+"-"+id).val())) ? 0 : parseInt($("#horas_ee-"+disc+"-"+id).val());
  horas_es = isNaN(parseInt($("#horas_es-"+disc+"-"+id).val())) ? 0 : parseInt($("#horas_es-"+disc+"-"+id).val());
  horas_ep = isNaN(parseInt($("#horas_ep-"+disc+"-"+id).val())) ? 0 : parseInt($("#horas_ep-"+disc+"-"+id).val());
  horas_ej = isNaN(parseInt($("#horas_ej-"+disc+"-"+id).val())) ? 0 : parseInt($("#horas_ej-"+disc+"-"+id).val());
  horas_tp = isNaN(parseInt($("#horas_tp-"+disc+"-"+id).val())) ? 0 : parseInt($("#horas_tp-"+disc+"-"+id).val());

  
  

  var totalHoras = horas_ee + horas_es + horas_ep + horas_ej + horas_tp;
  var totalHorasGeral = horas_ee + horas_es + horas_ep + horas_ej + horas_tp;
  
    var tds = document.getElementsByTagName("td");
    for (var i = 0; i<tds.length; i++) {      
      if (tds[i].className == "total-td["+id+"]") {
        // tds[i].html(totalHoras);
        tds[i].innerHTML = totalHoras;
      }
    }

    if($(this).val()!=""){
  
      var tds = document.getElementsByTagName("th");
      for (var i = 0; i<tds.length; i++) {      
        if(tipo=="ee"){
          if(disc==1){
            if (tds[i].className == "sub-a-tot-ee-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
             if (tds[i].className == "sub-a-tot-ee") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-a-tot-ee") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
          }
          if(disc==2){
            if (tds[i].className == "sub-p-tot-ee-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
             if (tds[i].className == "sub-p-tot-ee") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-p-tot-ee") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
          }
          if(disc==3){
            if (tds[i].className == "sub-i-tot-ee-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
             if (tds[i].className == "sub-i-tot-ee") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-i-tot-ee") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
          }
        }
        if(tipo=="es"){
          if(disc==1){
            if (tds[i].className == "sub-a-tot-es-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
             if (tds[i].className == "sub-a-tot-es") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-a-tot-es") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }   
          }
          if(disc==2){
            if (tds[i].className == "sub-p-tot-es-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
             if (tds[i].className == "sub-p-tot-es") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-p-tot-es") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }   
          }
          if(disc==3){
            if (tds[i].className == "sub-i-tot-es-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
             if (tds[i].className == "sub-i-tot-es") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-i-tot-es") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }   
          }
        }
        if(tipo=="ep"){
          if(disc==1){
            if (tds[i].className == "sub-a-tot-ep-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
             if (tds[i].className == "sub-a-tot-ep") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-a-tot-ep") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }   
          }
          if(disc==2){
            if (tds[i].className == "sub-p-tot-ep-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
             if (tds[i].className == "sub-p-tot-ep") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-p-tot-ep") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }   
          }
          if(disc==3){
            if (tds[i].className == "sub-i-tot-ep-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
             if (tds[i].className == "sub-i-tot-ep") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-i-tot-ep") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }   
          }
        }
        if(tipo=="ej"){
          if(disc==1){
            if (tds[i].className == "sub-a-tot-ej-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
            if (tds[i].className == "sub-a-tot-ej") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-a-tot-ej") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }   
          }
          if(disc==2){
            if (tds[i].className == "sub-p-tot-ej-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
            if (tds[i].className == "sub-p-tot-ej") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-p-tot-ej") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }   
          }
          if(disc==3){
            if (tds[i].className == "sub-i-tot-ej-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
            if (tds[i].className == "sub-i-tot-ej") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-i-tot-ej") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }   
          }
        }
        if(tipo=="tp"){
          if(disc==1){
            if (tds[i].className == "sub-a-tot-tp-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
            if (tds[i].className == "sub-a-tot-tp") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-a-tot-tp") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
          }
          if(disc==2){
            if (tds[i].className == "sub-p-tot-tp-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
            if (tds[i].className == "sub-p-tot-tp") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-p-tot-tp") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
          }
          if(disc==3){
            if (tds[i].className == "sub-i-tot-tp-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
            if (tds[i].className == "sub-i-tot-tp") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            } 
            if (tds[i].className == "full-i-tot-tp") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML);
            }  
          }
        }
      }
    }

});

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
          <div class="col-md-12" style="overflow: auto;overflow-y: hidden;Height:?">
          <div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-folder-open"></i> Projetos </div>
<div style="margin-bottom:1em;margin-top: 1em">
    <?= Html::a('Mostrar Todos', ['/projeto/create', 'pagination' => true], ['class'=>'btn btn-primary grid-button']) ?>
</div>
          <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            // 'pjax' => true,        
            'options' => ['style' => 'font-size:12px;'],                
            // 'hover' => true,            
            'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            [
            'attribute' => 'nome', 
            'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
            ],
            [
              'attribute' => 'status', 
              'format' => 'raw',
              'value' => function ($data) {

                $status = Yii::$app->db->createCommand('SELECT status, cor FROM projeto_status WHERE id='.$data->status)->queryOne();

                return '<span style="color:'.$status['cor'].' "><i class="fa fa-circle" aria-hidden="true"></i> '.$status['status'].'</span>';

              }, 
            ],
            /*[
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
            ],*/
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
            [
            'attribute' => 'uf',  
            'contentOptions' => ['style' => 'width:1em;  min-width:1em;'],
            ],
            
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
    <!-- <?//= Html::a('<i class="glyphicon glyphicon-plus"></i> Novo Projeto', ['create'], ['class' => 'btn btn-success']); ?> -->
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
      <?= $form->field($model, 'tipo')->radioList(array('A'=>'Autorização de Serviço','P'=>'AS/Proposta')); ?>        
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
        <div class="col-md-8" style="margin-top: -4em;">
        <?= $form->field($model, 'desc_resumida')->textarea(['maxlength' => true]) ?>

      </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <?= $form->field($model, 'cliente_id')->dropDownList($listClientes,['prompt'=>'Selecione um Cliente']) ?>
        </div>

        <div class="col-md-1">
          <?= $form->field($model, 'codigo')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>   
        </div>

        <div class="col-md-1">
          <?= $form->field($model, 'site')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>
        </div>

        <div class="col-md-1">
          
          <?= $form->field($model, 'planta')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>
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
       <div class="col-md-4">
        <?php if($model->isNewRecord){ ?>
        <?= $form->field($model, 'contato_id')->dropDownList(['prompt'=>'Selecione um Contato']) ?>
        <?php } else{ ?>
        <?= $form->field($model, 'contato_id')->dropDownList($listContatos,['prompt'=>'Selecione um Contato']) ?>
        <?php } ?>
      </div>

      <div class="col-md-1">
        <?= $form->field($model, 'tratamento')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>
      </div>

      <div class="col-md-1">
        <?= $form->field($model, 'contato')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>
      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'setor')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>
      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'fone_contato')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>
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
    <div class="col-md-1">
      <?= $form->field($model, 'rev_proposta')->textInput(['maxlength' => true,'style'=>'width:3em']) ?>
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
        <?= $form->field($model, 'nota_geral')->textarea(['maxlength' => true]) ?>

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
      

      <?php 
          $nao_prioritarios = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE isPrioritaria=0')->queryAll();

      ?>

      <div ><p><a class="btn btn-success nao-prioritarios">Não Prioritários</a></p></div>
      <div id="nao-prioritarios_div" style="margin-bottom: 1em" hidden> 
        <?php foreach ($nao_prioritarios as $key => $np) { ?> 
          <?php 
          if(!$model->isNewRecord){
          $npExists = Yii::$app->db->createCommand('SELECT id FROM escopo WHERE atividademodelo_id='.$np['id'].' AND projeto_id='.$model->id)->queryScalar();
          if(!empty($npExists)){ ?>       
              <input type="checkbox" name="np[<?= $key ?>]" value="<?= $np['id']?>" checked> <?= $np['nome'] ?>
         <?php } else{ ?>
              <input type="checkbox" name="np[<?= $key ?>]" value="<?= $np['id']?>"> <?= $np['nome'] ?>
         <?php } } } ?>
      </div>

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

      <?= Html::a('<span class="btn-label">Visualizar AS</span>', ['gerarrelatorio', 'id' => $model->id], ['class' => 'btn btn-primary', 'target'=>'_blank', 'style'=> 'float:right']) ?>

      <?= Html::a('<span class="btn-label">Visualizar BM</span>', ['gerarbm', 'id' => $model->id], ['class' => 'btn btn-primary', 'target'=>'_blank', 'style'=> 'float:right; margin-right: 1em']) ?>
  
     </div>


     <a href="#" id="escopos"></a>
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
    $separaEntregavel = 0;
    $total_a_EE = 0;
    $total_a_ES = 0;
    $total_a_EP = 0;
    $total_a_EJ = 0;
    $total_a_TP = 0;
    $total_p_EE = 0;
    $total_p_ES = 0;
    $total_p_EP = 0;
    $total_p_EJ = 0;
    $total_p_TP = 0;
    $total_i_EE = 0;
    $total_i_ES = 0;
    $total_i_EP = 0;
    $total_i_EJ = 0;
    $total_i_TP = 0;


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

      $separador = 0;
      if($isEntregavel){
          if($separaEntregavel==0){
            $separador = 1;
            $separaEntregavel = 1;
            
          }
          
        $qtd = '<td style="font-size: 10px; padding: 1px;">'.$form2->field($escopoModel, 'qtd')->textInput(['style'=>' width:4em', 'name' => 'Escopo['.$esc["id"].'][qtd]', 'type' => 'number'])->label(false).'</td>'; 
        $for = '<td style="font-size: 10px; padding: 1px;">'.$form2->field($escopoModel, 'for')->textInput(['style'=>' width:4em', 'name' => 'Escopo['.$esc["id"].'][for]'])->label(false).'</td>';

        $separaEntregavel++;
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

       $disciplina_id = Yii::$app->db->createCommand('SELECT disciplina_id FROM atividademodelo WHERE id='.$esc['atividademodelo_id'])->queryScalar();

      $horas_tp = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_tp')->textInput(['style'=>'width:4em', 'name' => 'Escopo['.$esc["id"].'][horas_tp]', 'type' => 'number', 'class' => 'Escopo['.$esc["id"].'][horas_tp] horas', 'id' => 'horas_tp-'.$disciplina_id.'-'.$esc["id"]])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popTP.'</div></td>'; 

      echo $form2->field($escopoModel, 'exe_tp_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_tp_id]','id' => 'escopotp-'.$esc["id"], 'type' => 'number','hidden'=>'hidden'])->label(false);

      $horas_ej = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_ej')->textInput(['style'=>'width:4em', 'name' =>'Escopo['.$esc["id"].'][horas_ej]', 'type' => 'number', 'class' => 'Escopo['.$esc["id"].'][horas_ej] horas', 'id' => 'horas_ej-'.$disciplina_id.'-'.$esc["id"]])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popEJ.'</div></td>'; 

      echo $form2->field($escopoModel, 'exe_ej_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_ej_id]','id' => 'escopoej-'.$esc["id"], 'type' => 'number', 'hidden'=>'hidden'])->label(false);

      $horas_ep = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_ep')->textInput(['style'=>'width:4em', 'name' =>'Escopo['.$esc["id"].'][horas_ep]', 'type' => 'number', 'class' => 'Escopo['.$esc["id"].'][horas_ep] horas', 'id' => 'horas_ep-'.$disciplina_id.'-'.$esc["id"]])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popEP.'</div></td>';

      echo $form2->field($escopoModel, 'exe_ep_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_ep_id]','id' => 'escopoep-'.$esc["id"], 'type' => 'number', 'hidden'=>'hidden'])->label(false);

      $horas_es = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_es')->textInput(['style'=>'width:4em', 'name' =>'Escopo['.$esc["id"].'][horas_es]', 'type' => 'number', 'class' => 'Escopo['.$esc["id"].'][horas_es] horas', 'id' => 'horas_es-'.$disciplina_id.'-'.$esc["id"]])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popES.'</div></td>';

      echo $form2->field($escopoModel, 'exe_es_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_es_id]','id' => 'escopoes-'.$esc["id"], 'type' => 'number', 'hidden'=>'hidden'])->label(false);

      $horas_ee = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_ee')->textInput(['style'=>'width:4em', 'name' =>'Escopo['.$esc["id"].'][horas_ee]', 'type' => 'number', 'class' => 'Escopo['.$esc["id"].'][horas_ee] horas', 'id' => 'horas_ee-'.$disciplina_id.'-'.$esc["id"]])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popEE.'</div></td>';

      echo $form2->field($escopoModel, 'exe_ee_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_ee_id]','id' => 'escopoee-'.$esc["id"], 'type' => 'number', 'hidden'=>'hidden'])->label(false);
      

     

      


      $total = $esc["horas_tp"]+$esc["horas_ej"]+$esc["horas_ep"]+$esc["horas_es"]+$esc["horas_ee"];
      $total = '<td class="total-td['.$esc['id'].']" style="font-size: 12px">'.$total.'</div>';

      
      if($disciplina_id == 1){        
        $total_a_EE = $esc["horas_ee"] + $total_a_EE;
        $total_a_ES = $esc["horas_es"] + $total_a_ES;
        $total_a_EP = $esc["horas_ep"] + $total_a_EP;
        $total_a_EJ = $esc["horas_ej"] + $total_a_EJ;
        $total_a_TP = $esc["horas_tp"] + $total_a_TP;
        
          if($descricao=='<tr><td style="font-size: 10px">Coordenação e Administração</td>'){
            $bodyA .=  $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total.'<tr><th>SUBTOTAL ATIVIDADES GERAIS DE PROJETO</th><th></th><th></th><th class="sub-a-tot-ee">'.$total_a_EE.'</th><th class="sub-a-tot-es">'.$total_a_ES.'</th><th class="sub-a-tot-ep">'.$total_a_EP.'</th><th class="sub-a-tot-ej">'.$total_a_EJ.'</th><th class="sub-a-tot-tp">'.$total_a_TP.'</th><th></th><th></th><th></th><th></th><th></th></tr>';  
          }
          else{
             $bodyA .=  $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
          }
      }
      if($disciplina_id == 2){
        $total_p_EE = $esc["horas_ee"] + $total_p_EE;
        $total_p_ES = $esc["horas_es"] + $total_p_ES;
        $total_p_EP = $esc["horas_ep"] + $total_p_EP;
        $total_p_EJ = $esc["horas_ej"] + $total_p_EJ;
        $total_p_TP = $esc["horas_tp"] + $total_p_TP;
        
        if($descricao=='<tr><td style="font-size: 10px">Coordenação e Administração</td>'){
            $bodyP .=  $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total.'<tr><th>SUBTOTAL ATIVIDADES GERAIS DE PROJETO</th><th></th><th></th><th class="sub-p-tot-ee">'.$total_p_EE.'</th><th class="sub-p-tot-es">'.$total_p_ES.'</th><th class="sub-p-tot-ep">'.$total_p_EP.'</th><th class="sub-p-tot-ej">'.$total_p_EJ.'</th><th class="sub-p-tot-tp">'.$total_p_TP.'</th><th></th><th></th><th></th><th></th><th></th></tr>';  
          }
          else{
             $bodyP .=  $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
          }
      }
      if($disciplina_id == 3){
        $total_i_EE = $esc["horas_ee"] + $total_i_EE;
        $total_i_ES = $esc["horas_es"] + $total_i_ES;
        $total_i_EP = $esc["horas_ep"] + $total_i_EP;
        $total_i_EJ = $esc["horas_ej"] + $total_i_EJ;
        $total_i_TP = $esc["horas_tp"] + $total_i_TP;
               
        if($descricao=='<tr><td style="font-size: 10px">Coordenação e Administração</td>'){
            $bodyI .=  $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total.'<tr><th>SUBTOTAL ATIVIDADES GERAIS DE PROJETO</th><th></th><th></th><th class="sub-i-tot-ee">'.$total_i_EE.'</th><th class="sub-i-tot-es">'.$total_i_ES.'</th><th class="sub-i-tot-ep">'.$total_i_EP.'</th><th class="sub-i-tot-ej">'.$total_i_EJ.'</th><th class="sub-i-tot-tp">'.$total_i_TP.'</th><th></th><th></th><th></th><th></th><th></th></tr>';  
          }
          else{
             $bodyI .=  $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
          }
      } 

          
 } 
    $entr = Yii::$app->db->createCommand('SELECT SUM(horas_ee) entr_horas_a_ee,SUM(horas_es) entr_horas_a_es,SUM(horas_ep) entr_horas_a_ep,SUM(horas_ej) entr_horas_a_ej,SUM(horas_tp) entr_horas_a_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$model->id.' AND isEntregavel=1 AND disciplina_id=1')->queryOne();

    $full = Yii::$app->db->createCommand('SELECT SUM(horas_ee) entr_horas_a_ee,SUM(horas_es) entr_horas_a_es,SUM(horas_ep) entr_horas_a_ep,SUM(horas_ej) entr_horas_a_ej,SUM(horas_tp) entr_horas_a_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=1 AND projeto_id='.$model->id)->queryOne();

    if(!isset($full['entr_horas_a_ee'])) $full['entr_horas_a_ee'] = 0; 
    if(!isset($full['entr_horas_a_es'])) $full['entr_horas_a_es'] = 0; 
    if(!isset($full['entr_horas_a_ep'])) $full['entr_horas_a_ep'] = 0; 
    if(!isset($full['entr_horas_a_ej'])) $full['entr_horas_a_ej'] = 0; 
    if(!isset($full['entr_horas_a_tp'])) $full['entr_horas_a_tp'] = 0; 

    if(!isset($entr['entr_horas_a_ee'])) $entr['entr_horas_a_ee'] = 0;
    if(!isset($entr['entr_horas_a_es'])) $entr['entr_horas_a_es'] = 0;
    if(!isset($entr['entr_horas_a_ep'])) $entr['entr_horas_a_ep'] = 0;
    if(!isset($entr['entr_horas_a_ej'])) $entr['entr_horas_a_ej'] = 0;
    if(!isset($entr['entr_horas_a_tp'])) $entr['entr_horas_a_tp'] = 0;
    
    $bodyA = $bodyA.'<tr><th>SUBTOTAL ATIVIDADES GERAIS DE PROJETO</th><th></th><th></th><th class="sub-a-tot-ee-entregavel">'.$entr['entr_horas_a_ee'].'</th><th class="sub-a-tot-es-entregavel">'.$entr['entr_horas_a_es'].'</th><th class="sub-a-tot-ep-entregavel">'.$entr['entr_horas_a_ep'].'</th><th class="sub-a-tot-ej-entregavel">'.$entr['entr_horas_a_ej'].'</th><th class="sub-a-tot-tp-entregavel">'.$entr['entr_horas_a_tp'].'</th><th></th><th></th><th></th><th></th><th></th></tr>
    <tr><th>HH TOTAL DE ENGENHARIA</th><th></th><th></th><th class="full-a-tot-ee">'.$full['entr_horas_a_ee'].'</th><th class="full-a-tot-es">'.$full['entr_horas_a_es'].'</th><th class="full-a-tot-ep">'.$full['entr_horas_a_ep'].'</th><th class="full-a-tot-ej">'.$full['entr_horas_a_ej'].'</th><th class="full-a-tot-tp">'.$full['entr_horas_a_tp'].'</th><th></th><th></th><th></th><th></th><th></th></tr>';

    $entr = Yii::$app->db->createCommand('SELECT SUM(horas_ee) entr_horas_p_ee,SUM(horas_es) entr_horas_p_es,SUM(horas_ep) entr_horas_p_ep,SUM(horas_ej) entr_horas_p_ej,SUM(horas_tp) entr_horas_p_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$model->id.' AND isEntregavel=1 AND disciplina_id=2')->queryOne();

    $full = Yii::$app->db->createCommand('SELECT SUM(horas_ee) entr_horas_p_ee,SUM(horas_es) entr_horas_p_es,SUM(horas_ep) entr_horas_p_ep,SUM(horas_ej) entr_horas_p_ej,SUM(horas_tp) entr_horas_p_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=2 AND projeto_id='.$model->id)->queryOne();

    if(!isset($full['entr_horas_p_ee'])) $full['entr_horas_p_ee'] = 0;
    if(!isset($full['entr_horas_p_es'])) $full['entr_horas_p_es'] = 0;
    if(!isset($full['entr_horas_p_ep'])) $full['entr_horas_p_ep'] = 0;
    if(!isset($full['entr_horas_p_ej'])) $full['entr_horas_p_ej'] = 0;
    if(!isset($full['entr_horas_p_tp'])) $full['entr_horas_p_tp'] = 0;

    if(!isset($entr['entr_horas_p_ee'])) $entr['entr_horas_p_ee'] = 0;
    if(!isset($entr['entr_horas_p_es'])) $entr['entr_horas_p_es'] = 0;
    if(!isset($entr['entr_horas_p_ep'])) $entr['entr_horas_p_ep'] = 0;
    if(!isset($entr['entr_horas_p_ej'])) $entr['entr_horas_p_ej'] = 0;
    if(!isset($entr['entr_horas_p_tp'])) $entr['entr_horas_p_tp'] = 0;


    $bodyP = $bodyP.'<tr><th>SUBTOTAL ATIVIDADES GERAIS DE PROJETO</th><th></th><th></th><th>'.$entr['entr_horas_p_ee'].'</th><th>'.$entr['entr_horas_p_es'].'</th><th>'.$entr['entr_horas_p_ep'].'</th><th>'.$entr['entr_horas_p_ej'].'</th><th>'.$entr['entr_horas_p_tp'].'</th><th></th><th></th><th></th><th></th><th></th></tr>
    <tr><th>HH TOTAL DE ENGENHARIA</th><th></th><th></th><th class="full-p-tot-ee">'.$full['entr_horas_p_ee'].'</th><th class="full-p-tot-es">'.$full['entr_horas_p_es'].'</th><th class="full-p-tot-ep">'.$full['entr_horas_p_ep'].'</th><th class="full-p-tot-ej">'.$full['entr_horas_p_ej'].'</th><th class="full-p-tot-tp">'.$full['entr_horas_p_tp'].'</th><th></th><th></th><th></th><th></th><th></th></tr>';

    $entr = Yii::$app->db->createCommand('SELECT SUM(horas_ee) entr_horas_i_ee,SUM(horas_es) entr_horas_i_es,SUM(horas_ep) entr_horas_i_ep,SUM(horas_ej) entr_horas_i_ej,SUM(horas_tp) entr_horas_i_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$model->id.' AND isEntregavel=1 AND disciplina_id=3')->queryOne();

    $full = Yii::$app->db->createCommand('SELECT SUM(horas_ee) entr_horas_i_ee,SUM(horas_es) entr_horas_i_es,SUM(horas_ep) entr_horas_i_ep,SUM(horas_ej) entr_horas_i_ej,SUM(horas_tp) entr_horas_i_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=3 AND projeto_id='.$model->id)->queryOne();

    if(!isset($full['entr_horas_i_ee'])) $full['entr_horas_i_ee'] = 0;
    if(!isset($full['entr_horas_i_es'])) $full['entr_horas_i_es'] = 0;
    if(!isset($full['entr_horas_i_ep'])) $full['entr_horas_i_ep'] = 0;
    if(!isset($full['entr_horas_i_ej'])) $full['entr_horas_i_ej'] = 0;
    if(!isset($full['entr_horas_i_tp'])) $full['entr_horas_i_tp'] = 0;

    if(!isset($entr['entr_horas_i_ee'])) $entr['entr_horas_i_ee'] = 0;
    if(!isset($entr['entr_horas_i_es'])) $entr['entr_horas_i_es'] = 0;
    if(!isset($entr['entr_horas_i_ep'])) $entr['entr_horas_i_ep'] = 0;
    if(!isset($entr['entr_horas_i_ej'])) $entr['entr_horas_i_ej'] = 0;
    if(!isset($entr['entr_horas_i_tp'])) $entr['entr_horas_i_tp'] = 0;

    $bodyI = $bodyI.'<tr><th>SUBTOTAL ATIVIDADES GERAIS DE PROJETO</th><th></th><th></th><th>'.$entr['entr_horas_i_ee'].'</th><th>'.$entr['entr_horas_i_es'].'</th><th>'.$entr['entr_horas_i_ep'].'</th><th>'.$entr['entr_horas_i_ej'].'</th><th>'.$entr['entr_horas_i_tp'].'</th><th></th><th></th><th></th><th></th><th></th></tr>
    <tr><th>HH TOTAL DE ENGENHARIA</th><th></th><th></th><th class="full-i-tot-ee">'.$full['entr_horas_i_ee'].'</th><th class="full-i-tot-es">'.$full['entr_horas_i_es'].'</th><th class="full-i-tot-ep">'.$full['entr_horas_i_ep'].'</th><th class="full-i-tot-ej">'.$full['entr_horas_i_ej'].'</th><th class="full-i-tot-tp">'.$full['entr_horas_i_tp'].'</th><th></th><th></th><th></th><th></th><th></th></tr>';

    $automacao = '<div style="height: 50em; overflow-y: scroll;">'.$header.''.$bodyA.'</table></div>';
    $processo = '<div style="height: 50em; overflow-y: scroll;">'.$header.''.$bodyP.'</table></div>';
    $instrumentacao = '<div style="height: 50em; overflow-y: scroll;">'.$header.''.$bodyI.'</table></div>';
    $as = '<div>'. $form2->field($model, "nota_geral")->textArea() .'</div>';
    $resumo = '<div>'. $form2->field($model, "resumo_escopo")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_exclusoes")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_premissas")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_restricoes")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_normas")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_documentos")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_itens")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_prazo")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_observacoes")->textArea() .'</div>
    ';

    $ld_preliminar = '<p>'.
        '<a class="btn btn-success ld-create">Novo</a>'.
    '</p>'.GridView::widget([
        'dataProvider' => $ldPreliminarDataProvider,
            'filterModel' => $ldPreliminarSearchModel,
            // 'pjax' => true,        
            'options' => ['style' => 'font-size:12px;'],                
            // 'hover' => true,            
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
/*[
    'label'=>'AS',
    'content'=>$as,        
],*/
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
    

  
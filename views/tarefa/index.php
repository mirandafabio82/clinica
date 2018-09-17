<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\models\Escopo;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Agenda */
/* @var $form yii\widgets\ActiveForm */

if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante'])){
  $this->registerJs('   
    $( document ).ready(function() {
        var id = '.Yii::$app->user->getId().';    
        $("#executante-id").val(id);
        $.ajax({ 
          url: "index.php?r=tarefa/preencheprojeto",
          data: {id: id},
          type: "POST",
          success: function(response){
           var resposta = $.parseJSON(response);
           console.log(resposta);
           var myOptions = resposta;

           $("#projeto-id").children("option:not(:first)").remove();
           var mySelect = $("#projeto-id");
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
}
else{
    $this->registerJs('   
    $( document ).ready(function() {
       var id = $("#projeto-id").val();    
          console.log(id);
          $.ajax({ 
            url: "index.php?r=tarefa/preencheexecutante",
            data: {id: id},
            type: "POST",
            success: function(response){
             var resposta = $.parseJSON(response);
             console.log(resposta);
             var myOptions = resposta;

             $("#executante-id").children("option:not(:first)").remove();
             var mySelect = $("#executante-id");
             $.each(myOptions, function(val, text) {
              mySelect.append(
              $("<option></option>").val(text["id"]).html(text["nome"])
              );
            });

            $("#executante-id").val('.$_POST['executante'].');

          },
          error: function(){
            console.log("failure");
          }
        });
    });
   

');

}

$this->registerJs('

  $(document).ajaxStart(function() {
    $("#projeto-id").attr("readonly", true);
    $("#loading").show(); // show the gif image when ajax starts
  }).ajaxStop(function() {
    $("#projeto-id").attr("readonly", false);
      $("#loading").hide(); // hide the gif image when ajax completes
  });

     $(".save").click(function(){
        
        $( ".executado" ).each(function( index ) {
          var ultimo = 0;
          if(index == $( ".executado" ).length - 1){
            ultimo = 1;
          }
          
          divisor = this.name.split("[")[1];

          id = divisor.split("]")[0];

          tipo = this.name.split("[")[2].split("]")[0];
          console.log(tipo);
          valor = this.value;
          if(this.value=="") valor = "null";

          $.ajax({ 
              url: "index.php?r=tarefa/attatividade",
              data: {id: id, value: valor, tipo: tipo, ultimo: ultimo},
              type: "POST",
              success: function(response){
               console.log(response);
               
             },
             error: function(){
              console.log("failure");
            }
          });
        });
      });
    
    /*$("#executante-id").change(function(ev){
      var id = $(this).val();    
      console.log(id);
      $.ajax({ 
        url: "index.php?r=tarefa/preencheprojeto",
        data: {id: id},
        type: "POST",
        success: function(response){
         var resposta = $.parseJSON(response);
         console.log(resposta);
         var myOptions = resposta;

         $("#projeto-id").children("option:not(:first)").remove();
         var mySelect = $("#projeto-id");
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

    $("#projeto-id").change(function(ev){
      var id = $(this).val();    
      console.log(id);
      $.ajax({ 
        url: "index.php?r=tarefa/preencheexecutante",
        data: {id: id},
        type: "POST",
        success: function(response){
         var resposta = $.parseJSON(response);
         console.log(resposta);
         var myOptions = resposta;

         $("#executante-id").children("option:not(:first)").remove();
         var mySelect = $("#executante-id");
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

<style>
.table-bordered > tbody > tr > td{
  padding-top: 0px !important;
  padding-bottom: 0px !important;
}
.pagination{
    margin: 0px;
}

.summary{
  display: none;
}

#w2{
    display: none;
}

.barra-btn{
  display:block;
  position:fixed;
  width:100%;
  bottom:0vh;
  left:0;
  background:#62727b;
  text-align:center;
  padding: 0px 0;
  z-index: 99;
}

.btn-barra {
  background-color: #62727b; 
  border-color: #62727b;
  color:white;
  -webkit-transition-duration: 0.4s; /* Safari */
  transition-duration: 0.4s;
}
.btn-barra:hover {
    background-color: white; /* Green */
    color: white;
}
</style>

<div class="box box-primary">
    <div class="box-header with-border">
    <?php $form = ActiveForm::begin(); ?>
      <div class="row"> 
            
      
      <div class="col-md-3">  
          <select id="projeto-id" class="form-control" name="projeto" >
            <option value="">Selecione um Projeto</option>
            <?php                  
              foreach ($listProjetos as $key => $list) {                   
                ?> 
                <option 
                  value="<?=$key?>" <?= isset($_POST['projeto']) && $key == $_POST['projeto'] ? "selected" :"" ?>><?=$list ?>                      
                </option>
            <?php } ?>
            
          </select>
      </div>

      <div  class="col-md-1">  
        <img style="z-index: 999999999" src="resources/dist/img/loading.gif" type="hidden" name="loading" id="loading" value="" width="64px" hidden/>        
      </div> 

      <?php if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin'])){ ?>
        <div class="col-md-3"> 
      <?php } else{?>
      <div class="col-md-3" hidden>
      <?php } ?> 
          <select  id="executante-id" class="form-control executante" name="executante" >
            <option value="">Selecione um Executante</option>
            
          </select>
      </div>  
      <div style="margin-bottom: 1em">
        <button type="submit" class="btn btn-primary filtrar">Filtrar</button>
      </div>
      </div>
     <?php ActiveForm::end(); ?>
    </div>

<?php if(isset($_POST['projeto'])){ 
  $executantes = Yii::$app->db->createCommand('SELECT user.nome FROM user JOIN projeto_executante ON user.id=projeto_executante.executante_id WHERE projeto_executante.projeto_id='.$_POST['projeto'])->queryAll();

  
?>
<div>
    <table>
      <tr>
        <th>Executantes</th>        
      </tr>
      <tr>
        <?php foreach ($executantes as $key => $exe) { ?>
          <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"> <?= $exe['nome'] ?> </td>
        <?php } ?>
      </tr>
    </table>
</div>
<?php } ?>
    
</div>



<!-- mask so funciona com isso -->
<?php $this->head() ?>

<?php if($isPost){ ?>
<div class="barra-btn" >
  <button type="button" class="btn btn-barra save" ><i class="fa fa-calendar" aria-hidden="true"></i> Adicionar Horas</button>
  <?php if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin'])){ ?>
    <?= Html::a('<i class="fa fa-list" aria-hidden="true"></i> Gerar BM', ['/tarefa/gerarbm', 'projetoid'=>$projeto_selected], ['class'=>'btn btn-barra']) ?>
  <?php } ?>
</div>
  <?php Pjax::begin(['id' => 'pjax-grid-view']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'options' => ['style' => 'font-size:12px;'],
        'pjax' => true,
        
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-hourglass-half"></i> Gerenciamento das Atividades'
        ],
        'columns' => [    
            [
              'class' => 'kartik\grid\ExpandRowColumn',
              'width' => '50px',
              'value' => function ($model, $key, $index, $column) {
                  return GridView::ROW_COLLAPSED;
              },
              'detail' => function ($model, $key, $index, $column) use ($executante_id){

                // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                // $dataProvider->query->where('horas_tp IS NOT NULL OR horas_ej IS NOT NULL OR horas_ep IS NOT NULL OR horas_es IS NOT NULL OR horas_ee IS NOT NULL');

                if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante'])){
                  $executante_id = Yii::$app->user->getId();
                }

                  return Yii::$app->controller->renderPartial('_grid_content', 
                    ['model' => $model, 'executante_id' => $executante_id]);
              },
              'headerOptions' => ['class' => 'kartik-sheet-style'] 
              
          ],            
            'nome',
            'descricao',
                     
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    Pjax::end(); } 

$this->registerJs('
  $(".kv-expand-row").click(); // iniciar a pagina com a tabela expandida
');

?>

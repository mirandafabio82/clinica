<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\models\Escopo;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Agenda */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('

      $(".save").click(function(){
        $(".form-group").submit();        
    });
    
    $("#executante-id").change(function(ev){
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
</style>

<div class="box box-primary">
    <div class="box-header with-border">
    <?php $form = ActiveForm::begin(); ?>
      <div class="row"> 
      <div class="col-md-3">  
          <select  id="executante-id" class="form-control executante" name="executante" >
            <option value="">Selecione um Executante</option>
            <?php                  
              foreach ($listExecutantes as $key => $list) {                   
                ?> 
                <option 
                  value="<?=$key?>" ><?=$list ?>                      
                </option>
            <?php } ?>
          </select>
      </div>     
      <div class="col-md-3">  
          <select id="projeto-id" class="form-control" name="projeto" >
            <option value="">Selecione um Projeto</option>
            
          </select>
      </div>
      <div style="margin-bottom: 1em">
        <button type="submit" class="btn btn-primary filtrar">Filtrar</button>
      </div>
      </div>
     <?php ActiveForm::end(); ?>
    </div>
</div>



<!-- mask so funciona com isso -->
<?php $this->head() ?>

<?php if($isPost){ ?>
<div style="margin-bottom: 1em">
  <button type="button" class="btn btn-primary save">Adicionar Horas</button>
  <?= Html::a('Gerar BM', ['/tarefa/gerarbm', 'projetoid'=>$projeto_selected], ['class'=>'btn btn-primary']) ?>
</div>

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
    } ?>

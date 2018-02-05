<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\models\Escopo;

/* @var $this yii\web\View */
/* @var $model app\models\Agenda */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('

      $(".save").click(function(){
        
        $( ".executado" ).each(function( index ) {
          
          divisor = this.name.split("[")[1];

          id = divisor.split("]")[0];

          tipo = this.name.split("[")[2].split("]")[0];
          
          valor = this.value;
          if(this.value=="") valor = "null";

          $.ajax({ 
              url: "index.php?r=atividade/attatividade",
              data: {id: id, value: valor, tipo: tipo},
              type: "POST",
              success: function(response){
               console.log(response);
               // location.reload();
             },
             error: function(){
              console.log("failure");
            }
          });
        });

        $( ".status" ).each(function( index ) {
          console.log(this.name +" "+ this.value );
          divisor = this.name.split("[")[1];

          id = divisor.split("]")[0];

          tipo = this.name.split("[")[2].split("]")[0];
          
          valor = this.value;
          if(this.value=="") valor = "null";

          $.ajax({ 
              url: "index.php?r=atividade/attatividade",
              data: {id: id, value: valor, tipo: tipo},
              type: "POST",
              success: function(response){
               console.log(response);
                location.reload();
             },
             error: function(){
              console.log("failure");
            }
          });
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


<!-- mask so funciona com isso -->
<?php $this->head() ?>
<div style="margin-bottom: 1em">
  <button type="button" class="btn btn-primary save">Salvar</button>
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
              'detail' => function ($model, $key, $index, $column) {
                
                // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                // $dataProvider->query->where('horas_tp IS NOT NULL OR horas_ej IS NOT NULL OR horas_ep IS NOT NULL OR horas_es IS NOT NULL OR horas_ee IS NOT NULL');

                  return Yii::$app->controller->renderPartial('_grid_content', 
                    ['model' => $model]);
              },
              'headerOptions' => ['class' => 'kartik-sheet-style'] 
              
          ],            
            'nome',
            'descricao'   
            
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

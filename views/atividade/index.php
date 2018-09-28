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

    $(".obs_atividade").focusout(function(){
       id = this.id.split("[")[1];
       id = id.split("]")[0];

       valor = this.value;

       $.ajax({ 
              url: "index.php?r=atividade/attobs",
              data: {id: id, value: valor},
              type: "POST",
              success: function(response){
               console.log(response);
               
             },
             error: function(){
              console.log("failure");
            }
          });       
    });

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
            'descricao',
          [
            'header' => 'Progresso',
            'format' => 'raw',
            'width' => '40em',
            'value' => function ($data) {

              $horas_ee = Yii::$app->db->createCommand('SELECT SUM(horas_ee) as horas, SUM(executado_ee) as exe FROM escopo WHERE projeto_id='.$data->id)->queryOne();
              $horas_es = Yii::$app->db->createCommand('SELECT SUM(horas_es) as horas, SUM(executado_es) as exe FROM escopo WHERE projeto_id='.$data->id)->queryOne();
              $horas_ep = Yii::$app->db->createCommand('SELECT SUM(horas_ep) as horas, SUM(executado_ep) as exe FROM escopo WHERE projeto_id='.$data->id)->queryOne();
              $horas_ej = Yii::$app->db->createCommand('SELECT SUM(horas_ej) as horas, SUM(executado_ej) as exe FROM escopo WHERE projeto_id='.$data->id)->queryOne();
              $horas_tp = Yii::$app->db->createCommand('SELECT SUM(horas_tp) as horas, SUM(executado_tp) as exe FROM escopo WHERE projeto_id='.$data->id)->queryOne();
             

              if(empty($horas_ee['horas'])) $horas_ee['horas']=0;
              if(empty($horas_es['horas'])) $horas_es['horas']=0;
              if(empty($horas_ep['horas'])) $horas_ep['horas']=0;
              if(empty($horas_ej['horas'])) $horas_ej['horas']=0;
              if(empty($horas_tp['horas'])) $horas_tp['horas']=0;


              $horas = $horas_ee['horas']+$horas_es['horas']+$horas_ep['horas']+$horas_ej['horas']+$horas_tp['horas'];
              
              if($horas!=0){
                $progress = $horas_ee['exe']+$horas_es['exe']+$horas_ep['exe']+$horas_ej['exe']+$horas_tp['exe'] / ($horas) * 100;
                $progressColor = "";
               
                if($progress <= 30) $progressColor = 'danger'; 
                if($progress <= 99.9 && $progress > 30) $progressColor = 'warning';
                if($progress >= 100) $progressColor = 'success';

                return '<div class="progress progress-xs">
                        <div class="progress-bar progress-bar-'.$progressColor.' progress-bar-striped" style="width:  '.$progress.'%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          </div>
                      </div>';
              }
            },
          ],

            
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Agenda */
/* @var $form yii\widgets\ActiveForm */
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
</style>
<!-- mask so funciona com isso -->
<?php $this->head() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['style' => 'font-size:12px;'],
        'pjax' => true,
        
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-hourglass-half"></i> Gerenciamento das Atividades'
        ],
        'columns' => [                       


            [
              'attribute' => 'status',      
              'class' => 'kartik\grid\EditableColumn',        
              'format' => 'raw',
              'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
               'value' => function ($data) {

                $status = Yii::$app->db->createCommand('SELECT status, cor FROM agenda_status WHERE id='.$data->status)->queryOne();
                
               return '<span style="color:'.$status['cor'].' "><i class="fa fa-circle" aria-hidden="true"></i> '.$status['status'].'</span>';

               },
                'editableOptions' => [
              'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
              'data' => $listStatus                
              ]
            ],
            [
                'attribute' => 'projeto_id',
                'value' => function($data){
                    if(isset($data->projeto_id))
                    return Yii::$app->db->createCommand('SELECT nome FROM projeto WHERE id='.$data->projeto_id)->queryScalar();
                }
            ],            
               'atividademodelo_id',
               'nome',
            
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

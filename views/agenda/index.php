<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\AgendaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Agenda';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agenda-index">
<style>
.kv-editable-link {
border: 0 !important;
background: none !important;
-webkit-appearance: none !important;
}

</style>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'toolbar' =>  [
        ['content' => Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['class' => 'btn btn-success'])
        ],
          '{export}',
          '{toggleData}',
        ],
        'export' => [
          'fontAwesome' => true
        ],
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-calendar"></i> Agenda'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'projeto_id',
                'value' => function($data){
                    return Yii::$app->db->createCommand('SELECT nome FROM projeto WHERE id='.$data->projeto_id)->queryScalar();
                }
            ],            
            [
              'attribute' => 'status',      
              'class' => 'kartik\grid\EditableColumn',        
              'format' => 'raw',
              'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
               'value' => function ($data) {

                $status = Yii::$app->db->createCommand('SELECT status FROM agenda_status WHERE id='.$data->status)->queryScalar();
                if($data->status==1)
                    $color = 'blue';
                else if($data->status==2)
                    $color = 'green';
                else if($data->status==3)
                    $color = 'red';
                else if($data->status==4)
                    $color = 'yellow';
                else 
                    $color = 'orange';

               return '<span style="color:'.$color.' "><i class="fa fa-circle" aria-hidden="true"></i> '.$status.'</span>';

               },
            ],
            'data',
            [
                'attribute' => 'site',
                'value' => function($data){
                    return Yii::$app->db->createCommand('SELECT nome FROM site WHERE id='.$data->local)->queryScalar();
                }
            ],         
            'quem',
            'assunto',
            'hr_inicio',
            'hr_final',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

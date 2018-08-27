<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logs';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
.table-bordered > tbody > tr > td{
  padding-top: 3px !important;
  padding-bottom: 3px !important;
}

.table-striped > tbody > tr:nth-of-type(odd){
  background-color: #b6b6b6 !important;
}
.dropify-wrapper.touch-fallback .dropify-clear {
  display:none;
}
.dropify-wrapper.touch-fallback .dropify-infos {
  display:none;
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

<div class="log-index">

   
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
<div class="box box-primary">
    <div class="box-header with-border">
<div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-handshake-o"></i> Logs </div>
<div style="margin-bottom:1em;margin-top: 1em">
    <?= Html::a('Mostrar Todos', ['/log/index', 'pagination' => true], ['class'=>'btn btn-primary grid-button']) ?>
</div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

           // ['class' => 'yii\grid\ActionColumn'],
            'descricao',
            [
            'attribute' => 'data', 
            'format' => 'raw',
            'value' => function ($data) {
                return Date('d/m/Y H:i:s', strtotime($data->data));
            }, 
            
            ],

            
        ],
    ]); ?>
</div>
</div>
</div>
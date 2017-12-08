<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Configuração';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-index">

   
    
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
            'heading' => '<i class="fa fa-cog"></i> Configurações'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'vl_hh',
            'vl_km',
            'qtd_km_dia',
            'pasta',
            // 'ultimo_bm',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

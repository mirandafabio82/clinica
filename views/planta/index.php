<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PlantaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plantas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="planta-index">
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
            'heading' => '<i class="fa fa-map"></i> Plantas'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'site_id',
                'value' => function($data){
                    return Yii::$app->db->createCommand('SELECT nome FROM planta WHERE site_id='.$data->site_id)->queryScalar();
                }
            ],
            'nome',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>

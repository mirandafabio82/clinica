<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TipoExecutanteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipos de Executantes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-executante-index">
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
            'heading' => '<i class="fa fa-vcard"></i> Tipos de Executante'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            'id',
            'cargo',
            'valor_hora',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

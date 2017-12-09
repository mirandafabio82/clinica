<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\AtividadeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Atividades';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="atividade-index">
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
            'heading' => '<i class="fa fa-hourglass-half"></i> Atividades'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'projeto_id',
            'executante_id',
            'local',
            'acao',
            // 'data',
            // 'comentario',
            // 'status',
            // 'solicitante',
            // 'hr_inicio',
            // 'hr_final',
            // 'hr100_inicio',
            // 'hr100_final',
            // 'horas',
            // 'valor_hh',
            // 'valor_km',
            // 'valor_total',
            // 'criado',
            // 'modificado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

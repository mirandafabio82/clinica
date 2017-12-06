<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\AtividadeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Atividades';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="atividade-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nova Atividade', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="box box-primary">
        <div class="box-header with-border">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
</div>
</div>
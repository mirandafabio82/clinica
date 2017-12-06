<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TipoExecutanteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipos de Executantes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-executante-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Novo Tipo de Executante', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="box box-primary">
        <div class="box-header with-border">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'cargo',
            'valor_hora',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
</div>
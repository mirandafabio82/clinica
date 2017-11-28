<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\EscopoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Escopos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="escopo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Escopo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nome',
            'item',
            'descricao',
            'horas',
            // 'executado',
            // 'interno',
            // 'criado',
            // 'modificado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

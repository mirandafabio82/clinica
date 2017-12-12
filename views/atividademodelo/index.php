<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\AtividademodeloSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Atividademodelos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="atividademodelo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Atividademodelo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nome',
            'isPrioritaria',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

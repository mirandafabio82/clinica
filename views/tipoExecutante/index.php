<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TipoExecutanteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipo Executantes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-executante-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tipo Executante', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'cargo',
            'valor_hora',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

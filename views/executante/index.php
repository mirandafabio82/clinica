<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ExecutanteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Executantes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="executante-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Executante', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'usuario_id',
            'tipo_executante_id',
            'nome',
            'cidade',
            'uf',
            // 'cpf',
            // 'email:email',
            // 'telefone',
            // 'celular',
            // 'criado',
            // 'modificado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

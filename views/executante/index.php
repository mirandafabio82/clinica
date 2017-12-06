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
        <?= Html::a('Novo Executante', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="box box-primary">
        <div class="box-header with-border">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'usuario_id',
            [
              'header' => 'Tipo de Executante',              
              'format' => 'raw',
               'value' => function ($data) {

                   return Yii::$app->db->createCommand('SELECT cargo FROM tipo_executante WHERE id='.$data->tipo_executante_id)->queryScalar();
               },
            ],
            [
              'header' => 'Nome',              
              'format' => 'raw',
               'value' => function ($data) {

                   return Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.$data->usuario_id)->queryScalar();
               },
            ],
            'cidade',            
            // 'cpf',
            [
              'header' => 'Email',              
              'format' => 'raw',
               'value' => function ($data) {

                   return Yii::$app->db->createCommand('SELECT email FROM user WHERE id='.$data->usuario_id)->queryScalar();
               },
            ],
            'telefone',
            'celular',
            'criado',
            // 'modificado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
</div>
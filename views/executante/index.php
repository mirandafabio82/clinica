<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ExecutanteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Executantes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="executante-index">

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
            'heading' => '<i class="fa fa-wrench"></i> Executantes'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'usuario_id',
            [
              'header' => 'Nome',              
              'format' => 'raw',
               'value' => function ($data) {

                   return Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.$data->usuario_id)->queryScalar();
               },
            ],
            [
              'header' => 'Tipo',              
              'format' => 'raw',
               'value' => function ($data) {
                  $executantes = Yii::$app->db->createCommand('SELECT cargo FROM tipo_executante JOIN executante_tipo ON tipo_executante.id=executante_tipo.tipo_id WHERE executante_tipo.executante_id='.$data->usuario_id)->queryAll();
                  $tipos = '';
                  foreach ($executantes as $key => $exec) {
                    $tipos .= $exec['cargo'].'; ';
                  }

                   return $tipos;
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

<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProjetoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projetos';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
.kv-editable-link {
border: 0 !important;
background: none !important;
-webkit-appearance: none !important;
}

</style>

<div class="projeto-index">

    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!-- <p>
        <?//= Html::a('Novo Projeto', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
     -->
      
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
            'heading' => '<i class="fa fa-folder-open"></i>  Projetos'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nome',
            [
              'attribute' => 'status',      
              'class' => 'kartik\grid\EditableColumn',        
              'format' => 'raw',
              'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
               'value' => function ($data) {

                $status = Yii::$app->db->createCommand('SELECT status FROM projeto_status WHERE id='.$data->status)->queryScalar();
                if($data->status==1)
                    $color = 'blue';
                else if($data->status==2)
                    $color = 'green';
                else
                    $color = 'red';

               return '<span style="color:'.$color.' "><i class="fa fa-circle" aria-hidden="true"></i> '.$status.'</span>';

               },
            ],
            [
              'attribute' => 'cliente_id',   
              'class' => 'kartik\grid\EditableColumn',           
              'format' => 'raw',
              'contentOptions' => ['style' => 'width:10em;'],
               'value' => function ($data) {

                $nome = Yii::$app->db->createCommand('SELECT nome FROM cliente WHERE id='.$data->cliente_id)->queryScalar();
                

               return $nome;

               },
            ],
            [
              'attribute' => 'contato_id',              
              'format' => 'raw',
              'contentOptions' => ['style' => 'width:10em;  min-width:10em;'],
              'value' => function ($data) {

                $nome = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.$data->contato_id)->queryScalar();                

               return $nome;

               },
            ],
            // 'descricao',
            'codigo',
            [
              'attribute' => 'site',              
              'format' => 'raw',
               'value' => function ($data) {

                $nome = Yii::$app->db->createCommand('SELECT nome FROM site WHERE id='.$data->site)->queryScalar();               

               return $nome;

               },
            ],
            [
              'attribute' => 'planta',              
              'format' => 'raw',
               'value' => function ($data) {

                $nome = Yii::$app->db->createCommand('SELECT nome FROM planta WHERE id='.$data->planta)->queryScalar();               

               return $nome;

               },
            ],
            'municipio',
            'uf',
            'cnpj',
            // 'tratamento',
            // 'contato',
            // 'setor',
            [
              'attribute' => 'fone_contato',
              'format' => 'raw',
              'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
            ],
            [
              'attribute' => 'celular',
              'format' => 'raw',
              'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
            ],
            'email:email',
            'proposta',
            'rev_proposta',
            'data_proposta',
            'qtd_hh',
            'vl_hh',
            'total_horas',
            'qtd_dias',
            'qtd_km',
            'vl_km',
            'total_km',
            'valor_proposta',
            'valor_consumido',
            'valor_saldo',
            'pendencia',
            'comentarios',
            'data_entrega',
            'cliente_fatura',
            'site_fatura',
            'municipio_fatura',
            'uf_fatura',
            'cnpj_fatura',
            'criado',
            'modificado',

            ['class' => 'yii\grid\ActionColumn'],

        ],
    ]); ?>
</div>

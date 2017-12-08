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
<div class="projeto-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Novo Projeto', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="box box-primary">
        <div class="box-header with-border" style="overflow-y: scroll;">
      
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'hover' => true,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
              'attribute' => 'status',      
              'class' => 'kartik\grid\EditableColumn',        
              'format' => 'raw',
               'value' => function ($data) {

                $status = Yii::$app->db->createCommand('SELECT status FROM projeto_status WHERE id='.$data->status)->queryScalar();
                if($data->status==1)
                    $color = 'blue';
                else if($data->status==2)
                    $color = 'green';
                else
                    $color = 'red';

               return '<span style="color:'.$color.' "><i class="fa fa-circle" aria-hidden="true"></i>'.$status.'</span>';

               },
            ],
            [
              'attribute' => 'cliente_id',   
              'class' => 'kartik\grid\EditableColumn',           
              'format' => 'raw',
               'value' => function ($data) {

                $nome = Yii::$app->db->createCommand('SELECT nome FROM cliente WHERE id='.$data->cliente_id)->queryScalar();
                

               return $nome;

               },
            ],
            [
              'attribute' => 'contato_id',              
              'format' => 'raw',
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
            'fone_contato',
            'celular',
            'email:email',
            'documentos',
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
</div>
</div>
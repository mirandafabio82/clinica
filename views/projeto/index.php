<?php

use yii\helpers\Html;
use yii\grid\GridView;

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
        <div class="box-header with-border">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'cliente_id',
            'contato_id',
            'escopo_id',
            'descricao',
            // 'codigo',
            // 'site',
            // 'planta',
            // 'municipio',
            // 'uf',
            // 'cnpj',
            // 'tratamento',
            // 'contato',
            // 'setor',
            // 'fone_contato',
            // 'celular',
            // 'email:email',
            // 'documentos',
            // 'proposta',
            // 'rev_proposta',
            // 'data_proposta',
            // 'qtd_hh',
            // 'vl_hh',
            // 'total_horas',
            // 'qtd_dias',
            // 'qtd_km',
            // 'vl_km',
            // 'total_km',
            // 'valor_proposta',
            // 'valor_consumido',
            // 'valor_saldo',
            // 'status',
            // 'pendencia',
            // 'comentarios',
            // 'data_entrega',
            // 'cliente_fatura',
            // 'site_fatura',
            // 'municipio_fatura',
            // 'uf_fatura',
            // 'cnpj_fatura',
            // 'criado',
            // 'modificado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
</div>
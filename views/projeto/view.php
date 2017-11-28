<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Projeto */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Projetos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projeto-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'cliente_id',
            'contato_id',
            'escopo_id',
            'descricao',
            'codigo',
            'site',
            'planta',
            'municipio',
            'uf',
            'cnpj',
            'tratamento',
            'contato',
            'setor',
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
            'status',
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
        ],
    ]) ?>

</div>

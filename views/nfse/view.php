<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Nfse */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Nfses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nfse-view">

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
            'processo',
            'nota_fiscal',
            'data_emissao',
            'data_entrega',
            'status',
            'pendencia:ntext',
            'nf_devolvida',
            'comentario_devolucao:ntext',
            'data_pagamento',
            'usuario_pendencia',
            'cnpj_emitente',
        ],
    ]) ?>

</div>

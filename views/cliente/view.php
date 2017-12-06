<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cliente-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    </p>
<div class="box box-primary">
        <div class="box-header with-border">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nome',
            'site',
            'cnpj',
            'cidade',
            'uf',
            'criado',
            'modificado',
        ],
    ]) ?>
</div>
</div>
</div>
<?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
<?= Html::a('Apagar', ['delete', 'id' => $model->id], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Are you sure you want to delete this item?',
        'method' => 'post',
    ],
]) ?>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Escopo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Escopos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="escopo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Excluir', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="row">
        <div class="col-md-12">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nome',
            'item',
            'descricao',
            'horas',
            'executado',
            'interno',
            'criado',
            'modificado',
        ],
    ]) ?>

</div>
</div>
</div>
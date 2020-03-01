<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TratamentoRealizado */

$this->title = $model->id_tratamento;
$this->params['breadcrumbs'][] = ['label' => 'Tratamento Realizados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tratamento-realizado-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_tratamento], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_tratamento], [
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
            'id_tratamento',
            'id_agendamento',
            'dente',
            'tratamento_realizado',
        ],
    ]) ?>

</div>

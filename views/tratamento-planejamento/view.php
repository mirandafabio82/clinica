<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TratamentoPlanejamento */

$this->title = $model->id_tratamento_planejamento;
$this->params['breadcrumbs'][] = ['label' => 'Planejamento', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tratamento-planejamento-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_tratamento_planejamento], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_tratamento_planejamento], [
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
            'id_tratamento_planejamento',
            'id_paciente',
            'primeira_opcao',
            'segunda_opcao',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TratamentoPlanejamento */

$this->title = 'Update Tratamento Planejamento: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Tratamento Planejamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tratamento_planejamento, 'url' => ['view', 'id' => $model->id_tratamento_planejamento]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tratamento-planejamento-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

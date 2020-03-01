<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TratamentoRealizado */

$this->title = 'Update Tratamento Realizado: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Tratamento Realizados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tratamento, 'url' => ['view', 'id' => $model->id_tratamento]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tratamento-realizado-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProjetoNome */

$this->title = 'Update Projeto Nome: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Projeto Nomes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="projeto-nome-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

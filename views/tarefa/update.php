<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tarefa */

$this->title = 'Update Tarefa: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Tarefas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tarefa-update">

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        'listProjetos' => $listProjetos,
        'listExecutantes' => $listExecutantes,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Agenda */

$this->title = 'Agenda: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Agendas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="agenda-update">

  
    <?= $this->render('_form', [
        'model' => $model,
        'listProjetos' => $listProjetos,
        'listSites' => $listSites,
        'listStatus' => $listStatus,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>
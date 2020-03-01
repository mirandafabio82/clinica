<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Paciente */

$this->title = 'Update Paciente: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Pacientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_paciente, 'url' => ['view', 'id' => $model->id_paciente]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="paciente-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'listRaca' => $listRaca,
        'listStatusCivil' => $listStatusCivil,
    ]) ?>

</div>
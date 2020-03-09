<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Agenda */

$this->title = 'Agenda';
$this->params['breadcrumbs'][] = ['label' => 'Agendas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agenda-create">

    <?= $this->render('_form', [
        'model' => $model,
        'arrayEventos' => $arrayEventos,
        'listConsultas' => $listConsultas,
        'listStatus' => $listStatus,
        'listResponsavel' => $listResponsavel,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'resp_autocomplete' => $resp_autocomplete,
        'listProcedimento' => $listProcedimento,
        'listBandeira' => $listBandeira
    ]) ?>

</div>

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
        'listProjetos' => $listProjetos,
        'listSites' => $listSites,
        'listStatus' => $listStatus,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'arrayEventos' => $arrayEventos,
        'listContatos' => $listContatos,
        'listExecutantes' => $listExecutantes,
        'arrayExecutantes' => $arrayExecutantes,
        'proj_autocomplete' => $proj_autocomplete,
        'cont_autocomplete' => $cont_autocomplete,
        'resp_autocomplete' => $resp_autocomplete,
    ]) ?>

</div>

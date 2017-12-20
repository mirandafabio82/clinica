<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Projeto */

$this->title = 'Projeto: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Projetos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="projeto-update">

    
    <?= $this->render('_form', [
        'model' => $model,
        'listClientes' => $listClientes,
        'listContatos' => $listContatos,
        'listSites' => $listSites,
        'listStatus' => $listStatus,
        'listEscopo' => $listEscopo,
        'listDisciplina' => $listDisciplina,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'escopoDataProvider' => $escopoDataProvider,
        'searchEscopo' => $searchEscopo,
        'escopoArray' => $escopoArray,
        'listExecutantes_tp' => $listExecutantes_tp,
        'listExecutantes_ej' => $listExecutantes_ej,
        'listExecutantes_ep' => $listExecutantes_ep,
        'listExecutantes_es' => $listExecutantes_es,
        'listExecutantes_ee' => $listExecutantes_ee,
    ]) ?>

</div>

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
        'listPlantas' => $listPlantas,
        'listEscopo' => $listEscopo,
        'listDisciplina' => $listDisciplina,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>

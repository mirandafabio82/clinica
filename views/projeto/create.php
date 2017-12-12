<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Projeto */

$this->title = 'Projeto';
$this->params['breadcrumbs'][] = ['label' => 'Projetos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projeto-create">


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
    ]) ?>

</div>

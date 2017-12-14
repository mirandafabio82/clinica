<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Atividademodelo */

$this->title = 'Modelo de Atividade';
$this->params['breadcrumbs'][] = ['label' => 'Atividademodelos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="atividademodelo-create">

    <?= $this->render('_form', [
        'model' => $model,
        'listEscopo' => $listEscopo,
        'listDisciplina' => $listDisciplina,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>

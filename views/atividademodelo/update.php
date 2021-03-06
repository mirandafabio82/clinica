<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Atividademodelo */

$this->title = 'Modelo de Atividade ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Atividademodelos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="atividademodelo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'listEscopo' => $listEscopo,
        'listDisciplina' => $listDisciplina,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Projeto */

$this->title = 'Update Projeto: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Projetos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="projeto-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'listClientes' => $listClientes,
        'listContatos' => $listContatos,
        'listEscopo' => $listEscopo,
    ]) ?>

</div>

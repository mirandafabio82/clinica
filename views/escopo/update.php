<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Escopo */

$this->title = 'Update Escopo: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Escopos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="escopo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Frs */

$this->title = 'Update Frs: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Frs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="frs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

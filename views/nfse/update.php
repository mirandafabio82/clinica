<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Nfse */

$this->title = 'Update Nfse: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Nfses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="nfse-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

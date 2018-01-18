<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LdPreliminar */

$this->title = 'Update Ld Preliminar: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Ld Preliminars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ld-preliminar-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

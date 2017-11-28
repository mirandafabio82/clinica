<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Executante */

$this->title = 'Update Executante: ' . $model->usuario_id;
$this->params['breadcrumbs'][] = ['label' => 'Executantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->usuario_id, 'url' => ['view', 'id' => $model->usuario_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="executante-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

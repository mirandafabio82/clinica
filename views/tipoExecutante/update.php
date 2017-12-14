<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoExecutante */

$this->title = 'Tipos de Executante: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Executantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tipo-executante-update">

   
    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>

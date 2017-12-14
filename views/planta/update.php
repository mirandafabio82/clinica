<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Planta */

$this->title = 'Editar Planta: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Plantas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="planta-update">

   
    <?= $this->render('_form', [
        'model' => $model,
        'listSites' => $listSites,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>

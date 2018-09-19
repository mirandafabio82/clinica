<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Executante */

$this->title = 'Executante: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Executantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->usuario_id, 'url' => ['view', 'id' => $model->usuario_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="executante-update">

  
    <?= $this->render('_form', [
        'model' => $model,
        'user' => $user,
        'listTipos' => $listTipos,
        'listDisc' => $listDisc,
        'listCargo' => $listCargo,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>

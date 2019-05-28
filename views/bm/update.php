<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bm */

$this->params['breadcrumbs'][] = ['label' => 'Bms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bm-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        'listProjetos' => $listProjetos,
        'bmescopos' => $bmescopos,
        'valor_total' => $valor_total,
        'bm_executantes' => $bm_executantes,
        'frs_nfse_pagamento' => $frs_nfse_pagamento,
        'frsModel' => $frsModel,
        'nfseModel' => $nfseModel,
        'pagamentoModel' => $pagamentoModel
    ]) ?>

</div>

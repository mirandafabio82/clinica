<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TipoExecutante */

$this->title = 'Tipo de Executante';
$this->params['breadcrumbs'][] = ['label' => 'Tipo Executantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-executante-create">

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>

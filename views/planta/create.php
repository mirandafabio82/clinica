<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Planta */

$this->title = 'Planta';
$this->params['breadcrumbs'][] = ['label' => 'Plantas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="planta-create">

  
    <?= $this->render('_form', [
        'model' => $model,
        'listSites' => $listSites,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>

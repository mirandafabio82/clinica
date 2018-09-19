<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Executante */

$this->title = 'Executante';
$this->params['breadcrumbs'][] = ['label' => 'Executantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="executante-create">

  
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

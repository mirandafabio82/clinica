<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Paciente */

$this->title = 'Paciente';
$this->params['breadcrumbs'][] = ['label' => 'Pacientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paciente-create">

  
    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'listRaca' => $listRaca,
        'listStatusCivil' => $listStatusCivil,
    ]) ?>

</div>

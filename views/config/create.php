<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Config */

$this->title = 'Configuração';
$this->params['breadcrumbs'][] = ['label' => 'Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-create">

  
    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>

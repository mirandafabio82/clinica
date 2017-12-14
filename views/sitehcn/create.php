<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Site */

$this->title = 'Novo Site';
$this->params['breadcrumbs'][] = ['label' => 'Sites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-create">

  
    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>

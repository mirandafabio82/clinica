<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Bm */
$this->params['breadcrumbs'][] = ['label' => 'Bms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bm-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        'listProjetos' => $listProjetos,
    ]) ?>

</div>

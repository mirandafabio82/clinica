<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Nfse */

$this->title = 'Create Nfse';
$this->params['breadcrumbs'][] = ['label' => 'Nfses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nfse-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

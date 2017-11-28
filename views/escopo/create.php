<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Escopo */

$this->title = 'Create Escopo';
$this->params['breadcrumbs'][] = ['label' => 'Escopos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="escopo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

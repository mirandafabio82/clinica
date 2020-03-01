<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TratamentoPlanejamento */

$this->title = 'Create Tratamento Planejamento';
$this->params['breadcrumbs'][] = ['label' => 'Tratamento Planejamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tratamento-planejamento-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

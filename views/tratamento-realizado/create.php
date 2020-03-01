<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TratamentoRealizado */

$this->title = 'Create Tratamento Realizado';
$this->params['breadcrumbs'][] = ['label' => 'Tratamento Realizados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tratamento-realizado-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

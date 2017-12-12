<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Escopopadrao */

$this->title = 'Create Escopopadrao';
$this->params['breadcrumbs'][] = ['label' => 'Escopopadraos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="escopopadrao-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

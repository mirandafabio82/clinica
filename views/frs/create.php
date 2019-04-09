<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Frs */

$this->title = 'Create Frs';
$this->params['breadcrumbs'][] = ['label' => 'Frs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

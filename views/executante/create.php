<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Executante */

$this->title = 'Executante';
$this->params['breadcrumbs'][] = ['label' => 'Executantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="executante-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'user' => $user,
        'listTipos' => $listTipos
    ]) ?>

</div>

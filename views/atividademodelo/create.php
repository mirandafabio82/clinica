<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Atividademodelo */

$this->title = 'Modelo de Atividade';
$this->params['breadcrumbs'][] = ['label' => 'Atividademodelos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="atividademodelo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'listEscopo' => $listEscopo
    ]) ?>

</div>

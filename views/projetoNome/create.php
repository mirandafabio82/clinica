<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProjetoNome */

$this->title = 'Create Projeto Nome';
$this->params['breadcrumbs'][] = ['label' => 'Projeto Nomes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projeto-nome-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

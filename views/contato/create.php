<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Contato */

$this->title = 'Contato';
$this->params['breadcrumbs'][] = ['label' => 'Contatos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contato-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'user' => $user,
        'listClientes' => $listClientes
    ]) ?>

</div>

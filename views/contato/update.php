<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Contato */

$this->title = 'Contato: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Contatos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->usuario_id, 'url' => ['view', 'id' => $model->usuario_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="contato-update">

 
    <?= $this->render('_form', [
        'model' => $model,
        'user' => $user,
        'listClientes' => $listClientes,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Contato */

$this->title = 'Contato';
$this->params['breadcrumbs'][] = ['label' => 'Contatos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contato-create">

   
    <?= $this->render('_form', [
        'model' => $model,
        'user' => $user,
        'listClientes' => $listClientes,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>

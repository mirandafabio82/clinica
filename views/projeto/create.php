<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Projeto */

$this->title = 'Projeto';
$this->params['breadcrumbs'][] = ['label' => 'Projetos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projeto-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'listClientes' => $listClientes,
        'listContatos' => $listContatos,
        'listSites' => $listSites,
        'listNomes' => $listNomes,
        'listStatus' => $listStatus
    ]) ?>

</div>

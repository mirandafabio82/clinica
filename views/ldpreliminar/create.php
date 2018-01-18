<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LdPreliminar */

$this->title = 'Create Ld Preliminar';
$this->params['breadcrumbs'][] = ['label' => 'Ld Preliminars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ld-preliminar-create">

   
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

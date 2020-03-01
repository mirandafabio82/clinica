<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Documento */

$this->title = 'Create Documento';
$this->params['breadcrumbs'][] = ['label' => 'Documentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documento-create">

  
    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'listTipoDoc' => $listTipoDoc
    ]) ?>

</div>

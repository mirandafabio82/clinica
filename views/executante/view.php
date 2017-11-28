<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Executante */

$this->title = $model->usuario_id;
$this->params['breadcrumbs'][] = ['label' => 'Executantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="executante-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->usuario_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->usuario_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'usuario_id',
            'tipo_executante_id',
            'nome',
            'cidade',
            'uf',
            'cpf',
            'email:email',
            'telefone',
            'celular',
            'criado',
            'modificado',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Paciente */

$this->title = $model->id_paciente;
$this->params['breadcrumbs'][] = ['label' => 'Pacientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paciente-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_paciente], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_paciente], [
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
            'id_paciente',
            'nome',
            'telefone',
            'celular',
            'nascimento',
            'rg',
            'cpf',
            'profissao_empresa',
            'cor_pele',
            'indicacao',
            'endereco',
            'nacionalidade',
            'naturalidade',
            'estado_civil',
            'nome_mae',
            'nome_pai',
        ],
    ]) ?>

</div>

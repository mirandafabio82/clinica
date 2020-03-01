<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PacienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pacientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paciente-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Paciente', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_paciente',
            'nome',
            'telefone',
            'celular',
            'nascimento',
            //'rg',
            //'cpf',
            //'profissao_empresa',
            //'cor_pele',
            //'indicacao',
            //'endereco',
            //'nacionalidade',
            //'naturalidade',
            //'estado_civil',
            //'nome_mae',
            //'nome_pai',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

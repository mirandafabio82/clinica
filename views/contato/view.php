<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Contato */

$this->title = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.$model->usuario_id)->queryScalar();;
$this->params['breadcrumbs'][] = ['label' => 'Contatos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contato-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->usuario_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Excluir', ['delete', 'id' => $model->usuario_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
<div class="box box-primary">
        <div class="box-header with-border">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'usuario_id',
            'cliente_id',
            'nome',
            'tratamento',
            'site',
            'contato',
            'setor',
            'email:email',
            'telefone',
            'celular',
            'criado',
            'modificado',
        ],
    ]) ?>
</div>
</div>
</div>

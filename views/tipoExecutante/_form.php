<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\TipoExecutante */
/* @var $form yii\widgets\ActiveForm */
?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-vcard"></i> Especialidades'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{update} {delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            // 'id',
            'cargo',
            'codigo',
            'valor_hora',
            'valor_pago',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<div class="tipo-executante-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
        <div class="box-header with-border">

    <?= $form->field($model, 'cargo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'valor_hora')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_pago')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

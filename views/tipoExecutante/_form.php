<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\TipoExecutante */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->registerJs("

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(e.target == this)
            location.href = '" . Url::to(['tipoexecutante/update']) . "&id='+id;
    });

");
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
              'template' => '{delete}',    
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
    <div class="row">
        <div class="col-md-2">
    <?= $form->field($model, 'cargo')->textInput(['maxlength' => true]) ?>
    </div>
        <div class="col-md-2">
    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>
    </div>
        <div class="col-md-2">
    <?= $form->field($model, 'valor_hora')->textInput(['maxlength' => true]) ?>
</div>
        <div class="col-md-2">
    <?= $form->field($model, 'valor_pago')->textInput(['maxlength' => true]) ?>
</div>
</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

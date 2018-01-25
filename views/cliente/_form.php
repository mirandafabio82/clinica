<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */
?>
<!-- mask so funciona com isso -->
<?php $this->head() ?>
<?php
$this->registerJs("

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
            if(e.target == this)
                location.href = '" . Url::to(['cliente/update']) . "&id='+id;
        }
    });

");
?>
<style>
.table-bordered > tbody > tr > td{
  padding-top: 0px !important;
  padding-bottom: 0px !important;
}
.pagination{
    margin: 0px;
}

.summary{
  display: none;
}
</style>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,        
        'options' => ['style' => 'font-size:12px;'],
        'hover' => true,
        /*'rowOptions' => function ($model, $key, $index, $grid) {
                return ['id' => $model['id'], 'onclick' => 'window.location = "index.php?r=cliente/update&id="+this.id'];
        },*/
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-handshake-o"></i> Clientes'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
        
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            
            'nome',
            'site',
            'cnpj',
            'uf',
            'cidade',
            
            // 'modificado',

            // ['class' => 'yii\grid\ActionColumn', 'header' => 'Ações'],
        ],
    ]); ?>
<div class="cliente-form">
    <div class="box box-primary">
        <div class="box-header with-border">
           <!-- <h3 class="box-title">Cadastro de Cliente</h3> -->
           <div class="cliente-form">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-4">           
                    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true])  ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true])  ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-2">
                    <?= $form->field($model, 'cnpj')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99.999.999/9999-99',
                    ]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'uf')->textInput(['maxlength' => true, 'style' => 'text-transform: uppercase']) ?>            
                </div>   
                <div class="col-md-2">
                    <?= $form->field($model, 'cidade')->textInput(['maxlength' => true])  ?>
                </div>

                 <div class="col-md-2">
                    <?= $form->field($model, 'bairro')->textInput(['maxlength' => true])  ?>
                </div>

                <div class="col-md-2">
                    <?= $form->field($model, 'cep')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99999-999',
                    ]) ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'endereco')->textInput(['maxlength' => true])  ?>
                </div>

                
                <div class="col-md-2">
                    <?= $form->field($model, 'insc_municipal')->textInput(['maxlength' => true])  ?>
                </div>

                <div class="col-md-2">
                    <?= $form->field($model, 'logo')->fileInput(); ?>
                </div>

            </div>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

   <?php ActiveForm::end(); ?>  
    
</div>
</div>

</div>
</div>
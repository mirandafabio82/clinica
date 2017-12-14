<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */
?>
<!-- mask so funciona com isso -->
<?php $this->head() ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,        
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-handshake-o"></i> Clientes'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nome',
            'site',
            'cnpj',
            'cidade',
            'uf',
            'criado',
            // 'modificado',

            ['class' => 'yii\grid\ActionColumn', 'header' => 'Ações'],
        ],
    ]); ?>
<div class="cliente-form">
    <div class="box box-primary">
        <div class="box-header with-border">
           <!-- <h3 class="box-title">Cadastro de Cliente</h3> -->
           <div class="cliente-form">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-3">           
                    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
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
                    <?= $form->field($model, 'uf')->textInput(['maxlength' => true]) ?>            
                </div>   
                <div class="col-md-2">
                    <?= $form->field($model, 'cidade')->textInput(['maxlength' => true])  ?>
                </div>

                 <div class="col-md-12">
                    <?= $form->field($model, 'endereco')->textInput(['maxlength' => true])  ?>
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
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Contato */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $this->head() ?>

<div class="contato-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
        <div class="box-header with-border">
        <div class="row">       
        <div class="col-md-6">
            <?= $form->field($model, 'cliente_id')->dropDownList($listClientes,['prompt'=>'Selecione um Cliente']) ?>
        
            <?= $form->field($user, 'nome')->textInput(['maxlength' => true]) ?>
        
            <?= $form->field($model, 'tratamento')->textInput(['maxlength' => true]) ?>
        
            <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>
        
            <?= $form->field($model, 'contato')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'setor')->textInput(['maxlength' => true]) ?>
       
            <?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>
        
            <?= $form->field($model, 'telefone')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '(99) 9999-9999',
                    ]) ?>
        
            <?= $form->field($model, 'celular')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '(99) 99999-9999',
                    ]) ?>    
        </div>  
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

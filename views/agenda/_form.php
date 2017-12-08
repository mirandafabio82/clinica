<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Agenda */
/* @var $form yii\widgets\ActiveForm */
?>

<!-- mask so funciona com isso -->
<?php $this->head() ?>

<div class="agenda-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
    <div class="box-header with-border">
    <div class="row">
    <div class="col-md-4">    
        <?= $form->field($model, 'projeto_id')->dropDownList($listProjetos,['prompt'=>'Selecione um Projeto']) ?>
    
        <?= $form->field($model, 'data')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
    
        <?= $form->field($model, 'local')->dropDownList($listSites,['prompt'=>'Selecione um Site']) ?>
    
        <?= $form->field($model, 'quem')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4"> 
        <?= $form->field($model, 'assunto')->textInput(['maxlength' => true]) ?>
    
        <?= $form->field($model, 'hr_inicio')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99:99:99',
                    ]) ?>
    
        <?= $form->field($model, 'hr_final')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99:99:99',
                    ]) ?>
    
        <?= $form->field($model, 'status')->dropDownList($listStatus) ?>
    </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

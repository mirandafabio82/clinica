<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Executante */
/* @var $form yii\widgets\ActiveForm */
?>

<!-- mask so funciona com isso -->
<?php $this->head() ?>

<div class="executante-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
        <div class="box-header with-border">
    <div class="row">       
    <div class="col-md-6">


    <div id="tipoExecutantes">
    <label>Funções</label>
    <br>
     <?php     
        foreach ($listTipos as $key => $tipo) { ?>
           <input type="checkbox" name="Tipos[<?=$key?>]" value="<?= $key?>"><?= $tipo ?>
        <?php } ?>
        
    </div>

    <?= $form->field($user, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uf')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'cidade')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'endereco')->textInput(['maxlength' => true]) ?>

    </div>
    <div class="col-md-6">

    <?= $form->field($model, 'cpf')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '999.999.999-99',
                    ]) ?>
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
    <?php ActiveForm::end(); ?>

</div>

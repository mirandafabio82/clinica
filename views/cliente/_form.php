<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */
?>
<!-- mask so funciona com isso -->
<?php $this->head() ?>

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

            </div>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

                <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
</div>


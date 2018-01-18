<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LdPreliminar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ld-preliminar-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">   
    <div class="col-md-2"> 
		<?= $form->field($model, 'hcn')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2"> 
    	<?= $form->field($model, 'cliente')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-8"> 
    	<?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>
    </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

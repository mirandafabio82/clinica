<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Tarefa */
/* @var $form yii\widgets\ActiveForm */
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

#w2{
    display: none;
}
</style>
<?php
$this->registerJs("

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
          if(e.target == this)
              location.href = '" . Url::to(['tarefa/update']) . "&id='+id;
        }
    });

   
");
?>
<!-- mask so funciona com isso -->
<?php $this->head() ?>
<div class="box box-primary">
    <div class="box-header with-border">

<div style="margin-bottom:1em">
    <?= Html::a('Mostrar Todos', ['/tarefa/create', 'pagination' => true], ['class'=>'btn btn-primary grid-button']) ?>
</div>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['style' => 'font-size:12px;'],   
        'columns' => [
            
            'projeto_id',
            'executante_id',
            'atividade_id:ntext',
            'data',
            'horas_as',
            'horas_executada',
            'horas_acumulada',
            'horas_saldo',
            //'descricao:ntext',
            //'criado',
            //'modificado',

        ],
    ]); ?>
</div>
</div>
<div class="tarefa-form">


    <?php $form = ActiveForm::begin(); ?>
<div class="box box-primary">
        <div class="box-header with-border">
    <div class="row">       
    <div class="col-md-3">
        <?= $form->field($model, 'executante_id')->dropDownList($listExecutantes,['prompt'=>'Selecione um Executante']) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'projeto_id')->dropDownList($listProjetos,['prompt'=>'Selecione um Projeto']) ?>  
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'atividade_id')->textInput(['maxlength' => true])  ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'data')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'horas_as')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'horas_executada')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'horas_acumulada')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'horas_saldo')->textInput(['maxlength' => true]) ?>
    </div>
    <!-- <div class="col-md-2">
    <?//= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>
    </div> -->
   
</div>
    <div class="form-group">
        <?= Html::submitButton('Cadastrar', ['class' => 'btn btn-success']) ?>
    </div>
</div>
</div>
    <?php ActiveForm::end(); ?>

</div>

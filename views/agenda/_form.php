<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Agenda */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->registerJs("

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
            if(e.target == this)
                location.href = '" . Url::to(['agenda/update']) . "&id='+id;
        }
    });

");
?>
<style>
.table-bordered > tbody > tr > td{
  padding-top: 0px !important;
  padding-bottom: 0px !important;
}
</style>
<!-- mask so funciona com isso -->
<?php $this->head() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['style' => 'font-size:12px;'],
        'pjax' => true,
        
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-calendar"></i> Agenda'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            
            [
                'attribute' => 'projeto_id',
                'value' => function($data){
                    if(isset($data->projeto_id))
                    return Yii::$app->db->createCommand('SELECT nome FROM projeto WHERE id='.$data->projeto_id)->queryScalar();
                }
            ],            
            [
              'attribute' => 'status',      
              'class' => 'kartik\grid\EditableColumn',        
              'format' => 'raw',
              'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
               'value' => function ($data) {

                $status = Yii::$app->db->createCommand('SELECT status, cor FROM agenda_status WHERE id='.$data->status)->queryOne();
                
               return '<span style="color:'.$status['cor'].' "><i class="fa fa-circle" aria-hidden="true"></i> '.$status['status'].'</span>';

               },
                'editableOptions' => [
              'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
              'data' => $listStatus                
              ]
            ],
            [
                'attribute' => 'data',
                'value' => function($data){
                    
                    return date_format(DateTime::createFromFormat('Y-m-d', $data->data), 'd/m/Y');
                }
            ], 
            [
                'attribute' => 'site',
                'value' => function($data){
                    if(isset($data->local) && !empty($data->local))
                    return Yii::$app->db->createCommand('SELECT nome FROM site WHERE id='.$data->local)->queryScalar();
                }
            ],         
            'quem',
            'assunto',
            'hr_inicio',
            'hr_final',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<div class="agenda-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
    <div class="box-header with-border">
    <div class="row">
    <div class="col-md-3">    
        <?= $form->field($model, 'projeto_id')->dropDownList($listProjetos,['prompt'=>'Selecione um Projeto']) ?>
    
        <?= $form->field($model, 'data')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
    
        </div>
        <div class="col-md-3"> 
        <?= $form->field($model, 'local')->dropDownList($listSites,['prompt'=>'Selecione um Site']) ?>
    
        <?= $form->field($model, 'quem')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3"> 
        <?= $form->field($model, 'assunto')->textInput(['maxlength' => true]) ?>
    
        <?= $form->field($model, 'hr_inicio')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99:99:99',
                    ]) ?>
        </div>
        <div class="col-md-3"> 
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

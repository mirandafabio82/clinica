<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Agenda */
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
            'heading' => '<i class="fa fa-calendar"></i> Agenda'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{update} {delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            'id',
            [
                'attribute' => 'projeto_id',
                'value' => function($data){
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
            ],
            'data',
            [
                'attribute' => 'site',
                'value' => function($data){
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

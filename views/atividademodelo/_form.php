<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Atividademodelo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="atividademodelo-form">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar' =>  [
        ['content' => Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['class' => 'btn btn-success'])
        ],
          '{export}',
          '{toggleData}',
        ],
        'export' => [
          'fontAwesome' => true
        ],
        'pjax' => true,        
        
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-map"></i> Atividades Modelo'
        ],
        'columns' => [
            
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{update} {delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            'id',
            'nome',
            [
                'attribute'=>'isPrioritaria',
                'value'=>function($data){
                    return $data->isPrioritaria==1 ? 'Sim' : 'Não';
                }
            ],
            [
                'attribute'=>'isEntregavel',
                'value'=>function($data){
                    return $data->isPrioritaria==1 ? 'Sim' : 'Não';
                }
            ],

        ],
    ]); ?>
    <?php $form = ActiveForm::begin(); ?>
     <div class="box box-primary">
    <div class="box-header with-border">
     
    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'escopopadrao_id')->dropDownList($listEscopo,['prompt'=>'Selecione um Escopo']) ?>
    <?= $form->field($model, 'disciplina_id')->dropDownList($listDisciplina,['prompt'=>'Selecione uma Displina']) ?>
    <input type="checkbox" name="Atividademodelo[isPrioritaria]">Atividade Prioritária
    <br>
    <input type="checkbox" name="Atividademodelo[isEntregavel]">Atividade Entregável

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
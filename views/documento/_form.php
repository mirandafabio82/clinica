<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Documento */
/* @var $form yii\widgets\ActiveForm */
?>
<!-- mask so funciona com isso -->
<?php $this->head() ?>
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
</style>
<?php
$this->registerJs("

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
          if(e.target == this)
              location.href = '" . Url::to(['documento/update']) . "&id='+id;
        }
    });

");
?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['style' => 'font-size:12px;'],
        'pjax' => true,
        
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-file"></i> Documentos'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],

            
            [
                'attribute' => 'path',
                'format' => 'raw',
                'value' => function($data){
                    $nome = Yii::$app->db->createCommand('SELECT path FROM documento WHERE id ='.$data->id)->queryScalar(); 
                    return Html::a(
                        $nome, 
                        Yii::$app->basePath.'/web/uploaded-files/'.$data->projeto_id.'/'.$data->path,
                         [                                 // link options
                         'title'=>'Go!',
                         'target'=>'_blank',
                         'class' => 'linksWithTarget',
                         'data-pjax'=>"0"
                       ]
                        );
                }
            ],
            [
                'attribute' => 'projeto_id',
                'format' => 'raw',
                'value' => function($data){
                    return Yii::$app->db->createCommand('SELECT nome FROM projeto WHERE id ='.$data->projeto_id)->queryScalar();
                },
            ],
            [
                'header' => 'Área',
                'format' => 'raw',
                'value' => function($data){
                    $area = Yii::$app->db->createCommand('SELECT planta FROM projeto WHERE id ='.$data->projeto_id)->queryScalar(); 
                    return $area;

                }
            ],
            [
                'header' => 'Descrição do Projeto',
                'format' => 'raw',
                'value' => function($data){
                    return Yii::$app->db->createCommand('SELECT descricao FROM projeto WHERE id ='.$data->projeto_id)->queryScalar();
                },
            ],

            'revisao',
            [
                'attribute' => 'data',
                'value' => function($data){
                    if(!empty($data->data))
                        return date_format(DateTime::createFromFormat('Y-m-d', $data->data), 'd/m/Y');
                }
            ], 
            'tipo',
            // 'criado',
            // 'modificado',

            
        ],
    ]); ?>
<div class="documento-form">

<div class="box box-primary">
        <div class="box-header with-border">    
    <?php $form = ActiveForm::begin(); ?>

     <div class="row">       
        <div class="col-md-4">
    <?= $form->field($model, 'projeto_id')->dropDownList($listProjetos,['prompt'=>'Selecione um Projeto']) ?>
 
    <?= $form->field($model, 'path')->fileInput(); ?>
    </div>
    <div class="col-md-2">
    <?= $form->field($model, 'revisao')->textInput() ?>   
    
    <?= $form->field($model, 'data')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
    </div>
    <div class="col-md-2">

    <?= $form->field($model, 'tipo')->textInput(['maxlength' => true]) ?>
     </div>
    </div>
   
    
<br>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
</div>
</div>

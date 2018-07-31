<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Atividademodelo */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->registerJs("
    $( document ).ready(function() {
        window.scrollTo(0, 50000);
    });
    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
            if(e.target == this)
                location.href = '" . Url::to(['atividademodelo/update']) . "&id='+id;
        }
    });

");
?>
<style>
.table-bordered > tbody > tr > td{
  padding-top: 3px !important;
  padding-bottom: 3px !important;
}

.table-striped > tbody > tr:nth-of-type(odd){
  background-color: #b6b6b6 !important;
}
.pagination{
    margin: 0px;
}

/*.summary{
  display: none;
}

#w2{
    display: none;
}*/
</style>



<div class="box box-primary">
    <div class="box-header with-border">
    <div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-tablet"></i> Atividades Modelo </div>
<div style="margin-bottom:1em;margin-top: 1em">
    <?= Html::a('Mostrar Todos', ['/atividademodelo/create', 'pagination' => true], ['class'=>'btn btn-primary grid-button']) ?>
</div>
<div class="atividademodelo-form">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'options' => ['style' => 'font-size:12px;'],
        'showHeader' => true,
        // 'pjax' => true,        
        
       /* 'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-map"></i> Atividades Modelo'
        ],*/
        'columns' => [
            
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => ' {delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            
            'nome',
            [
                'attribute'=>'disciplina_id',
                'value'=>function($data){
                    if(isset($data->disciplina_id))
                        return Yii::$app->db->createCommand('SELECT nome FROM disciplina WHERE id='.$data->disciplina_id)->queryScalar();
                }
            ],
            [
                'attribute'=>'escopopadrao_id',
                'value'=>function($data){
                    if(isset($data->escopopadrao_id))
                        return Yii::$app->db->createCommand('SELECT nome FROM escopopadrao WHERE id='.$data->escopopadrao_id)->queryScalar();
                }
            ],
            [
                'attribute'=>'isPrioritaria',
                'value'=>function($data){
                    return $data->isPrioritaria==1 ? 'Sim' : 'Não';
                }
            ],
            [
                'attribute'=>'isEntregavel',
                'value'=>function($data){
                    return $data->isEntregavel==1 ? 'Sim' : 'Não';
                }
            ],
            'ordem',

        ],
    ]); ?>
</div>
</div>
    <?php $form = ActiveForm::begin(); ?>
     <div class="box box-primary">
    <div class="box-header with-border">
     <div class="row">
         <div class="col-md-4">  
        <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2"> 
        <?= $form->field($model, 'disciplina_id')->dropDownList($listDisciplina,['prompt'=>'Selecione uma Displina']) ?>
        </div>
        <div class="col-md-2"> 
        <?= $form->field($model, 'escopopadrao_id')->dropDownList($listEscopo,['prompt'=>'Selecione um Escopo']) ?>
        </div>
        <div class="col-md-2"> 
        <?= $form->field($model, 'ordem')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2"> 
        <?= $form->field($model,'isPrioritaria')->checkBox() ?>        
        <?= $form->field($model,'isEntregavel')->checkBox() ?>
        </div>
    </div>
    <!-- <input type="checkbox" name="Atividademodelo[isPrioritaria]">Atividade Prioritária
    <br>
    <input type="checkbox" name="Atividademodelo[isEntregavel]">Atividade Entregável
 -->
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Salvar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
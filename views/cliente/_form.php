<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */
?>
<!-- mask so funciona com isso -->
<?php $this->head() ?>
<?php
$this->registerJs("
    $( document ).ready(function() {
        document.title = 'Clientes';
    });

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
            if(e.target == this)
                location.href = '" . Url::to(['cliente/update']) . "&id='+id;
        }
    });
    $('.dropify').dropify({
        tpl: {
        message:         '<div class=\'dropify-message\'><span class=\'file-icon\' /> <p>Clique para adicionar uma foto </p></div>',
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
.dropify-wrapper.touch-fallback .dropify-clear {
  display:none;
}
.dropify-wrapper.touch-fallback .dropify-infos {
  display:none;
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

<div class="box box-primary">
    <div class="box-header with-border">
<div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-handshake-o"></i> Clientes </div>
<div style="margin-bottom:1em;margin-top: 1em">
    <?= Html::a('Mostrar Todos', ['/cliente/create', 'pagination' => true], ['class'=>'btn btn-primary grid-button']) ?>
</div>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        // 'pjax' => true,        
        'options' => ['style' => 'font-size:12px;'],
        'rowOptions' => function ($model, $key, $index, $grid) {
                    return [
                        'style' => "cursor: pointer",
                        
                    ];
                },
        // 'hover' => true,
        /*'rowOptions' => function ($model, $key, $index, $grid) {
                return ['id' => $model['id'], 'onclick' => 'window.location = "index.php?r=cliente/update&id="+this.id'];
        },*/
        /*'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-handshake-o"></i> Clientes'
        ],*/
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
        
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            
            'nome',
            'site',
            'cnpj',
            'uf',
            'cidade',
            
            // 'modificado',

            // ['class' => 'yii\grid\ActionColumn', 'header' => 'Ações'],
        ],
    ]); ?>
</div>
</div>



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
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true])  ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true])  ?>
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
                    <?= $form->field($model, 'uf')->textInput(['maxlength' => true, 'style' => 'text-transform: uppercase']) ?>            
                </div>   
                <div class="col-md-2">
                    <?= $form->field($model, 'cidade')->textInput(['maxlength' => true])  ?>
                </div>

                 <div class="col-md-2">
                    <?= $form->field($model, 'bairro')->textInput(['maxlength' => true])  ?>
                </div>

                <div class="col-md-2">
                    <?= $form->field($model, 'cep')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99999-999',
                    ]) ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'endereco')->textInput(['maxlength' => true])  ?>
                </div>

                
                <div class="col-md-2">
                    <?= $form->field($model, 'insc_municipal')->textInput(['maxlength' => true])  ?>
                </div>

                <div class="col-md-2">
                    <?php
                          if($model->isNewRecord)
                          echo $form->field($model, 'logo')->fileInput(['class'=>'dropify']);
                          else{
                            echo $form->field($model, 'logo')->fileInput(['class'=>'dropify','data-default-file'=> Url::base()."/logos/".$model['logo']]);
                          } 
                        ?>
                </div>

            </div>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Salvar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

   <?php ActiveForm::end(); ?>  
    
</div>
</div>

</div>
</div>
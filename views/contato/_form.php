<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Contato */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('

  $( document ).ready(function() {    
    console.log("aa");
    $("#dbuser-password").text("");
  });
');
?>
<?php
$this->registerJs("

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
          if(e.target == this)
              location.href = '" . Url::to(['contato/update']) . "&id='+id;
        }
    });

");
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

<?php $this->head() ?>
<div class="box box-primary">
    <div class="box-header with-border">

<div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-address-book"></i> Contatos </div>
<div style="margin-bottom:1em;margin-top: 1em">
    <?= Html::a('Mostrar Todos', ['/contato/create', 'pagination' => true], ['class'=>'btn btn-primary grid-button']) ?>
</div>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        // 'pjax' => true,
        'options' => ['style' => 'font-size:12px;'],
        
        /*'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-address-book"></i> Contatos'
        ],*/
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            [
              'header' => '<span style="color:#337ab7">Nome</span>',
              'attribute' => 'user',              
              'format' => 'raw',
               'value' => function ($data) {
                 if(isset($data->usuario_id) && !empty($data->usuario_id))
                   return Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.$data->usuario_id)->queryScalar();
               },
            ],         
            [
              'header' => '<span style="color:#337ab7">Cliente</span>',
              'attribute' => 'cliente',              
              'format' => 'raw',
               'value' => function ($data) {
                 if(isset($data->cliente_id) && !empty($data->cliente_id))
                   return Yii::$app->db->createCommand('SELECT nome FROM cliente WHERE id='.$data->cliente_id)->queryScalar();
               },
            ],
            [
              'header' => '<span style="color:#337ab7">Email</span>',              
              'format' => 'raw',
               'value' => function ($data) {

                   return Yii::$app->db->createCommand('SELECT email FROM user WHERE id='.$data->usuario_id)->queryScalar();
               },
            ],
            'telefone',
            'celular',
            
            // 'modificado',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>

<div class="contato-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
        <div class="box-header with-border">
        <div class="row">       
        <div class="col-md-4">
            <?= $form->field($model, 'cliente_id')->dropDownList($listClientes,['prompt'=>'Selecione um Cliente']) ?>
        </div>
        <div class="col-md-3">    
            <?= $form->field($user, 'nome')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">       
            <?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($user, 'password')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'setor')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'tratamento')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'contato')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'telefone')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '(99) 9999-9999',
                    ]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'celular')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '(99) 99999-9999',
                    ]) ?>    
        </div>  
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Salvar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

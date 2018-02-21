<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Bm */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('

    $("#bm-projeto_id").change(function (e) {
    	var id = $(this).val();
	console.log(id);
	
       $.ajax({ 
      url: "index.php?r=bm/preencheform",
      data: {id: id},
      type: "POST",
      success: function(response){
       console.log(response);
       var resposta = $.parseJSON(response);
       $("#bm-objeto").val(resposta["setor"]);
       $("#bm-descricao").val(resposta["nome"]+"\nDescrição: "+resposta["descricao"]+"\n"+resposta["proposta"]+"\nSite: "+resposta["site"]);
       $("#bm-acumulado").val(resposta[1]);
       $("#bm-saldo").val(resposta[0]);
       
     },
     error: function(){
      console.log("failure");
    }
  });
    });

    $("td").click(function (e) {
        var id = $(this).closest("tr").attr("data-key");
        if(id != null){
            if(e.target == this)
                location.href = "' . Url::to(["bm/update"]) . '&id="+id;
        }
    });

');
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
<!-- mask so funciona com isso -->
<?php $this->head() ?>

<div class="bm-form">
	<div class="box box-primary">
    <div class="box-header with-border">
    <div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-list"></i> BM </div>
<div style="margin-bottom:1em;margin-top: 1em">
    <?= Html::a('Mostrar Todos', ['/bm/create', 'pagination' => true], ['class'=>'btn btn-primary grid-button']) ?>
</div>
    		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
        	[
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            [
                'attribute'=>'projeto_id',
                'value'=>function($data){
                    if(isset($data->projeto_id))
                        return Yii::$app->db->createCommand('SELECT nome FROM projeto WHERE id='.$data->projeto_id)->queryScalar();
                }
            ],
            [
                'header'=>'Descrição do Projeto',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){  
                 	if(isset($data->projeto_id))                  
                        return Yii::$app->db->createCommand('SELECT descricao FROM projeto WHERE id='.$data->projeto_id)->queryScalar();
                }
            ],
            // 'contrato',
            'objeto',
            // 'contratada',
            //'cnpj',
            //'contato',
            'numero_bm',
            // 'data',
            //'de',
            //'para',
            //'descricao:ntext',

            
        ],
    ]); ?>
    </div>
    </div>
    	<div class="box box-primary">
    <div class="box-header with-border">
    <?php if($model->isNewRecord){ ?>
    <?php $form = ActiveForm::begin(); ?>
    	<div class="row">
	    <div class="col-md-3">  
	    <?= $form->field($model, 'projeto_id')->dropDownList($listProjetos,['prompt'=>'Selecione um Projeto']) ?>
	    </div>
	    <div class="col-md-2">  
	    <?= $form->field($model, 'contrato')->textInput(['maxlength' => true, 'value' => '4600015210']) ?>
	    </div>
	    <div class="col-md-2"> 
	    <?= $form->field($model, 'objeto')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-md-3"> 
	    <?= $form->field($model, 'contratada')->textInput(['maxlength' => true, 'value' => 'HCN AUTOMAÇÃO LTDA']) ?>
 		</div>
	    <div class="col-md-2">  
	    <?= $form->field($model, 'cnpj')->textInput(['maxlength' => true, 'value' => '10.486.000/0001-05']) ?>
	    </div>
	    <div class="col-md-3"> 
	    <?= $form->field($model, 'contato')->textInput(['maxlength' => true, 'value' => 'HÉLDER CÂMARA DO NASCIMENTO']) ?>
	    </div>
	    <div class="col-md-1"> 
	    <?= $form->field($model, 'numero_bm')->textInput(['maxlength' => true, 'value' => Yii::$app->db->createCommand('SELECT ultimo_bm FROM config')->queryScalar()]) ?>
 		</div>
	    <div class="col-md-2">  
	    <?= $form->field($model, 'data')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
	    </div>
	    <div class="col-md-2"> 
	    <?= $form->field($model, 'de')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
	    </div>
	    <div class="col-md-2"> 
	    <?= $form->field($model, 'para')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
	    </div>
	     <div class="col-md-1"> 
	    <?= $form->field($model, 'acumulado')->textInput(['maxlength' => true]) ?>
 		</div>
    <div class="col-md-1"> 
      <?= $form->field($model, 'saldo')->textInput(['maxlength' => true]) ?>
    </div>
	    <div class="col-md-6"> 
	    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>
	    </div>
	</div>
    <?php } else{ ?>
	    <?php $form = ActiveForm::begin(); ?>
	    <div class="row">
	    <div class="col-md-3">  
	    <?= $form->field($model, 'projeto_id')->dropDownList($listProjetos,['prompt'=>'Selecione um Projeto']) ?>
	    </div>
	    <div class="col-md-2">  
	    <?= $form->field($model, 'contrato')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-md-2"> 
	    <?= $form->field($model, 'objeto')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-md-3"> 
	    <?= $form->field($model, 'contratada')->textInput(['maxlength' => true]) ?>
 		</div>
	    <div class="col-md-2">  
	    <?= $form->field($model, 'cnpj')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-md-3"> 
	    <?= $form->field($model, 'contato')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-md-1"> 
	    <?= $form->field($model, 'numero_bm')->textInput(['maxlength' => true]) ?>
 		</div>
	    <div class="col-md-2">  
	    <?= $form->field($model, 'data')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
	    </div>
	    <div class="col-md-2"> 
	    <?= $form->field($model, 'de')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
	    </div>
	    <div class="col-md-2"> 
	    <?= $form->field($model, 'para')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
	    </div>
	    <div class="col-md-1"> 
	    <?= $form->field($model, 'acumulado')->textInput(['maxlength' => true]) ?>
 		</div>
    <div class="col-md-1"> 
      <?= $form->field($model, 'saldo')->textInput(['maxlength' => true]) ?>
    </div>
	    <div class="col-md-6"> 
	    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>
	    </div>
	</div>
   <?php } ?>
    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-primary']) ?>
        <?php if(!$model->isNewRecord){ ?>
        <?= Html::a('<span class="btn-label">Visualizar BM</span>', ['gerarbm', 'id' => $model->id], ['class' => 'btn btn-primary', 'target'=>'_blank', 'style'=> ' margin-right: 1em']) ?>
        <?php } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>

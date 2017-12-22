<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\money\MaskMoney;
/* @var $this yii\web\View */
/* @var $model app\models\Executante */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->registerJs("

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(e.target == this)
            location.href = '" . Url::to(['executante/update']) . "&id='+id;
    });

    function preencheHora(elemento){
      var id = elemento.val();
      
      $.ajax({ 
          url: 'index.php?r=executante/preenchehora',
          data: {id: id},
          type: 'POST',
          success: function(response){          
            valor = response.split('\"')[1];
          if(id==1){
            $('#executante-vl_hh_tp-disp').val('R$ '+valor);
          } 
          else if(id==2){
            $('#executante-vl_hh_ej-disp').val('R$ '+valor);
          }
           else if(id==3){
            $('#executante-vl_hh_ep-disp').val('R$ '+valor);
          } 
           else if(id==4){
            $('#executante-vl_hh_es-disp').val('R$ '+valor);
          } 
           else{ //5
            $('#executante-vl_hh_ee-disp').val('R$ '+valor);
          }  
         },
         error: function(){
          console.log('failure');
        }
      });
    }

    $('.tipo-1').change(function(){
      
      if($(this).prop('checked')){
          $('#div_hh_tp').removeAttr('hidden');
          preencheHora($(this));
          
      }else{
        $('#div_hh_tp').attr('hidden', 'hidden');
      }
    });

     $('.tipo-2').change(function(){
      
      if($(this).prop('checked')){
      $('#div_hh_ej').removeAttr('hidden');
      preencheHora($(this));
      }else{
        $('#div_hh_ej').attr('hidden', 'hidden');
      }
    });

     $('.tipo-3').change(function(){
      
      if($(this).prop('checked')){
      $('#div_hh_ep').removeAttr('hidden');
      preencheHora($(this));
      }else{
        $('#div_hh_ep').attr('hidden', 'hidden');
      }
    });

     $('.tipo-4').change(function(){
      
      if($(this).prop('checked')){
      $('#div_hh_es').removeAttr('hidden');
      preencheHora($(this));
      }else{
        $('#div_hh_es').attr('hidden', 'hidden');
      }
    });

     $('.tipo-5').change(function(){
      
      if($(this).prop('checked')){
      $('#div_hh_ee').removeAttr('hidden');
      preencheHora($(this));
      }else{
        $('#div_hh_ee').attr('hidden', 'hidden');
      }
    });

");
?>
<!-- mask so funciona com isso -->
<?php $this->head() ?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'options' => ['style' => 'font-size:12px;'],
        
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-wrench"></i> Executantes'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            
            [
              'attribute' => 'usuario_id',              
              'format' => 'raw',
               'value' => function ($data) {

                   return Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.$data->usuario_id)->queryScalar();
               },
            ],
            [
              'header' => 'Tipo',              
              'format' => 'raw',
               'value' => function ($data) {
                  $executantes = Yii::$app->db->createCommand('SELECT cargo, codigo FROM tipo_executante JOIN executante_tipo ON tipo_executante.id=executante_tipo.tipo_id WHERE executante_tipo.executante_id='.$data->usuario_id)->queryAll();
                  $tipos = '';
                  foreach ($executantes as $key => $exec) {
                    $tipos .= $exec['cargo'].' ('.$exec['codigo'].'); ';
                  }

                   return $tipos;
               },
            ],
                       
            // 'cpf',
            [
              'header' => 'Email',              
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

<div class="executante-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
        <div class="box-header with-border">
    <div class="row">       
    <div class="col-md-4">


    <div id="tipoExecutantes">
    <label>Funções</label>
    <br>
     <?php     
        foreach ($listTipos as $key => $tipo) { ?>
           <input type="checkbox" name="Tipos[<?=$key?>]" value="<?= $key?>" class="tipo-<?=$key?>"><?= $tipo ?>
        <?php } ?>
        
    </div>
  </div>
    <div class="col-md-3">
    <?= $form->field($user, 'nome')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
    <?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
    <?= $form->field($user, 'password')->passwordInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
    <?= $form->field($model, 'cpf')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '999.999.999-99',
                    ]) ?>
    </div>
    <div class="col-md-2">
    <?= $form->field($model, 'cnpj')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99.999.999/9999-99',
                    ]) ?> 
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
    <div class="col-md-1">
    <?= $form->field($model, 'uf')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
    <?= $form->field($model, 'cidade')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-3">
    <?= $form->field($model, 'endereco')->textInput(['maxlength' => true]) ?>
    </div>
    
    <div class="col-md-3">   
    <?= $form->field($model, 'nome_empresa')->textInput(['maxlength' => true]) ?>  
    </div>
    <div class="col-md-2">
    <?= $form->field($model, 'insc_municipal')->textInput(['maxlength' => true]) ?>      
    </div>
    <div class="col-md-4">
    <?= $form->field($model, 'endereco_empresa')->textInput(['maxlength' => true]) ?>        
    </div>
    
   
    <div class="col-md-2" id="div_hh_tp" hidden>
    <?= $form->field($model, 'vl_hh_tp')->textInput(['maxlength' => true])->widget(MaskMoney::classname(), [
          'pluginOptions' => [
              'prefix' => 'R$ ',
              'thousands' => '.',
              'decimal' => ',',
              // 'suffix' => ' ¢',
              'allowNegative' => false

          ]
      ]); ?>
    </div>
    <div class="col-md-2" id="div_hh_ej" hidden>
    <?= $form->field($model, 'vl_hh_ej')->textInput(['maxlength' => true])->widget(MaskMoney::classname(), [
          'pluginOptions' => [
              'prefix' => 'R$ ',
              'thousands' => '.',
              'decimal' => ',',
              // 'suffix' => ' ¢',
              'allowNegative' => false

          ]
      ]); ?>
    </div>
    <div class="col-md-2" id="div_hh_ep" hidden>
    <?= $form->field($model, 'vl_hh_ep')->textInput(['maxlength' => true])->widget(MaskMoney::classname(), [
          'pluginOptions' => [
              'prefix' => 'R$ ',
              'thousands' => '.',
              'decimal' => ',',
              // 'suffix' => ' ¢',
              'allowNegative' => false

          ]
      ]); ?>
    </div>
    <div class="col-md-2" id="div_hh_es" hidden> 
    <?= $form->field($model, 'vl_hh_es')->textInput(['maxlength' => true])->widget(MaskMoney::classname(), [
          'pluginOptions' => [
              'prefix' => 'R$ ',
              'thousands' => '.',
              'decimal' => ',',
              // 'suffix' => ' ¢',
              'allowNegative' => false

          ]
      ]); ?>
    </div>
    <div class="col-md-2" id="div_hh_ee" hidden>
    <?= $form->field($model, 'vl_hh_ee')->textInput(['maxlength' => true])->widget(MaskMoney::classname(), [
          'pluginOptions' => [
              'prefix' => 'R$ ',
              'thousands' => '.',
              'decimal' => ',',
              // 'suffix' => ' ¢',
              'allowNegative' => false

          ]
      ]); ?>
    </div>
    <div class="col-md-2">
    <?= $form->field($model, 'vl_km')->textInput(['maxlength' => true])->widget(MaskMoney::classname(), [
          'pluginOptions' => [
              'prefix' => 'R$ ',
              'thousands' => '.',
              'decimal' => ',',
              // 'suffix' => ' ¢',
              'allowNegative' => false

          ]
      ]); ?>
    </div>
    <div class="col-md-2">
    <?= $form->field($model, 'qtd_km_dia')->textInput(['maxlength' => true]) ?>
    </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

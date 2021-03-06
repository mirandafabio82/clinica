<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\money\MaskMoney;
/* @var $this yii\web\View */
/* @var $model app\models\Executante */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
  .table-bordered>tbody>tr>td {
    padding-top: 3px !important;
    padding-bottom: 3px !important;
  }

  .table-striped>tbody>tr:nth-of-type(odd) {
    background-color: #b6b6b6 !important;
  }

  .pagination {
    margin: 0px;
  }

  .summary {
    display: none;
  }

  #w2 {
    display: none;
  }

  .barra-btn {
    display: block;
    position: fixed;
    width: 100%;
    bottom: 0vh;
    left: 0;
    background: #62727b;
    text-align: center;
    padding: 0px 0;
    z-index: 99;
  }

  .btn-barra {
    background-color: #62727b;
    border-color: #62727b;
    color: white;
    -webkit-transition-duration: 0.4s;
    /* Safari */
    transition-duration: 0.4s;
  }

  .btn-barra:hover {
    background-color: white;
    /* Green */
    color: white;
  }
</style>
<?php
$this->registerJs("

    $( document ).ready(function() {
        document.title = 'Executantes';
    });

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
          if(e.target == this)
              location.href = '" . Url::to(['executante/update']) . "&id='+id;
        }
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



<div class="box box-primary">
  <div class="box-header with-border">

    <div style="margin-bottom:1em">
      <?= Html::a('Mostrar Todos', ['/executante/create', 'pagination' => true], ['class' => 'btn btn-primary grid-button']) ?>
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
      /*'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-wrench"></i> Executantes'
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

            return Yii::$app->db->createCommand('SELECT nome FROM user WHERE id=' . $data->usuario_id)->queryScalar();
          },
        ],
        /*[
              'header' => '<span style="color:#337ab7">Tipo</span>',              
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
              'header' => '<span style="color:#337ab7">Email</span>',              
              'format' => 'raw',
               'value' => function ($data) {

                   return Yii::$app->db->createCommand('SELECT email FROM user WHERE id='.$data->usuario_id)->queryScalar();
               },
            ],*/
        // 'telefone',
        // 'celular',

        // 'modificado',

        // ['class' => 'yii\grid\ActionColumn'],
      ],
    ]); ?>
  </div>
</div>
<div class="executante-form">

  <?php $form = ActiveForm::begin(); ?>
  <div class="box box-primary">
    <div class="box-header with-border">
      <div class="row">

        <div class="col-md-3">
          <div id="tipoExecutantes">
            <label>Funções</label>
            <br>
            <?php
            foreach ($listTipos as $key => $tipo) {
              $existeTipo = '';
              if (!$model->isNewRecord)
                $existeTipo = Yii::$app->db->createCommand('SELECT tipo_id FROM executante_tipo WHERE tipo_id=' . $key . ' AND executante_id=' . $model->usuario_id)->queryScalar();
            ?>
              <?php if (!empty($existeTipo)) { ?>
                <div class="col-md-12">
                  <input type="checkbox" name="Tipos[<?= $key ?>]" value="<?= $key ?>" class="tipo-<?= $key ?>" checked="1"><?= $tipo ?>
                </div>
              <?php } else { ?>
                <div class="col-md-12">
                  <input type="checkbox" name="Tipos[<?= $key ?>]" value="<?= $key ?>" class="tipo-<?= $key ?>"><?= $tipo ?>
                </div>
              <?php } ?>
            <?php } ?>
          </div>
        </div>

        <div class="col-md-2">
          <div id="disciplinaExecutantes" style="margin-top: 1em">
            <label>Disciplinas</label>
            <br>
            <?php
            foreach ($listDisc as $key => $disc) {
              $existeTipo = '';
              if (!$model->isNewRecord)
                $existeTipo = Yii::$app->db->createCommand('SELECT disciplina_id FROM executante_disciplina WHERE disciplina_id=' . $key . ' AND executante_id=' . $model->usuario_id)->queryScalar();
            ?>
              <?php if (!empty($existeTipo)) { ?>
                <input type="checkbox" name="Disciplinas[<?= $key ?>]" value="<?= $key ?>" class="disc-<?= $key ?>" checked="1"><?= $disc ?>
              <?php } else { ?>
                <input type="checkbox" name="Disciplinas[<?= $key ?>]" value="<?= $key ?>" class="disc-<?= $key ?>"><?= $disc ?>
              <?php } ?>
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
          <?= $form->field($user, 'password')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
          <?= $form->field($model, 'cpf')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '999.999.999-99',
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

        <div class="col-md-2">
          <?= $form->field($model, 'is_prestador')->checkbox() ?>
        </div>

      </div>

      <div class="row">
        <div class="col-md-3">
          <?= $form->field($model, 'endereco')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
          <?= $form->field($model, 'bairro')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
          <?= $form->field($model, 'cidade')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-1">
          <?= $form->field($model, 'uf')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
          <?= $form->field($model, 'cep')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '99999-999',
          ]) ?>
        </div>

      </div>
    </div>
    EMPRESA
    <div class="row" style="border:1px solid black;padding: 2px; margin-bottom: 1em">
      <div class="col-md-4">
        <?= $form->field($model, 'nome_empresa')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'cnpj')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '99.999.999/9999-99',
        ]) ?>
      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'insc_municipal')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-3">
        <?= $form->field($model, 'endereco_empresa')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'bairro_empresa')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'cidade_empresa')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'uf_empresa')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'cep_empresa')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '99999-999',
        ]) ?>
      </div>

    </div>
    <div class="row">
      <div class="col-md-3">
        <?= $form->field($model, 'banco')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'banco_numero')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-1">
        <?= $form->field($model, 'agencia')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'conta_corrente')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-2">
        <label>Tipo de Conta</label>
        <select name="Executante[conta_tipo]" class="form-control">
          <option value="C" <?= $model->conta_tipo == 'C' ? 'selected' : '' ?>>Conta Corrente</option>
          <option value="P" <?= $model->conta_tipo == 'P' ? 'selected' : '' ?>>Poupança</option>
        </select>
      </div>
    </div>

    <div class="row">
      <?php
      $existeTipo = '';
      if (!$model->isNewRecord) {
        $existeTipo = Yii::$app->db->createCommand('SELECT tipo_id FROM executante_tipo WHERE tipo_id= 1 AND executante_id=' . $model->usuario_id)->queryScalar();
      }

      if (!empty($existeTipo)) {
      ?>
        <div class="col-md-2" id="div_hh_tp">
        <?php } else { ?>
          <div class="col-md-2" id="div_hh_tp" hidden>
          <?php } ?>
          <?= $form->field($model, 'vl_hh_tp')->textInput(['maxlength' => true, 'name' => 'Executante[vl_hh_tp]'])->widget(MaskMoney::classname(), [
            'pluginOptions' => [
              'prefix' => 'R$ ',
              'thousands' => '.',
              'decimal' => ',',
              // 'suffix' => ' ¢',
              'allowNegative' => false

            ]
          ]); ?>
          </div>

          <?php
          $existeTipo = '';
          if (!$model->isNewRecord) {
            $existeTipo = Yii::$app->db->createCommand('SELECT tipo_id FROM executante_tipo WHERE tipo_id= 2 AND executante_id=' . $model->usuario_id)->queryScalar();
          }

          if (!empty($existeTipo)) {
          ?>
            <div class="col-md-2" id="div_hh_ej">
            <?php } else { ?>
              <div class="col-md-2" id="div_hh_ej" hidden>
              <?php } ?>
              <?= $form->field($model, 'vl_hh_ej')->textInput(['maxlength' => true, 'name' => 'Executante[vl_hh_ej]'])->widget(MaskMoney::classname(), [
                'pluginOptions' => [
                  'prefix' => 'R$ ',
                  'thousands' => '.',
                  'decimal' => ',',
                  // 'suffix' => ' ¢',
                  'allowNegative' => false

                ]
              ]); ?>
              </div>

              <?php
              $existeTipo = '';
              if (!$model->isNewRecord) {
                $existeTipo = Yii::$app->db->createCommand('SELECT tipo_id FROM executante_tipo WHERE tipo_id= 3 AND executante_id=' . $model->usuario_id)->queryScalar();
              }

              if (!empty($existeTipo)) {
              ?>
                <div class="col-md-2" id="div_hh_ep">
                <?php } else { ?>
                  <div class="col-md-2" id="div_hh_ep" hidden>
                  <?php } ?>
                  <?= $form->field($model, 'vl_hh_ep')->textInput(['maxlength' => true, 'name' => 'Executante[vl_hh_ep]'])->widget(MaskMoney::classname(), [
                    'pluginOptions' => [
                      'prefix' => 'R$ ',
                      'thousands' => '.',
                      'decimal' => ',',
                      // 'suffix' => ' ¢',
                      'allowNegative' => false

                    ]
                  ]); ?>
                  </div>
                  <?php
                  $existeTipo = '';
                  if (!$model->isNewRecord) {
                    $existeTipo = Yii::$app->db->createCommand('SELECT tipo_id FROM executante_tipo WHERE tipo_id= 4 AND executante_id=' . $model->usuario_id)->queryScalar();
                  }

                  if (!empty($existeTipo)) {
                  ?>
                    <div class="col-md-2" id="div_hh_es">
                    <?php } else { ?>
                      <div class="col-md-2" id="div_hh_es" hidden>
                      <?php } ?>
                      <?= $form->field($model, 'vl_hh_es')->textInput(['maxlength' => true, 'name' => 'Executante[vl_hh_es]'])->widget(MaskMoney::classname(), [
                        'pluginOptions' => [
                          'prefix' => 'R$ ',
                          'thousands' => '.',
                          'decimal' => ',',
                          // 'suffix' => ' ¢',
                          'allowNegative' => false

                        ]
                      ]); ?>
                      </div>
                      <?php
                      $existeTipo = '';
                      if (!$model->isNewRecord) {
                        $existeTipo = Yii::$app->db->createCommand('SELECT tipo_id FROM executante_tipo WHERE tipo_id= 5 AND executante_id=' . $model->usuario_id)->queryScalar();
                      }

                      if (!empty($existeTipo)) {
                      ?>
                        <div class="col-md-2" id="div_hh_ee">
                        <?php } else { ?>
                          <div class="col-md-2" id="div_hh_ee" hidden>
                          <?php } ?>
                          <?= $form->field($model, 'vl_hh_ee')->textInput(['maxlength' => true, 'name' => 'Executante[vl_hh_ee]'])->widget(MaskMoney::classname(), [
                            'pluginOptions' => [
                              'prefix' => 'R$ ',
                              'thousands' => '.',
                              'decimal' => ',',
                              // 'suffix' => ' ¢',
                              'allowNegative' => false

                            ]
                          ]); ?>
                          </div>
                          <div class="col-md-1">
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
                          <div class="col-md-2">
                            <?= $form->field($model, 'cargo')->dropDownList($listCargo, ['prompt' => 'Nenhum']) ?>
                          </div>
                          <div class="col-md-2">
                            Cor
                            <?= $form->field($model, 'cor', ['template' => "{input}"])->input('color', ['class' => "input_class"]) ?>
                          </div>
                        </div>
                        <div class="barra-btn">
                          <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-floppy-o" aria-hidden="true"></i> Cadastrar' : '<i class="fa fa-floppy-o" aria-hidden="true"></i> Salvar', ['class' => $model->isNewRecord ? 'btn btn-barra' : 'btn btn-barra']) ?>

                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>

                </div>
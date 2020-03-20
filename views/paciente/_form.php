<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\money\MaskMoney;
/* @var $this yii\web\View */
/* @var $model app\models\Paciente */
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
        document.title = 'Paciente';
    });

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
          if(e.target == this)
              location.href = '" . Url::to(['paciente/update']) . "&id='+id;
        }
    });

    $('#paciente-cpf').focusout(function(){
        var cpf = $('#paciente-cpf').val();
        console.log(cpf);
        if(!cpf.includes('_')) {
            if(cpf != '123.456.789-00'){
            $.ajax({ 
                url: 'index.php?r=paciente/getdata',
                data: {cpf: cpf},
                type: 'POST',
                success: function(response){
                    var resposta = $.parseJSON(response);
                    $('#paciente-nome').val(resposta[0]['nome']);  
                    // $('#paciente-nome').attr('readonly', 'true');
                },
                error: function(request, status, error){
                  console.log(request.responseText);
                }
            });
        } else {
            $('#nome').val('');  
          }
        }
    });

    $('#inputCEP').focusout(function(){
        var cep = $('#inputCEP').val();
        $.ajax({ 
            url: 'https://viacep.com.br/ws/' + cep +'/json/',
            success: function(response){
                console.log(response);
                $('#inputCEP').val(response.cep);
                $('#inputCidade').val(response.localidade);
                $('#inputEstado').val(response.uf);
                $('#inputRua').val(response.logradouro);
                $('#inputBairro').val(response.bairro);
                
                $('#inputNum').focus();
            },
            error: function(request, status, error){
              console.log(request.responseText);
            }
          });
      });

      $('#inputNum').focusout(function(){
        var rua = $('#inputRua').val();
        var bairro = $('#inputBairro').val();
        var cep =  $('#inputCEP').val();
        var cidade =  $('#inputCidade').val();
        var uf =  $('#inputEstado').val();
        var numero = $('#inputNum').val();

        $('#paciente-endereco').val(rua + ', nº: ' + numero + ', ' + bairro + ' - CEP ' + cep + ', ' + cidade + ' - ' + uf);

      });
");
?>
<!-- mask so funciona com isso -->
<?php $this->head() ?>



<div class="box box-primary">
    <div class="box-header with-border">

        <div style="margin-bottom:1em; text-align: right">
            <?= Html::a('Mostrar Todos', ['/paciente/create', 'pagination' => true], ['class' => 'btn btn-primary grid-button']) ?>
        </div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'id' => 'grid',
            // 'pjax' => true,
            'options' => ['style' => 'font-size:12px;'],
            'rowOptions' => function ($model, $key, $index, $grid) {
                return [
                    'style' => "cursor: pointer",
                ];
            },
            'columns' => [
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'contentOptions' => ['style' => 'width:5em;  min-width:5em; text-align: center'],
                ],
                'nome',
                'cpf',
                'rg'
            ],
        ]); ?>
    </div>
</div>

<div class="paciente-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group col-md-6" style="margin-bottom: 0px;" hidden>
        <?= $form->field($model, 'id_paciente')->textInput(['maxlength' => true, 'class' => "form-control"]) ?>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6" style="margin-bottom: 0px;">
            <?= $form->field($model, 'cpf')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '999.999.999-99',
            ]) ?>
        </div>
        <div class="form-group col-md-6" style="margin-bottom: 0px;">
            <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'class' => "form-control"]) ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-4" style="margin-bottom: 0px;">
            <?= $form->field($model, 'rg')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '99.999.999-99',

            ]) ?>
        </div>
        <div class="form-group col-md-4" style="margin-bottom: 0px;">
            <?= $form->field($model, 'celular')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '(99) 99999-9999',
            ]) ?>
        </div>
        <div class="form-group col-md-4" style="margin-bottom: 0px;">
            <?= $form->field($model, 'telefone')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '(99) 9999-9999',
            ]) ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6" style="margin-bottom: 0px;">
            <?= $form->field($model, 'nome_mae')->textInput(['maxlength' => true, 'class' => "form-control"]) ?>
        </div>
        <div class="form-group col-md-6" style="margin-bottom: 0px;">
            <?= $form->field($model, 'nome_pai')->textInput(['maxlength' => true, 'class' => "form-control"]) ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-4" style="margin-bottom: 0px;">
            <?= $form->field($model, 'profissao_empresa')->textInput(['maxlength' => true, 'class' => "form-control"]) ?>
        </div>
        <div class="form-group col-md-2" style="margin-bottom: 0px;">
            <?= $form->field($model, 'nascimento')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '99/99/9999',
            ]) ?>
        </div>

        <div class="form-group col-md-3" style="margin-bottom: 0px;">
            <?= $form->field($model, 'cor_pele')->dropDownList($listRaca, ['prompt' => 'Selecione']) ?>
        </div>

        <div class="form-group col-md-3" style="margin-bottom: 0px;">
            <?= $form->field($model, 'estado_civil')->dropDownList($listStatusCivil, ['prompt' => 'Selecione o estado civil']) ?>
        </div>
    </div>


    <div class="form-row">
        <div class="form-group col-md-4" style="margin-bottom: 0px;">
            <?= $form->field($model, 'nacionalidade')->textInput(['maxlength' => true, 'class' => "form-control"]) ?>
        </div>

        <div class="form-group col-md-4" style="margin-bottom: 0px;">
            <?= $form->field($model, 'naturalidade')->textInput(['maxlength' => true, 'class' => "form-control"]) ?>
        </div>

        <div class="form-group col-md-4">
            <label for="inputCEP">CEP</label>
            <input type="text" class="form-control" id="inputCEP" <?= $model->isNewRecord ? 'required' : '' ?>>
        </div>

    </div>

    <div class="form-row">

        <div class="form-group col-md-3">
            <label for="inputEstado">Estado</label>
            <select id="inputEstado" class="form-control">
                <option selected>Escolha...</option>
                <option value="AC">Acre</option>
                <option value="AL">Alagoas</option>
                <option value="AP">Amapá</option>
                <option value="AM">Amazonas</option>
                <option value="BA">Bahia</option>
                <option value="CE">Ceará</option>
                <option value="DF">Distrito Federal</option>
                <option value="ES">Espírito Santo</option>
                <option value="GO">Goiás</option>
                <option value="MA">Maranhão</option>
                <option value="MT">Mato Grosso</option>
                <option value="MS">Mato Grosso do Sul</option>
                <option value="MG">Minas Gerais</option>
                <option value="PA">Pará</option>
                <option value="PB">Paraíba</option>
                <option value="PR">Paraná</option>
                <option value="PE">Pernambuco</option>
                <option value="PI">Piauí</option>
                <option value="RJ">Rio de Janeiro</option>
                <option value="RN">Rio Grande do Norte</option>
                <option value="RS">Rio Grande do Sul</option>
                <option value="RO">Rondônia</option>
                <option value="RR">Roraima</option>
                <option value="SC">Santa Catarina</option>
                <option value="SP">São Paulo</option>
                <option value="E">Sergipe</option>
                <option value="TO">Tocantins</option>
            </select>
        </div>

        <div class="form-group col-md-2">
            <label for="inputCidade">Cidade</label>
            <input type="text" class="form-control" id="inputCidade" <?= $model->isNewRecord ? 'required' : '' ?>>
        </div>

        <div class="form-group col-md-2">
            <label for="inputBairro">Bairro</label>
            <input type="text" class="form-control" id="inputBairro" <?= $model->isNewRecord ? 'required' : '' ?>>
        </div>

        <div class="form-group col-md-3">
            <label for="inputRua">Logradouro</label>
            <input type="text" class="form-control" id="inputRua" <?= $model->isNewRecord ? 'required' : '' ?>>
        </div>

        <div class="form-group col-md-2">
            <label for="inputNum">Nº</label>
            <input type="number" class="form-control" id="inputNum" <?= $model->isNewRecord ? 'required' : '' ?>>
        </div>

    </div>

    <div class="form-row">
        <div class="form-group col-md-10">
            <?= $form->field($model, 'endereco')->textInput(['maxlength' => true, 'class' => "form-control", 'readonly' => true]) ?>
        </div>
        <div class="form-group col-md-2">
            <?= $form->field($model, 'indicacao')->textInput(['maxlength' => true, 'class' => "form-control"]) ?>
        </div>
    </div>
</div>

<div class="form-group" style="text-align: center">
    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-floppy-o" aria-hidden="true"></i> Cadastrar' : '<i class="fa fa-floppy-o" aria-hidden="true"></i> Salvar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-barra', 'id' => $model->isNewRecord ? 'btnCreate' : 'btnUpdate']) ?>
</div>
<?php ActiveForm::end(); ?>
</div>
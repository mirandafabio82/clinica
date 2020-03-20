<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Documento */
/* @var $form yii\widgets\ActiveForm */
?>
<!-- mask so funciona com isso -->
<?php $this->head() ?>
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

    .dropify-wrapper.touch-fallback .dropify-clear {
        display: none;
    }

    /*.summary{
  display: none;
}

#w2{
    display: none;
}*/
</style>
<?php
$this->registerJs("
    $( document ).ready(function() {
        document.title = 'Documentos';
        $('#printDocument').css('display','none');
    });


    $('#inputType').change(function() {
        if ($('#inputType').val() != 1) {
            $('#w0').css('display','none');
            $('#printDocument').css('display','');
        } else {
            $('#w0').css('display','');
            $('#printDocument').css('display','none');
        }
      });

      $('#up_nome').focusout(function(){
        var nome = $('#up_nome').val();
        var cpf = $('#paciente-cpf').val();
        if((cpf == '12345678900') || (cpf == '123.456.789-00')){
            $.ajax({ 
                url: 'index.php?r=impressao/getdatapaciente',
                data: {cpf: cpf, nome: nome},
                type: 'POST',
                success: function(response){
                    var resposta = $.parseJSON(response);
                    var ao_cpf = cpf; 
                    ao_cpf = ao_cpf.replace( /(\d{3})(\d)/ , '$1.$2'); //Coloca um ponto entre o terceiro e o quarto dígitos
                    ao_cpf = ao_cpf.replace( /(\d{3})(\d)/ , '$1.$2'); //Coloca um ponto entre o terceiro e o quarto dígitos
                    ao_cpf = ao_cpf.replace( /(\d{3})(\d{1,2})$/ , '$1-$2'); //Coloca um hífen entre o terceiro e o quarto dígitos
                    $('#paciente-cpf').val(ao_cpf);
                    date = resposta[0]['nascimento'];
                    var final = date.split('-')[2] + '-' + date.split('-')[1] + '-' + date.split('-')[0];
                    $('#paciente-nascimento').val(final);  
                    $('#up_nome').attr('readonly', true);
                    $('#paciente-nascimento').attr('readonly', true);

                    $('#id_paciente').val(resposta[0]['id_paciente']);
                },
                error: function(request, status, error){
                  console.log(request.responseText);
                }
            });
        }
      });
      
    $('#buttonConfirm').click(function(){
        if( $('#paciente-nascimento').val() == '') {
            var optsel = confirm('Paciente não encontrado, deseja continuar?');
            if (optsel == true){
                $('#w0').submit();
            }
        } else {
            $('#w0').submit();
        }
    });

    $('#paciente-cpf').focusout(function(){
        var cpf = $('#paciente-cpf').val();
        if(!cpf.includes('_')) {
            if((cpf != '12345678900') && (cpf != '123.456.789-00')){
            $.ajax({ 
                url: 'index.php?r=paciente/getdatapaciente',
                data: {cpf: cpf},
                type: 'POST',
                success: function(response){
                    var resposta = $.parseJSON(response);
                    console.log(resposta);
                    var ao_cpf = cpf; 
                    ao_cpf = ao_cpf.replace( /(\d{3})(\d)/ , '$1.$2'); //Coloca um ponto entre o terceiro e o quarto dígitos
                    ao_cpf = ao_cpf.replace( /(\d{3})(\d)/ , '$1.$2'); //Coloca um ponto entre o terceiro e o quarto dígitos
                    ao_cpf = ao_cpf.replace( /(\d{3})(\d{1,2})$/ , '$1-$2'); //Coloca um hífen entre o terceiro e o quarto dígitos
                    $('#paciente-cpf').val(ao_cpf);
                    date = resposta[0]['nascimento'];
                    var final = date.split('-')[2] + '-' + date.split('-')[1] + '-' + date.split('-')[0];
                    $('#paciente-nascimento').val(final);
                    
                    $('#up_nome').val(resposta[0]['nome']);  
                    $('#up_nome').attr('readonly', true);
                    $('#paciente-nascimento').attr('readonly', true);

                    $('#id_paciente').val(resposta[0]['id_paciente']);
                },
                error: function(request, status, error){
                  console.log(request.responseText);
                }
            });
        } else {
            var ao_cpf = cpf; 
            ao_cpf = ao_cpf.replace( /(\d{3})(\d)/ , '$1.$2'); //Coloca um ponto entre o terceiro e o quarto dígitos
            ao_cpf = ao_cpf.replace( /(\d{3})(\d)/ , '$1.$2'); //Coloca um ponto entre o terceiro e o quarto dígitos
            ao_cpf = ao_cpf.replace( /(\d{3})(\d{1,2})$/ , '$1-$2'); //Coloca um hífen entre o terceiro e o quarto dígitos
            $('#paciente-cpf').val(ao_cpf);
            $('#up_nome').removeAttr('readonly');
            $('#paciente-nascimento').removeAttr('readonly');

            $('#paciente-nascimento').val('');
            $('#up_nome').val('');
            $('#id_paciente').val('');
          }
        }
    });
");
?>
<div class="box box-primary">
    <div class="box-header with-border">

        <div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-file"></i> Documentos </div>
        <div style="margin-bottom:1em;margin-top: 1em">
            <div class="col-md-6">
                <?= Html::dropDownList('Impressao[type]', 'inputType', $listDocumentos, ['class' => 'form-control', 'id' => 'inputType']) ?>
                <!-- <?= Html::a('<span class="btn-label"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Visualizar Documento</span>', ['teste'], ['class' => 'btn btn-barra', 'target' => '_blank', 'style' => ' margin-right: 1em']) ?> -->
            </div>
            <div class="col-md-6">
                <div class="autocomplete col-md-3" style="width:300px; padding: 0" id="autocomplete_div_0">
                    <?= Html::button('Imprimir', ['class' => 'btn btn-success', 'id' => 'printDocument']) ?>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="documento-form" id="documento_form">

    <div class="box box-primary">
        <div class="box-header with-border">
            <?php $form = ActiveForm::begin(['action' => ['/impressao/gerarfileanaminese'], 'options' => ['method' => 'post', 'target' => '_blank']]); ?>

            <div class="row">

                <div class="col-md-12">

                    <div class="col-md-4">
                        <div class="form-group col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                            <?= $form->field($model, 'cpf')->widget(\yii\widgets\MaskedInput::className(), [
                                'mask' => '999.999.999-99',
                                'id' => 'form_cpf'
                            ]) ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                            <label>Nome</label>
                            <input class="np_autocomplete form-control" id="up_nome" type="text" name="Documento[nome]" readonly>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                            <?= $form->field($model, 'nascimento')->widget(\yii\widgets\MaskedInput::className(), [
                                'mask' => '99/99/9999',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <input id="id_paciente" type="text" name="Impressao[id_paciente]" style="display: none">
            </div>
            <div class="form-group" style="text-align: center">
                <?= Html::Button('Confirmar', ['class' => 'btn btn-success', 'id' => 'buttonConfirm']) ?>
            </div>
        </div>
        <br>
        <?php ActiveForm::end(); ?>
    </div>
</div>
</div>
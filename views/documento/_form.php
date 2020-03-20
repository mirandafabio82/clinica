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
        var d = new Date();
        $('#documento-data').val( ('00' + (d.getDate())).slice(-2) + '/' + ('00' + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear());
    });

   /* $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
          if(e.target == this)
              location.href = '" . Url::to(['documento/update']) . "&id='+id;
        }
    });*/
    $('.dropify').dropify({
        tpl: {
        message:'<div class=\'dropify-message\'><span class=\'file-icon\' /> <p>Clique ou arraste um arquivo para adicioná-lo </p></div>',
    }
    });

    $('#documento-id_tipo_documento').change(function() {
        if ($('#documento-id_tipo_documento').val() == 1003) {
            $('#outro_tipo').removeAttr('readonly');
            $('#outro_tipo').focus();
        } else {
            $('#outro_tipo').attr('readonly', true);
        }
      });

      $('#up_cpf').focusout(function(){
        var cpf = $('#up_cpf').val();
        if(!cpf.includes('_')) {
            if((cpf != '12345678900') && (cpf != '123.456.789-00')){
            $.ajax({ 
                url: 'index.php?r=paciente/getdatapaciente',
                data: {cpf: cpf},
                type: 'POST',
                success: function(response){
                    var resposta = $.parseJSON(response);
                    var ao_cpf = cpf; 
                    ao_cpf = ao_cpf.replace( /(\d{3})(\d)/ , '$1.$2'); //Coloca um ponto entre o terceiro e o quarto dígitos
                    ao_cpf = ao_cpf.replace( /(\d{3})(\d)/ , '$1.$2'); //Coloca um ponto entre o terceiro e o quarto dígitos
                    ao_cpf = ao_cpf.replace( /(\d{3})(\d{1,2})$/ , '$1-$2'); //Coloca um hífen entre o terceiro e o quarto dígitos
                    $('#up_cpf').val(ao_cpf);
                    $('#up_nome').val(resposta[0]['nome']);  
                    $('#up_nome').attr('readonly', true);
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
            $('#up_cpf').val(ao_cpf);
            $('#up_nome').removeAttr('readonly');
            $('#up_nome').val('');
          }
        }
    });
");
?>
<div class="box box-primary">
    <div class="box-header with-border">

        <div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-file"></i> Documentos </div>
        <div style="margin-bottom:1em;margin-top: 1em">
            <?= Html::a('Mostrar Todos', ['/documento/create', 'pagination' => true], ['class' => 'btn btn-primary grid-button']) ?>
        </div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => ['style' => 'font-size:12px;'],
            'columns' => [
                [
                    'header' => '<span style="color:#337ab7">Excluir</span>',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'contentOptions' => ['style' => 'width:5em;  min-width:5em; text-align: center'],
                ],
                [
                    'header' => '<span style="color:#337ab7">Nome do Paciente</span>',
                    'format' => 'raw',
                    'attribute' => 'pacienteNome',
                    'value' => function ($data) {
                        return Yii::$app->db->createCommand('SELECT nome FROM paciente WHERE id_paciente = ' . $data->id_paciente)->queryScalar();
                    }
                ],
                [
                    'header' => '<span style="color:#337ab7">CPF</span>',
                    'format' => 'raw',
                    'attribute' => 'pacienteCPF',
                    'value' => function ($data) {
                        return Yii::$app->db->createCommand('SELECT cpf FROM paciente WHERE id_paciente = ' . $data->id_paciente)->queryScalar();
                    }
                ],
                [
                    'header' => '<span style="color:#337ab7">Tipo</span>',
                    'value' => function ($data) {
                        if (!empty($data->id_tipo_documento))
                            return Yii::$app->db->createCommand('SELECT nome FROM tipo_documento WHERE id_tipo_documento = ' . $data->id_tipo_documento)->queryScalar();
                    }
                ],
                [
                    'header' => '<span style="color:#337ab7">Data</span>',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'text-align: center; width:10em;  min-width:10em;'],
                    'attribute' => 'data',
                ],
                'observacao',
                [
                    'header' => '<span style="color:#337ab7">Download</span>',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'width:20em;  min-width:4em; text-align: center'],
                    'value' => function ($data) {
                        $nome = Yii::$app->db->createCommand('SELECT path FROM documento WHERE id_documento =' . $data->id_documento)->queryScalar();
                        if (!empty($data->path)) {
                            return Html::a(
                                '<i class="fa fa-fw fa-download" style="text-align: center; font-size: 15px;"></i>',
                                $data->path,
                                [                                 // link options
                                    'title' => 'Fazer download',
                                    'target' => '_blank',
                                    'class' => 'linksWithTarget',
                                    'data-pjax' => "0"
                                ]
                            );
                        } else {
                            return Html::a(
                                '<span style="color:#337ab7">Não há documento</span>',
                                ''
                            );
                        }
                    }
                ],
            ],
        ]); ?>
    </div>
</div>

<div class="documento-form">

    <div class="box box-primary">
        <div class="box-header with-border">
            <?php $form = ActiveForm::begin(['action' => ['/documento/upload'], 'options' => ['method' => 'post']]); ?>

            <div class="row">

                <div class="col-md-12">

                    <div class="col-md-4">
                        <div class="form-group col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                            <?= $form->field($model, 'id_tipo_documento')->dropDownList($listTipoDoc, ['prompt' => 'Selecione um tipo']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                            <label>Outro</label>
                            <input class="np_autocomplete form-control" id="outro_tipo" type="text" name="Documento[outro_tipo]" readonly=true>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                            <?= $form->field($model, 'data')->widget(\yii\widgets\MaskedInput::className(), [
                                'mask' => '99/99/9999',
                            ]) ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group col-md-4" style="width:300px;padding: 0" id="autocomplete_div_0">
                            <label>CPF</label>
                            <input class="np_autocomplete form-control" id="up_cpf" type="text" name="Documento[cpf]" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                            <label>Nome</label>
                            <input class="np_autocomplete form-control" id="up_nome" type="text" name="Documento[nome]" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">

                    <div class="form-group col-md-4">
                        <?= $form->field($model, 'path')->fileInput(['class' => 'dropify', 'multiple' => true]); ?>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-6">
                            <?= $form->field($model, 'observacao')->textarea() ?>
                        </div>
                    </div>
                </div>


            </div>
            <div class="form-group" style="text-align: center">
                <?= Html::submitButton('Cadastrar', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <br>
        <?php ActiveForm::end(); ?>
    </div>
</div>
</div>
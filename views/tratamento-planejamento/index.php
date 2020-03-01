<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TratamentoPlanejamentoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planejamento';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
Modal::begin(['header' => '<h4>LD-Preliminar</h4>', 'id' => 'modal', 'size' => 'modal-lg',]);
echo '<div id="modalContent"></div>';
Modal::end();
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

var id_tratamento;

    $('#createNewPlanejamento').click(function(e) {
        $('#create_tratamento_planejamento').modal('show');
    });
    var evento_id = '';

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
            if(e.target == this) {
                $.ajax({ 
                    url: 'index.php?r=tratamento-planejamento/getplanejamento',
                    data: {id: id},
                    type: 'POST',
                    success: function(response){
                        var resposta = $.parseJSON(response);
                        console.log(resposta);
                        var ao_cpf = resposta['cpf']; 
                        ao_cpf = ao_cpf.replace( /(\d{3})(\d)/ , '$1.$2'); //Coloca um ponto entre o terceiro e o quarto dígitos
                        ao_cpf = ao_cpf.replace( /(\d{3})(\d)/ , '$1.$2'); //Coloca um ponto entre o terceiro e o quarto dígitos
                        ao_cpf = ao_cpf.replace( /(\d{3})(\d{1,2})$/ , '$1-$2'); //Coloca um hífen entre o terceiro e o quarto dígitos
                        $('#up_nome').val(resposta['nome']);
                        $('#up_cpf').val(ao_cpf);
                        $('#up_primeira_opcao').val(resposta['primeira_opcao']);
                        $('#up_segunda_opcao').val(resposta['segunda_opcao']);
                        

                        $('#update_tratamento_planejamento').modal('show');

                        id_tratamento =  resposta['id_tratamento'];
                    },
                    error: function(request, status, error){
                    }
                });
            }
        }
    });

    $('#newPlanejamento').click(function() {
        var conf_cpf = $('#conf_cpf').val();
        var primeira_opcao = $('#tratamentoplanejamento-primeira_opcao').val();
        var segunda_opcao =  $('#tratamentoplanejamento-segunda_opcao').val();

        $.ajax({ 
            url: 'index.php?r=tratamento-planejamento/createone',
            data: {cpf: conf_cpf,
                primeira: primeira_opcao,
                segunda: segunda_opcao
            },
            type: 'POST'
        });
    });

    $('#updatePlanejamento').click(function(){
        var primeira_opcao = $('#up_primeira_opcao').val();
        var segunda_opcao =  $('#up_segunda_opcao').val();

        $.ajax({
            url: 'index.php?r=tratamento-planejamento/update',
            data: {
                id_tratamento: id_tratamento,
                primeira: primeira_opcao,
                segunda: segunda_opcao
            },
            type: 'POST'
        });
    });

    $('#conf_cpf').focusout(function(){
        var cpf = $('#conf_cpf').val();
            $.ajax({ 
                url: 'index.php?r=paciente/getdata',
                data: {cpf: cpf},
                type: 'POST',
                success: function(response){
                    var resposta = $.parseJSON(response);
                    $('#conf_nome').val(resposta[0]['nome']);

                    var ao_cpf = cpf; 
                    ao_cpf = ao_cpf.replace( /(\d{3})(\d)/ , '$1.$2'); //Coloca um ponto entre o terceiro e o quarto dígitos
                    ao_cpf = ao_cpf.replace( /(\d{3})(\d)/ , '$1.$2'); //Coloca um ponto entre o terceiro e o quarto dígitos
                    ao_cpf = ao_cpf.replace( /(\d{3})(\d{1,2})$/ , '$1-$2'); //Coloca um hífen entre o terceiro e o quarto dígitos
                    $('#conf_cpf').val(ao_cpf);

                    $('#tratamentoplanejamento-primeira_opcao').focus();
                },
                error: function(request, status, error){
                  console.log(request.responseText);
                }
            });
    });
    ");
?>
<?php $this->head() ?>
<div class="tratamento-planejamento-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <div class="box box-primary">
        <div class="box-header with-border">
            <div style="margin-bottom:1em;">
                <?= Html::button('Novo Planejamento', ['class' => 'btn btn-success grid-button', 'id' => 'createNewPlanejamento']) ?>
                <?= Html::a('Mostrar Todos', ['/tratamento-planejamento/index', 'pagination' => true], ['class' => 'btn btn-primary grid-button', 'style' => 'float: right']) ?>
            </div>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{delete}',
                        'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
                    ],
                    [
                        'header' => '<span style="color:#337ab7">Nome do Paciente</span>',
                        'attribute' => 'nome',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return Yii::$app->db->createCommand('SELECT nome FROM paciente WHERE id_paciente=' . $data->id_paciente)->queryScalar();
                        },
                    ],
                    [
                        'header' => '<span style="color:#337ab7">CPF</span>',
                        'attribute' => 'cpf',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return Yii::$app->db->createCommand('SELECT cpf FROM paciente WHERE id_paciente=' . $data->id_paciente)->queryScalar();
                        },
                    ]
                ],
            ]); ?>
        </div>
    </div>

    <div id="create_tratamento_planejamento" class="modal fade" role="dialog" style="z-index: 999999999">
        <div class="modal-dialog" style="width:50%">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="text-align: center">Planejamento</h4>
                </div>

                <div class="modal-body">
                    <?php $form = ActiveForm::begin() ?>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="col-md-6">
                                <div class="form-group col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                                    <label>CPF</label>
                                    <input class="np_autocomplete form-control" id="conf_cpf" type="text" name="PlanejamentoTratamento[cpf]" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                                    <label>Nome</label>
                                    <input class="np_autocomplete form-control" id="conf_nome" type="text" name="PlanejamentoTratamento[nome]" readonly>
                                </div>

                            </div>

                            <div class="form-group col-md-6" style="margin-bottom: 0px;">
                                <?= $form->field($model, 'primeira_opcao')->textarea(['maxlength' => true, 'class' => "form-control", 'required']) ?>
                            </div>

                            <div class="form-group col-md-6" style="margin-bottom: 0px;">
                                <?= $form->field($model, 'segunda_opcao')->textarea(['maxlength' => true, 'class' => "form-control"]) ?>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 15px">
                        <?= Html::button('Salvar', ['class' => 'btn btn-success', 'id' => 'newPlanejamento']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

    <div id="update_tratamento_planejamento" class="modal fade" role="dialog" style="z-index: 999999999">
        <div class="modal-dialog" style="width:50%">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="text-align: center">Planejamento</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="col-md-6">
                                <div class="form-group col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                                    <label>CPF</label>
                                    <input class="np_autocomplete form-control" id="up_cpf" type="text" name="PlanejamentoTratamento[cpf]" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                                    <label>Nome</label>
                                    <input class="np_autocomplete form-control" id="up_nome" type="text" name="PlanejamentoTratamento[nome]" readonly>
                                </div>
                            </div>

                            <div class="form-group col-md-6" style="margin-bottom: 0px;">
                                <?= $form->field($model, 'primeira_opcao')->textarea(['maxlength' => true, 'class' => "form-control", 'required', 'id' => 'up_primeira_opcao']) ?>
                            </div>

                            <div class="form-group col-md-6" style="margin-bottom: 0px;">
                                <?= $form->field($model, 'segunda_opcao')->textarea(['maxlength' => true, 'class' => "form-control", 'id' => 'up_segunda_opcao']) ?>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 15px">
                        <?= Html::button('<i class="fa fa-floppy-o" style="margin-right: 10px;" aria-hidden="true"></i> Alterar', ['class' => 'btn btn-barra', 'id' => 'updatePlanejamento']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
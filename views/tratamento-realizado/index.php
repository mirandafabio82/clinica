<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TratamentoRealizadoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tratamento Realizados';
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

$(document).ready(function(){

});

var id;

$('#saveTratamento').click(function (e) {
        
    var conf_id = id;
    var conf_dente = $('#conf_dente').val();
    var conf_tratamento = $('#conf_tratamento').val();
        $.ajax({
          url: 'index.php?r=tratamento-realizado/updateone',
          data: {
            id: conf_id,
            dente: conf_dente,
            tratamento: conf_tratamento,
          },
          type: 'POST'
        });
      });

    var evento_id = '';

    $('td').click(function (e) {
        id = $(this).closest('tr').attr('data-key');
        if(id != null){
            if(e.target == this) {
                $.ajax({ 
                    url: 'index.php?r=tratamento-realizado/getagendamento',
                    data: {id: id},
                    type: 'POST',
                    success: function(response){
                        var resposta = $.parseJSON(response);
                        
                        var ao_cpf = resposta['cpf']; 

                        ao_cpf = ao_cpf.replace( /(\d{3})(\d)/ , '$1.$2'); //Coloca um ponto entre o terceiro e o quarto dígitos
                        ao_cpf = ao_cpf.replace( /(\d{3})(\d)/ , '$1.$2'); //Coloca um ponto entre o terceiro e o quarto dígitos
  
                        ao_cpf = ao_cpf.replace( /(\d{3})(\d{1,2})$/ , '$1-$2'); //Coloca um hífen entre o terceiro e o quarto dígitos


                        $('#conf_nome').val(resposta['nome']);
                        $('#conf_cpf').val(ao_cpf);
                        $('#conf_horario').val(resposta['horario']);
                        $('#conf_dente').val(resposta['dente']);
                        $('#conf_tratamento').val(resposta['tratamento_realizado']);
                        $('#tratamento_realizado').modal('show');
                    },
                    error: function(request, status, error){
                    }
                });
            }
        }
    });
    ");
?>
<?php $this->head() ?>
<div class="tratamento-realizado-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <div class="box box-primary">
        <div class="box-header with-border">
            <div style="margin-bottom:1em; text-align: right">
                <?= Html::a('Mostrar Todos', ['/tratamento-realizado/index', 'pagination' => true], ['class' => 'btn btn-primary grid-button']) ?>
            </div>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'header' => '<span style="color:#337ab7">Nome do Paciente</span>',
                        'attribute' => 'agendamento',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return Yii::$app->db->createCommand('SELECT nome FROM agendamento WHERE id_agendamento=' . $data->id_agendamento)->queryScalar();
                        },
                    ],
                    'dente',
                    'tratamento_realizado',
                    [
                        'header' => '<span style="color:#337ab7; text-align: center">Visualizar</span>',
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'width:10em;  min-width:4em; text-align: center'],
                        'value' => function ($data) {
                            return Html::tag(
                                'i class="fa fa-fw fa-eye" style="text-align: center; font-size: 15px;"'
                            );
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>

    <div id="tratamento_realizado" class="modal fade" role="dialog" style="z-index: 999999999">
        <div class="modal-dialog" style="width:50%">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title" style="text-align: center">Tratamento Realizado</h4>
                </div>
                <div class="modal-body">

                    <?php $form = ActiveForm::begin();
                    $form->options['id'] = 'tratamento_realizado-form'; ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">

                                <div class="form-group col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                                    <label>Nome</label>
                                    <input class="np_autocomplete form-control" id="conf_nome" type="text" name="TratamentoRealizado[nome]" readonly>
                                </div>

                                <div class="form-group col-md-3" style="width:300px;padding: 0; margin-top: 15px;">
                                    <label>Dia - Horário</label> <br>
                                    <input type="datetime-local" id="conf_horario" name="TratamentoRealizado[horario]" class="form-control" readonly>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                                    <label>CPF</label>
                                    <input class="np_autocomplete form-control" id="conf_cpf" type="text" name="TratamentoRealizado[cpf]" readonly>
                                </div>

                                <div class="form-group col-md-3" style="width:300px;padding: 0; margin-top: 15px;">
                                    <label>Dente</label> <br>
                                    <input type="number" id="conf_dente" name="TratamentoRealizado[dente]" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top: 15px">
                                <label>Tratamento realizado</label> <br>
                                <textarea type="textarea" id="conf_tratamento" name="TratamentoRealizado[tratamento]" class="form-control" maxlength="255" required></textarea>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <div style="text-align: center; margin-top: 15px">
                        <button class="btn btn-barra" id="saveTratamento"><i class="fa fa-floppy-o" aria-hidden="true"></i><span style="margin-left: 10px">Alterar</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
</script>
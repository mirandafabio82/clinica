<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\Agendamento */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
  .autocomplete-items {
    position: absolute;
    border: 1px solid #d4d4d4;
    border-bottom: none;
    border-top: none;
    z-index: 99;
    /*position the autocomplete items to be the same width as the container:*/
    top: 100%;
    left: 0;
    right: 0;
  }

  .box.box-primary {
    border: none;
  }

  .autocomplete-items div {
    padding: 10px;
    cursor: pointer;
    background-color: #fff;
    border-bottom: 1px solid #d4d4d4;
  }

  .autocomplete-items div:hover {
    /*when hovering an item:*/
    background-color: #e9e9e9;
  }

  .autocomplete-active {
    /*when navigating through the items using the arrow keys:*/
    background-color: DodgerBlue !important;
    color: #ffffff;
  }
</style>

<?php

$eventos = '';
foreach ($arrayEventos as $key => $evt) {
  $cor = $evt['cor'];

  if ($cor == 'red') {
    $cor = '#bf4646';
  } else if ($cor == 'orange') {
    $cor = '#dca33b';
  } else if ($cor == 'green') {
    $cor = '#327932';
  }

  $eventos .= "{
                        id             : " . $evt['id_agendamento'] . ",
                        title          : '" . $evt['nome'] . "',
                        start          : '" . $evt['horario'] . "',
                        end            : '" . $evt['horario'] . "',
                        backgroundColor: '" . $cor . "', 
                        borderColor    : '" . $cor . "'
                      },";
}


$this->registerJs("

    var evento_id = '';

    $('#conf_valor').change(function(){
      var valor_min =  $('#conf_valor').attr('min');
      var valor_max =  $('#conf_valor').attr('max');
      var valor =  $('#conf_valor').val();
      if(valor < valor_min) {
        valor = valor_min;
        $('#conf_valor').val(parseFloat(valor_min).toFixed(2));
      }
      else if(valor > valor_max) {
        valor = valor_max;
        $('#conf_valor').val(parseFloat(valor_max).toFixed(2));
      } else {
        $('#conf_valor').val(parseFloat(valor).toFixed(2));
      }
      // Add options parcelamento
      var select = $('#conf_parcela');
      select.empty();
      for (var i = 1; i <= 12; i++) {
        var valor_parcela = valor / i;

        if (valor_parcela < 50) break;

        var text = i;
        text += \"x de R$ \";
        text += parseFloat(valor_parcela).toFixed(2);

        var option = new Option(text, text);

        select.append($(option));
      }
});

$('#deleteEvent').click(function(){       
  $.ajax({ 
    url: 'index.php?r=agenda/deleteone',
    data: {id: evento_id},
    type: 'POST',
    success: function(response){
         console.log('success');
    },
    error: function(request, status, error){
      console.log(request.responseText);
    }
  });
});

$('#forma_pagamento').click(function(){
  $('#forma_pagamento_modal').modal('show');
});


    $('#updateEvent').click(function(){       
      var up_nome = $('#up_nome').val();
      var up_tipo_atendimento = $('#up_tipo_atendimento').val();
      var up_cpf = $('#up_cpf').val();
      var up_horario = $('#up_horario').val();
      var up_plano_particular = $('#up_plano_particular').val();
      var up_status = $('#up_status').val();
      var up_descricao = $('#up_descricao').val();
      up_horario = up_horario.replace('T',' ');
      var data =  {id: evento_id, nome: up_nome, tipo_atendimento: up_tipo_atendimento, cpf: up_cpf, horario: up_horario, plano_particular: up_plano_particular, status: up_status, descricao: up_descricao};

      console.log(data);

      $.ajax({ 
        url: 'index.php?r=agenda/update',
        data: {id: evento_id, nome: up_nome, tipo_atendimento: up_tipo_atendimento, cpf: up_cpf, horario: up_horario, plano_particular: up_plano_particular, status: up_status, descricao: up_descricao},
        type: 'POST',
        success: function(response){
          console.log('success');
        },
        error: function(request, status, error){
          console.log(error);
        }
      });
    });

      
    $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
       /* $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })*/

      })
    }

    init_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
    $('#calendar').fullCalendar({
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'Hoje',
        month: 'Mês',
        week : 'Semana',
        day  : 'Dia'
      },
      defaultView: 'agendaDay',
      eventAfterAllRender: function(view){
          var type = view.type;

          var dateInicial = view.intervalStart.format('YYYY-MM-DD');
          var dateFinal = view.intervalEnd.format('YYYY-MM-DD');

          if(type === 'agendaDay'){

          } else {
            var d_final = new Date(dateFinal);
            d_final.setDate(d_final.getDate() - 1);
            dateFinal = d_final.getFullYear() + '-' + ('00' + (d_final.getMonth() + 1)).slice(-2) + '-' + ('00' + (d_final.getDate())).slice(-2);
          }
          $.ajax({ 
            url: 'index.php?r=agenda/getagendamento',
            data: {tipo: type, dataStart: dateInicial, dataEnd: dateFinal},
            type: 'POST',
            success: function(response){
              response = '{' + '\"data\"' + ':[' + response + ']}';
              var resposta = $.parseJSON(response);
              var count = resposta['data'][0].length;
              
              $('#external-events').empty();
              for(linha=0; linha < count; linha++){
                var nome = resposta[\"data\"][0][linha][\"nome\"];
                var horario = resposta[\"data\"][0][linha][\"horario\"].split(\"T\")[1];
                var color = resposta[\"data\"][0][linha][\"cor\"];
                var id = resposta[\"data\"][0][linha][\"id_agendamento\"];

                var botao = '<button class=\"btn btn-success btn-circle btn-circle-sm m-1\" id=\"confirm_' + id + '\" data-target=\"#tratamento_realizado\" onclick=\"confirmAgenda(' + id + ')\"><i class=\"fa fa-check\"></i></button><button style=\"margin-left: 10px\" class=\"btn btn-danger\" onclick=\"cancelAgenda(' + id+ ')\" id=\"cancel_' + id + '\" name=\"cancelEvent\"><i class=\"fa fa-close\"></i></button>';

                if(color == 'red') {
                  horario = 'CANCELADO';
                  color = '#bf4646';
                  botao = '<button class=\"btn btn-success btn-circle btn-circle-sm m-1\" id=\"confirm_' + id + '\" data-target=\"#tratamento_realizado\" onclick=\"confirmAgenda(' + id + ')\"><i class=\"fa fa-check\"></i></button>';
                } else if(color == 'orange') {
                  color = '#dca33b';
                  horario = 'REALIZADO';
                  botao = '<button class=\"btn btn-primary btn-circle btn-circle-sm m-1\" id=\"confirm_' + id + '\" data-target=\"#tratamento_realizado\"  onclick=\"viewTratamento(\'' + nome + '\')\"><i class=\"fa fa-eye\"></i></button>';
                } else if(color == 'green') {
                  color = '#327932';
                  botao = '<button class=\"btn btn-warning btn-circle btn-circle-sm m-1\" id=\"confirm_' + id + '\" data-target=\"#tratamento_realizado\" onclick=\"createTratamento(' + id + ')\"><i class=\"fa fa-address-card\"></i></button>';
                }

                $('#external-events').append('<div class=\"external-event bg-green\" style=\"padding: 10px;display: grid;grid-template-columns: auto auto auto auto auto auto; grid-gap: 10px;background-color: ' + color + '!important\"><div class=\"item1\" style=\"grid-column: 1 / 5; margin-top: auto; margin-bottom: auto;\">' + nome + ' - ' + horario + '</div><div class=\"item2\" style=\"text-align: right;\"></div><div class=\"item3\" style=\"text-align: right;\">' + botao + '</div></div>');
              }
            },
            error: function(request, status, error){
              console.log(error);
            }
          });
      },
      eventDrop: function(event) {
          var hr_inicio = event.start._i[0]+'-'+(event.start._i[1] + 1)+'-'+event.start._i[2]+' '+event.start._i[3]+':'+event.start._i[4]; 
          var hr_final = '';
          if(event.end != null){
            var hr_final = event.end._i[0]+'-'+(event.end._i[1] + 1)+'-'+event.end._i[2]+' '+event.end._i[3]+':'+event.end._i[4]; 
          }

          $.ajax({
            url: 'index.php?r=agenda/updateevent',
            data: {id: event.id, hr_inicio: hr_inicio, hr_final: hr_final},
            type: 'POST',
            success: function(response){
                 console.log('success');
            },
            error: function(request, status, error){
            }
          });
      },

      eventResize: function(event) {
          var hr_inicio = event.start._i[0]+'-'+(event.start._i[1] + 1)+'-'+event.start._i[2]+' '+event.start._i[3]+':'+event.start._i[4]; 
          var hr_final = '';
          if(event.end != null){
            var hr_final = event.end._i[0]+'-'+(event.end._i[1] + 1)+'-'+event.end._i[2]+' '+event.end._i[3]+':'+event.end._i[4]; 
          }
          console.log(hr_inicio);
          $.ajax({ 
            url: 'index.php?r=agenda/updateevent',
            data: {id: event.id, hr_inicio: hr_inicio, hr_final: hr_final},
            type: 'POST',
            success: function(response){
                 console.log('success');
            },
            error: function(request, status, error){
              
            }
          });

      },

      eventClick: function(calEvent, jsEvent, view) {

        evento_id = calEvent.id;
        $.ajax({ 
          url: 'index.php?r=agenda/getevent',
          data: {id: calEvent.id},
          type: 'POST',
          success: function(response){
            var resposta = $.parseJSON(response)
            $('#up_nome').val(resposta['nome']);
            $('#up_tipo_atendimento').val(resposta['tipo_atendimento']);
            $('#up_cpf').val(resposta['cpf']);
            $('#up_horario').val(resposta['horario']);
            $('#up_plano_particular').val(resposta['plano_particular']);
            $('#up_status').val(resposta['status']);
            $('#up_descricao').val(resposta['descricao']);
            $('#up_responsavel').val(resposta['id_responsavel']);
            $('#atualizar').modal('show');
         },
         error: function(request, status, error){
          
        }
      });
      },
      selectable: true,
      selectHelper: true,
      select: function(start, end){       
        $('#cadastrar #start').val(moment(start).format('YYYY-MM-DD')+'T'+moment(start).format('HH:mm:ss'));
        $('#cadastrar #end').val(moment(end).format('YYYY-MM-DD')+'T'+moment(end).format('HH:mm:ss'));
        $('#cadastrar').modal('show');
      },
      //Random default events
      events    : [
        " . $eventos . "
      ],
      editable  : false,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function (date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject')

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject)

        // assign it the date that was reported
        copiedEventObject.start           = date
        copiedEventObject.allDay          = allDay
        copiedEventObject.backgroundColor = $(this).css('background-color')
        copiedEventObject.borderColor     = $(this).css('border-color')

        // render the event on the calendar
        // the last `true` argument determines if the event sticks (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

        // is the remove after drop checkbox checked?
        if ($('#drop-remove').is(':checked')) {
          // if so, remove the element from the 'Draggable Events' list
          $(this).remove()
        }

      }
    })

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      //Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.html(val)
      $('#external-events').prepend(event)

      //Add draggable funtionality
      init_events(event)

      //Remove event from text input
      $('#new-event').val('')
    })
  })

");
?>
<?php
Modal::begin(['header' => '<h4>LD-Preliminar</h4>', 'id' => 'modal', 'size' => 'modal-lg',]);
echo '<div id="modalContent"></div>';
Modal::end();
?>
<style>
  .table-bordered>tbody>tr>td {
    padding-top: 0px !important;
    padding-bottom: 0px !important;
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
</style>
<!-- mask so funciona com isso -->
<?php $this->head() ?>
<div class="box box-primary">
  <div class="box-header with-border">
    <div style="background-color: #337ab7;border-radius: 10px;color:white;padding: 10px"><i class="fa fa-calendar"></i> Agenda </div>


    <?php
    if (isset($_SESSION['msg'])) {
      echo $_SESSION['msg'];
      unset($_SESSION['msg']);
    }
    ?>

    <div class="row">

      <div class="col-md-7">
        <div class="box box-primary">
          <div class="box-body no-padding">
            <!-- THE CALENDAR -->
            <?= yii2fullcalendar\yii2fullcalendar::widget([
              'options' => [
                'lang' => 'pt',
                'hidden' => 'hidden',
                //... more options to be defined here!
              ],
              'events' => Url::to(['/timetrack/default/jsoncalendar'])
            ]);
            ?>
            <div id="calendar"></div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /. box -->
      </div>
      <div class="col-md-5">
        <div class="box box-solid">
          <div class="box-header with-border" style="margin-top: 10%; text-align: center; font-family: 'Righteous', sans-serif;">
            <h4 class="box-title" style="font-size: 30px">Consultas</h4>
          </div>
          <div class="box-body" style="overflow-y: scroll; height: 500px;">
            <!-- the events -->
            <div id="external-events">
              <!-- <?php
                    foreach ($arrayEventos as $consulta) { ?>
                <div class="external-event bg-green" style="padding: 10px;display: grid;grid-template-columns: auto auto auto auto auto auto; grid-gap: 10px;background-color: <?= $consulta['cor'] ?> !important">
                  <div class="item1" style="grid-column: 1 / 5; margin-top: auto; margin-bottom: auto;"><?= $consulta['nome'] ?> - <?= explode(" ", $consulta['horario'])[1] ?></div>
                  <div class="item2" style="text-align: right;"></div>
                  <div class="item3" style="text-align: right;">
                    <button class="btn btn-success btn-circle btn-circle-sm m-1">
                      <i class="fa fa-check"></i>
                    </button>
                    <button style="margin-left: 10px" class="btn btn-danger">
                      <i class="fa fa-close"></i>
                    </button>
                  </div>
                </div>
              <?php } ?> -->
            </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /. box -->
        <div class="box box-solid">
          <!-- <div class="box-header with-border">
              <h3 class="box-title">Criar Eventos</h3>
            </div> -->
          <div class="box-body">
            <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
              <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
              <!-- <ul class="fc-color-picker" id="color-chooser">
                  <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
                </ul> -->
            </div>
            <!-- /btn-group -->
            <div class="input-group">
              <!-- <input id="new-event" type="text" class="form-control" placeholder="Event Title">

                <div class="input-group-btn">
                  <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Add</button>
                </div> -->
              <!-- /btn-group -->
            </div>
            <!-- /input-group -->
          </div>
        </div>
      </div>
      <!-- /.col -->
    </div>
  </div>
</div>

<div id="cadastrar" class="modal fade" role="dialog" style="z-index: 999999999">
  <div class="modal-dialog" style="width:50%">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color: #e6e2e2">
        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title" style="text-align: center">Novo Agendamento</h4>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
          <div class="col-md-12">
            <div class="col-md-6">

              <div class="autocomplete col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                <label>Nome</label>
                <input class="np_autocomplete form-control" id="nome" type="text" name="Agenda[nome]" placeholder="Insira o Nome" required>
              </div>

              <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-top: 15px;" id="autocomplete_div_0">
                <label>CPF</label>
                <input class="np_autocomplete form-control" id="cpf" type="number" name="Agenda[cpf]" placeholder="Insira o CPF" required>
              </div>

              <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-top: 15px;">
                <label>Horário</label> <br>
                <input type="datetime-local" id="start" name="Agenda[horario]" class="form-control" required>
              </div>

            </div>
            <div class="col-md-6">
              <div class="autocomplete col-md-3" style="width:300px; padding: 0" id="autocomplete_div_0">
                <label>Tipo de Atendimento</label>
                <?= Html::dropDownList('Agenda[tipo_atendimento]', 'tipo_atendimento', $listProcedimento, ['class' => 'form-control', 'prompt' => 'Selecione um tipo']) ?>
              </div>

              <div class="autocomplete col-md-3" style="width:300px; padding: 0; margin-top: 15px;" id="autocomplete_div_0" hidden>
                <label>Plano de Saúde / Particular</label>
                <input class="np_autocomplete form-control" id="plano_particular" value="Particular" type="text" name="Agenda[plano_particular]" required>
              </div>

              <div class="autocomplete col-md-3" style="width:300px; padding: 0; margin-top: 15px;" id="autocomplete_div_0">
                <label>Responsável</label>
                <?= Html::dropDownList('Agenda[id_responsavel]', 'responsavel', $listResponsavel, ['class' => 'form-control', 'prompt' => 'Selecione um responsavel']) ?>
              </div>

              <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-top: 15px;">
                <?= $form->field($model, 'id_status')->dropDownList($listStatus); ?>
              </div>
            </div>
            <div class="col-md-12">
              <?= $form->field($model, 'descricao')->textArea(['maxlength' => true]) ?>
            </div>
          </div>


        </div>
        <div class="form-group" style="text-align: center">
          <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Salvar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>


      </div>
    </div>

  </div>

</div>


<div id="atualizar" class="modal fade" role="dialog" style="z-index: 999999999">
  <div class="modal-dialog" style="width:50%">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title" style="text-align: center">Agendamento</h4>
      </div>
      <div class="modal-body">

        <div class="row">
          <div class="col-md-12">
            <div class="col-md-6">

              <div class="autocomplete col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                <label>Nome</label>
                <input class="np_autocomplete form-control" id="up_nome" type="text" name="Agenda[nome]" placeholder="Insira um Nome">
              </div>

              <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-top: 15px" id="autocomplete_div_0">
                <label>CPF</label>
                <input class="np_autocomplete form-control" id="up_cpf" type="number" name="Agenda[cpf]" placeholder="Insira o CPF">
              </div>

              <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-top: 15px;">
                <label>Horário</label> <br>
                <input type="datetime-local" id="up_horario" name="Agenda[horario]" class="form-control" required>
              </div>

            </div>
            <div class="col-md-6">
              <div class="autocomplete col-md-3" style="width:300px; padding: 0" id="autocomplete_div_0">
                <label>Tipo de Atendimento</label>
                <?= Html::dropDownList('Agenda[tipo_atendimento]', null, $listProcedimento, ['id' => 'up_tipo_atendimento', 'class' => 'form-control', 'prompt' => 'Selecione um tipo']) ?>
                <!-- <input class="np_autocomplete form-control" id="up_tipo_atendimento" type="text" name="Agenda[tipo_atendimento]" placeholder="Insira o tipo de atendimento"> -->
              </div>

              <div class="autocomplete col-md-3" style="width:300px; padding: 0; margin-top: 15px;" id="autocomplete_div_0" hidden>
                <label>Plano de Saúde / Particular</label>
                <input class="np_autocomplete form-control" id="up_plano_particular" type="text" name="Agenda[plano_particular]" required>
              </div>

              <div class="autocomplete col-md-3" style="width:300px; padding: 0; margin-top: 15px;" id="autocomplete_div_0">
                <label>Responsável</label>
                <?= Html::dropDownList('Agenda[id_responsavel]', 'responsavel', $listResponsavel, ['id' => 'up_responsavel', 'class' => 'form-control', 'prompt' => 'Selecione um responsavel']) ?>
              </div>

              <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-top: 15px;">
                <?= $form->field($model, 'id_status')->dropDownList($listStatus, ['id' => 'up_status']); ?>
              </div>
            </div>

            <div class="col-md-12">
              <?= $form->field($model, 'descricao')->textArea(['maxlength' => true, 'id' => 'up_descricao']) ?>
            </div>

          </div>
          <div style="text-align: center">
            <button class="btn btn-primary" id="updateEvent">Alterar</button>
            <button style="margin-left: 10px;" class="btn btn-danger" id="deleteEvent">Excluir</button>
          </div>

        </div>
      </div>

    </div>
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

              <div class="autocomplete col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                <label>Nome</label>
                <input class="np_autocomplete form-control" id="conf_nome" type="text" name="Agenda[nome]" readonly>
              </div>

              <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-top: 15px;">
                <label>Dia - Horário</label> <br>
                <input type="datetime-local" id="conf_horario" name="Agenda[horario]" class="form-control" readonly>
              </div>

              <div class="autocomplete col-md-3" style="width:300px; padding: 0; margin-top: 15px;" id="autocomplete_div_0">
                <label>Responsável</label>
                <?= Html::dropDownList('Agenda[id_responsavel]', 'responsavel', $listResponsavel, ['id' => 'conf_responsavel', 'class' => 'form-control', 'prompt' => 'Selecione um responsavel']) ?>
              </div>

              <div class="autocomplete col-md-2" style="width:300px;padding: 0; margin-top: 15px;">
                <label>Valor</label>
                <input class="form-control" id="conf_valor" type="number" name="Agenda[valor]">
              </div>

            </div>
            <div class="col-md-6">
              <div class="autocomplete col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
                <label>CPF</label>
                <input class="np_autocomplete form-control" id="conf_cpf" type="text" name="Agenda[cpf]" readonly>
              </div>

              <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-top: 15px;">
                <label>Dente</label> <br>
                <input type="number" id="conf_dente" name="Agenda[dente]" class="form-control" required>
              </div>

              <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-top: 15px;">
                <label>Forma de pagamento</label> <br>
                <input type="text" id="forma_pagamento" name="Agenda[forma_pagamento]" class="form-control" readonly>
              </div>

              <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-top: 15px;">
                <label>Parcelamento</label>
                <select class="form-control" id="conf_parcela" name="Agenda[parcela]"></select>
              </div>

            </div>

            <div class="col-md-12" style="margin-top: 15px">
              <label>Tratamento realizado</label> <br>
              <textarea type="textarea" id="conf_tratamento" name="Agenda[tratamento]" class="form-control" maxlength="255" required></textarea>
            </div>
          </div>
        </div>
        <?php ActiveForm::end(); ?>
        <div style="text-align: center; margin-top: 15px">
          <button class="btn btn-success" id="saveTratamento" onclick="finishAgendamento()">Salvar</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="forma_pagamento_modal" class="modal fade" role="dialog" style="z-index: 999999999">
  <div class="modal-dialog" style="width:25%">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title" style="text-align: center">Tratamento Realizado</h4>
      </div>
      <div class="modal-body">

        <div style="text-align: center; margin-top: 15px">
          <button class="btn btn-success" id="saveTratamento" onclick="finishAgendamento()">Salvar</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  var id_agendamento;

  function cancelAgenda(id_agenda) {
    $(document).ready(function() {
      $.ajax({
        url: 'index.php?r=agenda/delete',
        data: {
          id: id_agenda
        },
        type: 'POST',
        success: function(response) {
          console.log('success');
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });
  }

  function finishAgendamento() {
    var conf_dente = $('#conf_dente').val();
    var conf_tratamento = $('#conf_tratamento').val();
    if (conf_dente != '' && conf_tratamento != '') {
      $(document).ready(function() {
        $.ajax({
          url: 'index.php?r=agenda/finish',
          data: {
            id: id_agendamento,
            tratamento: conf_tratamento,
            dente: conf_dente
          },
          type: 'POST',
          success: function(response) {

          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });
    } else {
      alert('Preencha todos os campos!');
    }
  }

  function createTratamento(id_agenda) {
    id_agendamento = id_agenda;
    $.ajax({
      url: 'index.php?r=agenda/getevent',
      data: {
        id: id_agenda
      },
      type: 'POST',
      success: function(response) {
        var resposta = $.parseJSON(response);
        $('#conf_nome').val(resposta['nome']);
        $('#conf_cpf').val(resposta['cpf']);
        $('#conf_horario').val(resposta['horario']);
        $('#conf_valor').val(resposta['valor_inicial']);
        $('#conf_valor').attr("min", parseInt(resposta['valor_inicial']));
        $('#conf_valor').attr("max", parseInt(resposta['valor_final']));
        $('#conf_tratamento').val(resposta['nome_atendimento']);
        $('#conf_responsavel').val(resposta['id_responsavel']);
        document.getElementById("up_tipo_atendimento").value = resposta['tipo_atendimento'];

        // Add options parcelamento
        for (var i = 1; i <= 12; i++) {
          var valor_parcela = resposta['valor_inicial'] / i;

          if (valor_parcela < 50) break;

          var select = document.getElementById("conf_parcela");
          var option = document.createElement("option");
          option.text = i + "x de R$ " + valor_parcela.toFixed(2);
          select.add(option);

        }
        $('#tratamento_realizado').modal('show');
      },
      error: function(request, status, error) {

      }
    });
  }

  function viewTratamento(nome) {
    window.location.href = 'index.php?TratamentoRealizadoSearch%5Bagendamento%5D=' + nome + '&TratamentoRealizadoSearch%5Bdente%5D=&TratamentoRealizadoSearch%5Btratamento_realizado%5D=&r=tratamento-realizado%2Findex';
  }

  function confirmAgenda(id_agenda) {

    $(document).ready(function() {
      $.ajax({
        url: 'index.php?r=agenda/confirm',
        data: {
          id: id_agenda
        },
        type: 'POST',
        success: function(response) {
          console.log('success');
        },
        error: function(request, status, error) {
          console.log(request.responseText);
        }
      });
    });

  }

  function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;

    count_np = 0;
    $(".np_autocomplete").each(function() {
      count_np++;
    });
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) {
        return false;
      }
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
            /*insert the value for the autocomplete text field:*/
            inp.value = this.getElementsByTagName("input")[0].value;
            /*close the list of autocompleted values,
            (or any other open lists of autocompleted values:*/
            closeAllLists();
          });
          a.appendChild(b);
        }
      }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
    });

    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/
      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }

    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
          x[i].parentNode.removeChild(x[i]);
        }
      }
    }

    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function(e) {
      closeAllLists(e.target);
    });
  }

  /*An array containing all the country names in the world:*/

  var resp = [<?= $resp_autocomplete ?>];

  /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
  var i = 0;

  autocomplete(document.getElementById("responsavel"), resp);

  autocomplete(document.getElementById("up_tipo_atendimento"), resp);
</script>
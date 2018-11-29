<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\Agenda */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
  $eventos = '';
  foreach ($arrayEventos as $key => $evt) {
          $cor = Yii::$app->db->createCommand('SELECT cor FROM executante WHERE usuario_id='.$evt['responsavel'])->queryScalar();
          $eventos .= "{
                        id             : ".$evt['id'].",
                        title          : '".$evt['assunto']."',
                        start          : '".$evt['hr_inicio']."',
                        end            : '".$evt['hr_final']."',
                        backgroundColor: '".$cor."', 
                        borderColor    : '".$cor."'
                      },";
        }     

      
$this->registerJs("

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
            if(e.target == this)
                location.href = '" . Url::to(['agenda/update']) . "&id='+id;
        }
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
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

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
        today: 'hoje',
        month: 'mês',
        week : 'semana',
        day  : 'dia'
      },

      eventClick: function(calEvent, jsEvent, view) {
        document.getElementById('update-form').action = '/web/index.php?r=agenda%2Fupdate%2F&id='+calEvent.id;

        $.ajax({ 
          url: 'index.php?r=agenda/getevent',
          data: {id: calEvent.id},
          type: 'POST',
          success: function(response){
            var resposta = $.parseJSON(response);
            console.log(resposta);
            $('#up_projeto_id').val(resposta['projeto_id']);
            $('#up_responsavel').val(resposta['responsavel']);
            $('#up_contato').val(resposta['contato']);
            $('#up_assunto').val(resposta['assunto']);
            $('#up_hr_inicio').val(resposta['hr_inicio']);
            $('#up_hr_final').val(resposta['hr_final']);
            $('#up_local').val(resposta['local']);
            $('#up_status').val(resposta['status']);
            $('#up_descricao').val(resposta['descricao']);

            $('#atualizar').modal('show');
         },
         error: function(request, status, error){
          alert(request.responseText);
        }
      });
        
      },

      selectable: true,
      selectHelper: true,
      select: function(start, end){
        $('#cadastrar #start').val(moment(start).format('DD/MM/YYYY HH:mm:ss'));
        $('#cadastrar #end').val(moment(end).format('DD/MM/YYYY HH:mm:ss'));
        $('#cadastrar').modal('show');
      },
      //Random default events
      events    : [        
        ". $eventos ."
      ],
      editable  : true,
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
<div class="box box-primary">
    <div class="box-header with-border">
<div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-calendar"></i> Agenda </div>


<?php
  if(isset($_SESSION['msg'])){
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
  }
?>
   
<div class="row">
        <div class="col-md-3">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h4 class="box-title">Executantes</h4>
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-events">
                <?php foreach ($arrayExecutantes as $key => $exe) { ?>
                    <div class="external-event bg-green" style="background-color: <?= $exe['cor'] ?> !important"><?= $exe['nome'] ?></div>                
                <?php } ?>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Criar Eventos</h3>
            </div>
            <div class="box-body">
              <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                <ul class="fc-color-picker" id="color-chooser">
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
                </ul>
              </div>
              <!-- /btn-group -->
              <div class="input-group">
                <input id="new-event" type="text" class="form-control" placeholder="Event Title">

                <div class="input-group-btn">
                  <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Add</button>
                </div>
                <!-- /btn-group -->
              </div>
              <!-- /input-group -->
            </div>
          </div>
        </div>
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
        <!-- /.col -->
    </div>
</div>
 </div>

<div id="cadastrar" class="modal fade" role="dialog" style="z-index: 999999999">
  <div class="modal-dialog" style="width:50%">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        <h4 class="modal-title">Evento</h4>
      </div>
      <div class="modal-body">             
        <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
      <div class="col-md-12">   
        <div class="col-md-6">    
            <?= $form->field($model, 'projeto_id')->dropDownList($listProjetos,['prompt'=>'Selecione um Projeto']) ?>
            <?= $form->field($model, 'contato')->dropDownList($listContatos, ['prompt'=>'Selecione um contato']); ?> 
            <?= $form->field($model, 'hr_inicio')->widget(\yii\widgets\MaskedInput::className(), [
                            'mask' => '99/99/9999 99:99:99',
                        ])->textInput(['id' => 'start']) ?>
            <?= $form->field($model, 'local')->textInput(['maxlength' => true]) ?>  
            
        </div>
        <div class="col-md-6">         
            <?= $form->field($model, 'responsavel')->dropDownList($listExecutantes, ['prompt'=>'Selecione um responsável']); ?>
            <?= $form->field($model, 'assunto')->textInput(['maxlength' => true]) ?> 
            <?= $form->field($model, 'hr_final')->widget(\yii\widgets\MaskedInput::className(), [
                            'mask' => '99/99/9999 99:99:99',
                        ])->textInput(['id' => 'end']) ?>  
            <?= $form->field($model, 'status')->dropDownList($listStatus); ?>
        </div>
        <div class="col-md-12"> 
            <?= $form->field($model, 'descricao')->textArea(['maxlength' => true]) ?> 
        </div>   
      </div>
             
        
         </div>
        <div class="form-group">
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
          
        <h4 class="modal-title">Evento</h4>
      </div>
      <div class="modal-body">             
        
    <?php $form = ActiveForm::begin();  $form->options['id'] = 'update-form'; ?>
    <div class="row">
      <div class="col-md-12">   
        <div class="col-md-6">    
          
            <?= $form->field($model, 'projeto_id')->dropDownList($listProjetos,['prompt'=>'Selecione um Projeto', 'id' => 'up_projeto_id']) ?>
            <?= $form->field($model, 'contato')->dropDownList($listContatos, ['prompt'=>'Selecione um contato', 'id' => 'up_contato']); ?> 
            <?= $form->field($model, 'hr_inicio')->widget(\yii\widgets\MaskedInput::className(), [
                            'mask' => '99/99/9999 99:99:99',
                        ])->textInput(['id' => 'up_hr_inicio']) ?>
            <?= $form->field($model, 'local')->textInput(['maxlength' => true, 'id' => 'up_local']) ?>  
            
        </div>
        <div class="col-md-6">         
            <?= $form->field($model, 'responsavel')->dropDownList($listExecutantes, ['prompt'=>'Selecione um responsável', 'id' => 'up_responsavel']); ?>
            <?= $form->field($model, 'assunto')->textInput(['maxlength' => true, 'id' => 'up_assunto']) ?> 
            <?= $form->field($model, 'hr_final')->widget(\yii\widgets\MaskedInput::className(), [
                            'mask' => '99/99/9999 99:99:99',
                        ])->textInput(['id' => 'up_hr_final']) ?>  
            <?= $form->field($model, 'status')->dropDownList($listStatus, ['id' => 'up_status']); ?>
        </div>
        <div class="col-md-12"> 
            <?= $form->field($model, 'descricao')->textArea(['maxlength' => true, 'id' => 'up_descricao']) ?> 
        </div>   
      </div>
             
        
         </div>
        <div class="form-group">
            <?= Html::submitButton('Salvar', ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-primary']) ?>
        </div>
        
        <?php ActiveForm::end(); ?>
    
    </div>
    </div>
    
      </div>
     
    </div>


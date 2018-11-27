<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Agenda */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
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
      //Random default events
      events    : [
        {
          title          : 'All Day Event',
          start          : new Date(y, m, 1),
          backgroundColor: '#f56954', //red
          borderColor    : '#f56954' //red
        },
        {
          title          : 'Long Event',
          start          : new Date(y, m, d - 5),
          end            : new Date(y, m, d - 2),
          backgroundColor: '#f39c12', //yellow
          borderColor    : '#f39c12' //yellow
        },
        {
          title          : 'Meeting',
          start          : new Date(y, m, d, 10, 30),
          allDay         : false,
          backgroundColor: '#0073b7', //Blue
          borderColor    : '#0073b7' //Blue
        },
        {
          title          : 'Lunch',
          start          : new Date(y, m, d, 12, 0),
          end            : new Date(y, m, d, 14, 0),
          allDay         : false,
          backgroundColor: '#00c0ef', //Info (aqua)
          borderColor    : '#00c0ef' //Info (aqua)
        },
        {
          title          : 'Birthday Party',
          start          : new Date(y, m, d + 1, 19, 0),
          end            : new Date(y, m, d + 1, 22, 30),
          allDay         : false,
          backgroundColor: '#00a65a', //Success (green)
          borderColor    : '#00a65a' //Success (green)
        },
        {
          title          : 'Click for Google',
          start          : new Date(y, m, 28),
          end            : new Date(y, m, 29),
          url            : 'http://google.com/',
          backgroundColor: '#3c8dbc', //Primary (light-blue)
          borderColor    : '#3c8dbc' //Primary (light-blue)
        }
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
<div style="margin-bottom:1em;margin-top: 1em">
    <?= Html::a('Mostrar Todos', ['/agenda/create', 'pagination' => true], ['class'=>'btn btn-primary grid-button']) ?>
</div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'options' => ['style' => 'font-size:12px;'],
        // 'pjax' => true,
        
        // 'hover' => true,
        /*'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-calendar"></i> Agenda'
        ],*/
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            
            [
                'attribute' => 'projeto_id',
                'value' => function($data){
                    if(isset($data->projeto_id))
                    return Yii::$app->db->createCommand('SELECT nome FROM projeto WHERE id='.$data->projeto_id)->queryScalar();
                }
            ],     
            [
              'attribute' => 'status',
              'format' => 'raw',
              'value' => function ($data) {

                $status = Yii::$app->db->createCommand('SELECT status, cor FROM agenda_status WHERE id='.$data->status)->queryOne();
                
               return '<span style="color:'.$status['cor'].' "><i class="fa fa-circle" aria-hidden="true"></i> '.$status['status'].'</span>';

               },
            ],       
            /*[
              'attribute' => 'status',      
              'class' => 'kartik\grid\EditableColumn',        
              'format' => 'raw',
              'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
               'value' => function ($data) {

                $status = Yii::$app->db->createCommand('SELECT status, cor FROM agenda_status WHERE id='.$data->status)->queryOne();
                
               return '<span style="color:'.$status['cor'].' "><i class="fa fa-circle" aria-hidden="true"></i> '.$status['status'].'</span>';

               },
                'editableOptions' => [
              'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
              'data' => $listStatus                
              ]
            ],*/
            [
                'attribute' => 'data',
                'value' => function($data){
                    if(!empty($data->data))
                    return date_format(DateTime::createFromFormat('Y-m-d', $data->data), 'd/m/Y');
                }
            ], 
            [
                'attribute' => 'site',
                'value' => function($data){
                    if(isset($data->local) && !empty($data->local))
                    return Yii::$app->db->createCommand('SELECT nome FROM site WHERE id='.$data->local)->queryScalar();
                }
            ],         
            'quem',
            'assunto',
            'hr_inicio',
            'hr_final',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

<div class="row">
<div class="agenda-form col-md-6">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
    <div class="box-header with-border">
    <div class="row">
    <div class="col-md-3">    
        <?= $form->field($model, 'projeto_id')->dropDownList($listProjetos,['prompt'=>'Selecione um Projeto']) ?>
    
        
    
        </div>
        <div class="col-md-4"> 
        <!-- <//?= $form->field($model, 'local')->dropDownList($listSites,['prompt'=>'Selecione um Site']) ?> -->
    
        <?= $form->field($model, 'quem')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3"> 
        <?= $form->field($model, 'assunto')->textInput(['maxlength' => true]) ?>   
        
        </div>
        
        <div class="col-md-3">
            <?= $form->field($model, 'data')->widget(\yii\widgets\MaskedInput::className(), [
                            'mask' => '99/99/9999',
                        ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'hr_inicio')->widget(\yii\widgets\MaskedInput::className(), [
                            'mask' => '99:99:99',
                        ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'hr_final')->widget(\yii\widgets\MaskedInput::className(), [
                            'mask' => '99:99:99',
                        ]) ?>
        </div>

        </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Salvar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    </div>
</div>
    <?php ActiveForm::end(); ?>

    <!-- /.col -->
        <div class="col-md-6">
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

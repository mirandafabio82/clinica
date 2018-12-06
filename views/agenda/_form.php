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
          $cor = Yii::$app->db->createCommand('SELECT cor FROM executante JOIN user ON user.id = executante.usuario_id WHERE nome="'.$evt['responsavel'].'"')->queryScalar();
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

    var evento_id = '';

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
            if(e.target == this)
                location.href = '" . Url::to(['agenda/update']) . "&id='+id;
        }
    });

    $('#deleteEvent').click(function(){       
      if (confirm('Tem certeza que deseja excluir esse evento?')) {
          $.ajax({ 
            url: 'index.php?r=agenda/delete',
            data: {id: evento_id},
            type: 'POST',
            success: function(response){
                 console.log('success');
            },
            error: function(request, status, error){
              alert(request.responseText);
            }
          });
      } else {
        
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
        today: 'hoje',
        month: 'mês',
        week : 'semana',
        day  : 'dia'
      },

      eventDrop: function(event) {
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
              alert(request.responseText);
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
              alert(request.responseText);
            }
          });
      },

      eventClick: function(calEvent, jsEvent, view) {
        document.getElementById('update-form').action = '/web/index.php?r=agenda%2Fupdate%2F&id='+calEvent.id;
        evento_id = calEvent.id;
        $.ajax({ 
          url: 'index.php?r=agenda/getevent',
          data: {id: calEvent.id},
          type: 'POST',
          success: function(response){
            var resposta = $.parseJSON(response);
            console.log(resposta);
            $('#up_projeto').val(resposta['projeto']);
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
        $('#cadastrar #start').val(moment(start).format('YYYY-MM-DD')+'T'+moment(start).format('HH:mm:ss'));
        $('#cadastrar #end').val(moment(end).format('YYYY-MM-DD')+'T'+moment(end).format('HH:mm:ss'));
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
            
             
            <div class="autocomplete col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
              <label>Projeto</label>
              <input class="np_autocomplete form-control" id="projeto" type="text" name="Agenda[projeto]" placeholder="Insira um Projeto"> 
            </div>
            
            <div class="autocomplete col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
              <label>Contato</label>
              <input class="np_autocomplete form-control" id="contato" type="text" name="Agenda[contato]" placeholder="Insira um Contato"> 
            </div> 

            <label>Hora Início</label> <br>    
            <input type="datetime-local" id="start" name="Agenda[hr_inicio]" class="form-control">
            
            <?= $form->field($model, 'local')->textInput(['maxlength' => true]) ?>  
            
        </div>
        <div class="col-md-6">         
            <div class="autocomplete col-md-3" style="width:300px; padding: 0" id="autocomplete_div_0">
              <label>Executante</label>
              <input class="np_autocomplete form-control" id="responsavel" type="text" name="Agenda[responsavel]" placeholder="Insira um Responsável"> 
            </div> 
            <?= $form->field($model, 'assunto')->textInput(['maxlength' => true]) ?> 

            <label>Hora Final</label> <br>  
            <input type="datetime-local" id="end" name="Agenda[hr_final]" class="form-control"> 

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
          
            <div class="autocomplete col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
              <label>Projeto</label>
              <input class="np_autocomplete form-control" id="up_projeto" type="text" name="Agenda[projeto]" placeholder="Insira um Projeto"> 
            </div>
            
            <div class="autocomplete col-md-3" style="width:300px;padding: 0" id="autocomplete_div_0">
              <label>Contato</label>
              <input class="np_autocomplete form-control" id="up_contato" type="text" name="Agenda[contato]" placeholder="Insira um Contato"> 
            </div>        
            <label>Hora Início</label> <br>      
            <input type="datetime-local" id="up_hr_inicio" name="Agenda[hr_inicio]" class="form-control"> 

            <?= $form->field($model, 'local')->textInput(['maxlength' => true, 'id' => 'up_local']) ?>  
            
        </div>
        <div class="col-md-6">         
            <div class="autocomplete col-md-3" style="width:300px; padding: 0" id="autocomplete_div_0">
              <label>Responsável</label>
              <input class="np_autocomplete form-control" id="up_responsavel" type="text" name="Agenda[responsavel]" placeholder="Insira um Responsável"> 
            </div>
            <?= $form->field($model, 'assunto')->textInput(['maxlength' => true, 'id' => 'up_assunto']) ?> 
            
            <label>Hora Final</label> <br>  
            <input type="datetime-local" id="up_hr_final" name="Agenda[hr_final]" class="form-control"> 

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
        <button class="btn btn-danger" id="deleteEvent" >Excluir</button>
    
    </div>
    </div>
    
      </div>
     
    </div>


<script>
function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;

  count_np = 0;
  $( ".np_autocomplete" ).each(function() {
    count_np++;
  });
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
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
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
      });
}

/*An array containing all the country names in the world:*/
var proj = [<?= $proj_autocomplete ?>];
var cont = [<?= $cont_autocomplete ?>];
var resp = [<?= $resp_autocomplete ?>];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
var i = 0;

  autocomplete(document.getElementById("projeto"), proj);
  autocomplete(document.getElementById("contato"), cont);
  autocomplete(document.getElementById("responsavel"), resp);

  autocomplete(document.getElementById("up_projeto"), proj);
  autocomplete(document.getElementById("up_contato"), cont);
  autocomplete(document.getElementById("up_responsavel"), resp);



</script>

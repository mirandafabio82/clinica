<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\money\MaskMoney;
use kartik\tabs\TabsX;
use kartik\popover\PopoverX;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use app\models\Projeto;

/* @var $this yii\web\View */

$this->title = 'HCN Automação';

$this->registerJs(' 
    $( document ).ready(function() {
     //  $("#rel_resumido").click(); 
    });

    $("#rel_resumido").click(function(){
        var id = $("#projeto_rel_resumido").val();    
        var tabela = document.getElementById("tabela_rel_resumido");
        var tabela_header = document.getElementById("tabela_rel_resumido_header");
        
        if(tabela.rows.length>1){
            for(var i=1;i<=tabela.rows.length;i++){
              $("#tabela_rel_resumido tr").remove(); 
            }
          }    

          if(tabela_header.rows.length>1){
            for(var i=1;i<=tabela_header.rows.length;i++){
              $("#tabela_rel_resumido_header tr").remove(); 
            }
          }    

       // $("#rel_resumido").attr("disabled", true);

        row = tabela.insertRow(0);
        cell1 = row.insertCell(0);
        cell2 = row.insertCell(1);
        cell3 = row.insertCell(2);
        cell4 = row.insertCell(3);
        cell5 = row.insertCell(4);
        cell6 = row.insertCell(5);
        cell7 = row.insertCell(6);
        cell8 = row.insertCell(7);
        cell9 = row.insertCell(8);
        cell10 = row.insertCell(9);
        
         cell1.innerHTML = "BM";
         cell2.innerHTML = "Data";
         cell3.innerHTML = "Valor";
         cell4.innerHTML = "Andamento";
         cell5.innerHTML = "FRS";
         cell6.innerHTML = "Data FRS";
         cell7.innerHTML = "NF";
         cell8.innerHTML = "Data NF";
         cell9.innerHTML = "Pagamento";
         cell10.innerHTML ="Data Pagamento";

         document.getElementById("progress-bar").style.width = "0%";
        
        $.ajax({ 
          url: "index.php?r=site/projetoresumo",
          data: {id: id},
          type: "POST",
          success: function(response){
            var bms = $.parseJSON(response);
            
            console.log(bms);
            
            var porcentagem = 0;
            var valorTotal = 0;
            var pagamento = 0;

            row_header = tabela_header.insertRow(0);
            cell1_header = row_header.insertCell(0);
            row_header.bgColor = "blanchedalmond";
            
            cell1_header.innerHTML = bms[0]["projeto_nome"];   
            cell1_header.innerHTML = cell1_header.innerHTML + " &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; " +bms[0]["projeto_tipo"];         
            cell1_header.innerHTML = cell1_header.innerHTML + " &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; " +bms[0]["site"];
            cell1_header.innerHTML = cell1_header.innerHTML + " &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;" +bms[0]["contato"];
            cell1_header.style.fontWeight = "bolder";
            
            row_header = tabela_header.insertRow(1);
            cell1_header = row_header.insertCell(0);
            row_header.bgColor = "blanchedalmond";    

            cell1_header.innerHTML = bms[0]["descricao"];
            cell1_header.innerHTML = cell1_header.innerHTML + " &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; "  + bms[0]["proposta"];
            cell1_header.innerHTML = cell1_header.innerHTML + " &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; "  + formatData(bms[0]["data_proposta"]);
            cell1_header.innerHTML = cell1_header.innerHTML + " &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; "  + "R$ " + mascaraValor(bms[0]["valor_proposta"]);
            cell1_header.style.fontWeight = "bolder";

            for(var i=0;i<bms.length;i++){

                row = tabela.insertRow(i+1);
                cell1 = row.insertCell(0);
                cell2 = row.insertCell(1);
                cell3 = row.insertCell(2);
                cell4 = row.insertCell(3);
                cell5 = row.insertCell(4);
                cell6 = row.insertCell(5);
                cell7 = row.insertCell(6);
                cell8 = row.insertCell(7);
                cell9 = row.insertCell(8);
                cell10 = row.insertCell(9);

                cell1.innerHTML = bms[i]["bm_num"];
                cell2.innerHTML = formatData(bms[i]["bm_data"]);
                if(bms[i]["bm_valor"] != null)
                 cell3.innerHTML = "R$ " + mascaraValor(bms[i]["bm_valor"]);
                else
                  cell3.innerHTML = "";
                if(bms[i]["andamento"] != null)
                  cell4.innerHTML = Math.round10(bms[i]["andamento"], -1)+"%";
                else
                  cell4.innerHTML = "";
                cell5.innerHTML = bms[i]["frs"];

                if(bms[i]["frs_data"] != null)
                  cell6.innerHTML = formatData(bms[i]["frs_data"]);
                else
                  cell6.innerHTML = "";

                cell7.innerHTML = bms[i]["nota_fiscal"];
                  
                if(bms[i]["nfse_data"] != null)
                  cell8.innerHTML = formatData(bms[i]["nfse_data"]);
                else
                  cell8.innerHTML = "";
                
                if(bms[i]["pagamento"] != null)
                  cell9.innerHTML = "R$ " + mascaraValor(bms[i]["pagamento"]);
                else
                  cell9.innerHTML = "";
                if(bms[i]["andamento"]!=null){
                    porcentagem = porcentagem + Math.round10(bms[i]["andamento"], -1);
                }
                if(bms[i]["bm_valor"]!=null){
                  valorTotal = valorTotal + Math.round10(bms[i]["bm_valor"], -1);
                }
                if(bms[i]["pagamento"]!=null){
                  pagamento = pagamento + Math.round10(bms[i]["pagamento"], -1);
                }
                cell10.innerHTML = bms[i]["data_pagamento"];
                
            }
                row = tabela.insertRow(i+1);
                cell1 = row.insertCell(0);
                cell2 = row.insertCell(1);
                cell3 = row.insertCell(2);
                cell4 = row.insertCell(3);
                cell5 = row.insertCell(4);
                cell6 = row.insertCell(5);
                cell7 = row.insertCell(6);
                cell8 = row.insertCell(7);
                cell9 = row.insertCell(8);
                cell10 = row.insertCell(9);
                row.bgColor = "antiquewhite";

                cell1.innerHTML = "Total";
                cell3.innerHTML = "R$ " + mascaraValor(valorTotal);
                cell4.innerHTML = Math.round10(porcentagem, -1) +"%";
                cell9.innerHTML = "R$ " + mascaraValor(pagamento);
                
                row = tabela.insertRow(i+2);
                cell1 = row.insertCell(0);
                cell2 = row.insertCell(1);
                cell3 = row.insertCell(2);
                cell4 = row.insertCell(3);
                cell5 = row.insertCell(4);
                cell6 = row.insertCell(5);
                cell7 = row.insertCell(6);
                cell8 = row.insertCell(7);
                cell9 = row.insertCell(8);
                cell10 = row.insertCell(9);
                row.bgColor = "aliceblue";

                cell1.innerHTML = "Saldo";
                cell3.innerHTML = "R$ " + mascaraValor(valorTotal);
                cell4.innerHTML = Math.round10((100-porcentagem), -1) +"%";

                $("#label_evolucao").text("Evolução "+ Math.round10(porcentagem, -1) +"%");
                

            console.log(porcentagem);
           // $("#rel_resumido").attr("disabled", false);
            document.getElementById("progress-bar").style.width = porcentagem+"%";
        },
        error: function(){
          console.log("failure");
        }
      });
    });   

    function mascaraValor(valor) {
          valor = valor * 100;
        valor = valor.toString().replace(/\D/g,"");
        valor = valor.toString().replace(/(\d)(\d{8})$/,"$1.$2");
        valor = valor.toString().replace(/(\d)(\d{5})$/,"$1.$2");
        valor = valor.toString().replace(/(\d)(\d{2})$/,"$1,$2");
        return valor                    
    }

    function formatData(data){
      var aux = data.split("-");
      data = aux[2]+"/"+aux[1]+"/"+aux[0];
      return data;
    }

');
?>

<style>
  div.scrollmenu {
      overflow: auto;
      white-space: nowrap;
  }

  div.scrollmenu a {
      display: inline-block;
      color: white;
      text-align: center;
      padding: 14px;
      text-decoration: none;
  }
  
  .endRows {
        background-color: aliceblue;
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

    ?>

<?php if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin']) || (isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante']) && (Yii::$app->db->createCommand('SELECT cargo FROM executante WHERE usuario_id='.Yii::$app->user->id)->queryScalar() == 2))){ ?>
<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?= $numBm -1 ?></h3>

              <p>Nº do último BM</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $concluido ?></h3>

              <p>Projetos Concluídos</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= $aguardando ?></h3>

              <p>Projetos Aguardando Aprovação</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?= $emitirAS ?></h3>

              <p>Projetos Aguardando Emitir AS</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
      </div>
<?php } ?>
     <div class="row">
            
      
<?php if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin']) || (isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante']) && (Yii::$app->db->createCommand('SELECT cargo FROM executante WHERE usuario_id='.Yii::$app->user->id)->queryScalar() == 2))){ ?>
      

      <div class="col-md-12">
          <!-- PRODUCT LIST -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Relatório Resumido</h3>    
            
                    <div class="row" style="margin-bottom: 0.5em">
                      <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-left:1em" id="autocomplete_div_0">
                        <?= Html::dropDownList('id', null, $listAllProjetos, ['id' => 'projeto_rel_resumido', 'class' => 'form-control']) ?>
                      </div>                            
                    <div class="row" >
                      <div class="col-md-2">
                        <button class="btn btn-primary" type="submit" id="rel_resumido" form="form-relgeral" value="Submit">Gerar Relatório</button>
                      </div>
                      <div class="col-md-2" style="margin-left: -5em; margin-top: -1em">
                        <label id="label_evolucao"></label>
                        <div class="progress progress-xs" style="margin-bottom: 2px; background-color: #9c9898; height: 20px;">
                          <div class="progress-bar progress-bar-success progress-bar-striped" id="progress-bar" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                      

                    
                    <table id="tabela_rel_resumido_header" class="table table-hover" style="margin-bottom: 0px;"> 
                        <tr>                                              
                        </tr>                    
                    </table>                 

                  <div class="scrollmenu" style="height: 20em">
                    <table id="tabela_rel_resumido" class="table table-hover">
                      <tr>
                        <th>BM</th>
                        <th>Data</th>
                        <th>Valor</th>
                        <th align="center">Andamento</th>
                        <th>FRS</th>
                        <th>Data FRS</th>
                        <th>NF</th>
                        <th>Data NF</th>
                        <th>Pagamento</th>
                        <th>Data Pagamento</th>
                      </tr>                    
                    </table>
                  </div>                                
                
            </div>            
          </div>
           
      </div>

  <div class="col-md-6">
        <!-- PRODUCT LIST -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Últimas Atividades</h3>
              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <?php foreach ($logs as $key => $log) { ?> 
                  <li class="item">                   
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-description"><?= $log['descricao'] ?><span
                          class="label label-info pull-right"><?= date_format(DateTime::createFromFormat('Y-m-d H:i:s', $log['data']), 'd/m/Y H:i:s') ?></span></a>                 
                      
                    </div>
                  </li>
                <?php } ?>
                <!-- /.item -->
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="<?= Url::to(['log/index']) ?>" class="uppercase">Visualizar o Log Completo</a>
            </div>
            <!-- /.box-footer -->
          </div> 

      </div>
<?php } ?>
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

<?php if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin']) || (isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante']) && (Yii::$app->db->createCommand('SELECT cargo FROM executante WHERE usuario_id='.Yii::$app->user->id)->queryScalar() == 2))){ ?>

<div class="col-md-6">
         <!-- PRODUCT LIST -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Pagamentos Previstos</h3>              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <?php 
                  if(!empty($pagamentos_dia)){
                  foreach ($pagamentos_dia as $key => $pgt) { ?> 

                  <li class="item">                   
                    <div class="product-info">
                      <a href="<?= Url::to(['bm/update','id'=> $pgt['bm_id']]) ?>" class="product-description">Existe pagamento previsto para <?= Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.$pgt['executante_id'])->queryScalar()  ?> referente ao BM Nº <?= Yii::$app->db->createCommand('SELECT numero_bm FROM bm WHERE id='.$pgt['bm_id'])->queryScalar()  ?><span
                          class="label label-info pull-right"><?= date_format(DateTime::createFromFormat('Y-m-d', $pgt['previsao_pgt']), 'd/m/Y') ?></span></a>                 
                      
                    </div>
                  </li>
                <?php }} else{ ?>
                        <div class="product-info">
                      <a href="javascript:void(0)" class="product-description">Não existe pagamento a ser realizado</a>                 
                      
                    </div>
                <?php } ?>
                <!-- /.item -->
              </ul>
            </div>
            
          </div>     
      
</div>        
 <?php } ?>


<script>
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
        /*$(this).draggable({
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
      //Random default events
      events    : [ <?=$eventos?> ],
      editable  : false, // this allows things to be dropped onto the calendar !!!
    
    })

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    
  
  });

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

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
var i = 0;

  autocomplete(document.getElementById("projeto"), proj);
  autocomplete(document.getElementById("contato"), cont);

  autocomplete(document.getElementById("up_projeto"), proj);
  autocomplete(document.getElementById("up_contato"), cont);

</script>

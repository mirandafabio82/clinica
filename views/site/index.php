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
        console.log(id);   
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

         cell1.style.fontWeight = "bolder"; cell2.style.fontWeight = "bolder"; cell3.style.fontWeight = "bolder"; cell4.style.fontWeight = "bolder"; cell5.style.fontWeight = "bolder"; cell6.style.fontWeight = "bolder"; cell7.style.fontWeight = "bolder"; cell8.style.fontWeight = "bolder"; cell9.style.fontWeight = "bolder"; cell10.style.fontWeight = "bolder";

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
            var valorProposta = 0;

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

            valorProposta = bms[0]["valor_proposta"];

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
                cell3.innerHTML = "R$ " + mascaraValor(valorProposta - valorTotal);
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

        valor = Math.round((valor * 100));

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

', \yii\web\View::POS_READY);

$this->registerJs(' 

  $("#projeto_rel_resumido").select2(); 
  "select2-projeto_rel_resumido-results"

', \yii\web\View::POS_READY);
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

  .select2-results__options {
    background-color: white;
  }
</style>

<?php
$eventos = '';
foreach ($arrayEventos as $key => $evt) {
  $cor = 'blue';
  $eventos .= "{
                        id             : " . 4 . ",
                        title          : '" . 'Teste' . "',
                        start          : '" .'14:00' . "',
                        end            : '" . '15:00' . "',
                        backgroundColor: '" . $cor . "', 
                        borderColor    : '" . $cor . "'
                      },";
}

?>
<div class="box-body no-padding">
  <h1></h1>
</div>


<script>
  $(function() {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      ele.each(function() {

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
    var d = date.getDate(),
      m = date.getMonth(),
      y = date.getFullYear()
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'Hoje',
        month: 'Mês',
        week: 'Semana',
        day: 'Dia'
      },
      defaultView: 'agendaDay',
      //Random default events
      events: [<?= $eventos ?>],
      editable: false, // this allows things to be dropped onto the calendar !!!

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
  var proj = [<?= $proj_autocomplete ?>];
  var cont = [<?= $cont_autocomplete ?>];

  /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
  var i = 0;

  autocomplete(document.getElementById("projeto"), proj);
  autocomplete(document.getElementById("up_projeto"), proj);
</script>
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\tabs\TabsX;
use kartik\select2\Select2;
?>

<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: center;
    padding: 2px;
}

tr:nth-child(even) {
    
}

th {
    background-color: #3c8dbc;
    color: white;
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
</style>

<?php
$this->registerJs('

  $( document ).ready(function() {
      document.title = "HCN - Relatórios";
      initS2Loading("projeto-id","s2options_d6851687");
    });

  $("#bm_btn").click(function(ev){
    file_bm = "";
    console.log("click");
    $.ajax({ 
        url: "index.php?r=faturamento/readbm",
        data: {file: file_bm},
        type: "POST",
        success: function(response){
        
        },
        error: function(){
         console.log("failure");
        }
    });
  });

  $("#relatorio-extrato-btn").click(function(ev){

      var bms = "";
      var executante = "";
      if($(".extrato_checkbox:checkbox:checked").length > 0){
        $(".extrato_checkbox:checkbox:checked").each(function( index ) {        
            var bm = $( this )[0].id.split("-")[0].split("_")[1];
            var exe = $( this )[0].id.split("-")[1].split("_")[1];
            
            executante = executante+exe+"-";
            bms = bms+bm+"-";           
        });

        executante = executante.split("-")[0];
        
        bms = bms.substring(0, bms.length - 1);
        
        window.open("index.php?r=relatorio/relatorioextrato&bms="+bms+"&executante_id="+executante, "_blank");
    }
    else{
      alert("Nenhum Checkbox foi selecionado");
    }
  });

  
  $("#gerar-fluxocaixa").click(function(ev){
    var check_extrato = $("#pg_checkbox").is(":checked");
    var executante_id = $("#executante-extrato-id").val();
    var tabela = $("#tabela_extrato");
    console.log(executante_id);
    $.ajax({ 
        url: "index.php?r=relatorio/fluxocaixa",
        data: {executante_id: executante_id, check_extrato: check_extrato},
        type: "POST",
        success: function(response){
          var obj = JSON.parse(response);
          console.log(obj);
          var i = 0;

          //remove as rows
          $("#tabela_fluxocaixa").find("tr:gt(1)").remove();
           var vl_hh_ee = '.Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=5')->queryScalar().';
           var vl_hh_es = '.Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=4')->queryScalar().';
           var vl_hh_ep = '.Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=3')->queryScalar().';
           var vl_hh_ej = '.Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=2')->queryScalar().';
           var vl_hh_tp = '.Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=1')->queryScalar().';

          
          for(i=0; i < (obj.length)/2; i++){
            var executado_ee =  obj[i+(obj.length)/2].executado_ee;
            var executado_es =  obj[i+(obj.length)/2].executado_es;
            var executado_ep =  obj[i+(obj.length)/2].executado_ep;
            var executado_ej =  obj[i+(obj.length)/2].executado_ej;
            var executado_tp =  obj[i+(obj.length)/2].executado_tp;

            var row = tabela[0].insertRow(i+2);
            row.insertCell(0).innerHTML = i+1;
            row.insertCell(1).innerHTML = obj[i].nome;
            row.insertCell(2).innerHTML = obj[i].numero_bm;
            row.insertCell(3).innerHTML = obj[i].descricao;
            row.insertCell(4).innerHTML = executado_ee;
            row.insertCell(5).innerHTML = executado_es;
            row.insertCell(6).innerHTML = executado_ep;
            row.insertCell(7).innerHTML = executado_ej;
            row.insertCell(8).innerHTML = executado_tp;
            row.insertCell(9).innerHTML = formataDinheiro(executado_ee*vl_hh_ee + executado_es*vl_hh_es + executado_ep*vl_hh_ep + executado_ej*vl_hh_ej + executado_tp*vl_hh_tp);
            row.insertCell(10).innerHTML = "<input type=\"date\" value="+obj[i].data_pgt+" class=\"extrato_date\" id=date_bm_"+obj[i].id+"-exe_"+executante_id+" disabled>";
            row.insertCell(11).innerHTML = "<input type=\"checkbox\" class=\"extrato_checkbox\" id=bm_"+obj[i].id+"-exe_"+executante_id+"-row_"+(i+2)+" >";
          }

          $(".extrato_date").change(function(ev){
              console.log("asdasd");
          });

        },
        error: function(){
         console.log("failure");
        }
    });
  });   

  $("#filtrar-extrato").click(function(ev){
    var check_extrato = $("#pg_checkbox").is(":checked");
    var executante_id = $("#executante-extrato-id").val();
    var tabela = $("#tabela_extrato");
    
    $.ajax({ 
        url: "index.php?r=relatorio/tabelaextrato",
        data: {executante_id: executante_id, check_extrato: check_extrato},
        type: "POST",
        success: function(response){
          var obj = JSON.parse(response);
          console.log(obj);
          var i = 0;

          //remove as rows
          $("#tabela_extrato").find("tr:gt(1)").remove();
           var vl_hh_ee = '.Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=5')->queryScalar().';
           var vl_hh_es = '.Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=4')->queryScalar().';
           var vl_hh_ep = '.Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=3')->queryScalar().';
           var vl_hh_ej = '.Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=2')->queryScalar().';
           var vl_hh_tp = '.Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=1')->queryScalar().';

          
          for(i=0; i < (obj.length)/2; i++){
            var executado_ee =  obj[i+(obj.length)/2].executado_ee;
            var executado_es =  obj[i+(obj.length)/2].executado_es;
            var executado_ep =  obj[i+(obj.length)/2].executado_ep;
            var executado_ej =  obj[i+(obj.length)/2].executado_ej;
            var executado_tp =  obj[i+(obj.length)/2].executado_tp;

            var row = tabela[0].insertRow(i+2);
            row.insertCell(0).innerHTML = i+1;
            row.insertCell(1).innerHTML = obj[i].nome;
            row.insertCell(2).innerHTML = obj[i].numero_bm;
            row.insertCell(3).innerHTML = obj[i].descricao;
            row.insertCell(4).innerHTML = executado_ee;
            row.insertCell(5).innerHTML = executado_es;
            row.insertCell(6).innerHTML = executado_ep;
            row.insertCell(7).innerHTML = executado_ej;
            row.insertCell(8).innerHTML = executado_tp;
            row.insertCell(9).innerHTML = formataDinheiro(executado_ee*vl_hh_ee + executado_es*vl_hh_es + executado_ep*vl_hh_ep + executado_ej*vl_hh_ej + executado_tp*vl_hh_tp);
            row.insertCell(10).innerHTML = "<input type=\"date\" value="+obj[i].data_pgt+" class=\"extrato_date\" id=date_bm_"+obj[i].id+"-exe_"+executante_id+" disabled>";
            row.insertCell(11).innerHTML = "<input type=\"checkbox\" class=\"extrato_checkbox\" id=bm_"+obj[i].id+"-exe_"+executante_id+"-row_"+(i+2)+" >";
          }

          $(".extrato_date").change(function(ev){
              console.log("asdasd");
          });

        },
        error: function(){
         console.log("failure");
        }
    });
  });   

  function formataDinheiro(n) {
    return n.toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
  }

   
');
?>


<div class="box box-primary">
    <div class="box-header with-border">
<div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-calculator"></i> Faturamento </div>

<?php
      $prestadores = '';
     foreach ($listPrestadores as $key => $prest) {                   
        $prestadores .= '<option value="'.$key.'">'.$prest.' </option>';        
     }

    $projetos = '';
     foreach ($listProjetos as $key => $prost) {                   
        $projetos .= '<option value="'.$key.'">'.$prost.' </option>';        
     }

      $form = ActiveForm::begin(); 
     $rel_geral_content = '<form action="/web/index.php?r=relatorio%2Frelatoriogeral" method="post" id="form-relgeral">
                          <div class="row">
                            <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-left:1em" id="autocomplete_div_0">
                              <label>Projeto</label>
                              <input class="np_autocomplete form-control" id="projeto" type="text" name="projeto" placeholder="Insira um Projeto"> 
                            </div>
                            <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-left:1em" id="autocomplete_div_0">
                              <label>Contato</label>
                              <input class="np_autocomplete form-control" id="contato" type="text" name="contato" placeholder="Insira um Projeto"> 
                            </div>
                         
                          </div>
                          <div class="row" style="margin-top:1em">                            
                            <div class="col-md-2">
                              Período BM
                              <input type="date" name="bm_de" class="form-control" placeholder="de">                           
                              <input type="date" name="bm_ate" class="form-control" placeholder="até">
                            </div>
                            <div class="col-md-2">
                              Período AS
                              <input type="date" name="as_de" class="form-control" placeholder="de">                            
                              <input type="date" name="as_ate" class="form-control" placeholder="até">
                            </div>
                            <div class="col-md-2">
                              Período FRS
                              <input type="date" name="frs_de" class="form-control" placeholder="de">                            
                              <input type="date" name="frs_ate" class="form-control" placeholder="até">
                            </div>
                          </div>
                          <div class="row" style="margin-top:1em">
                            <div class="col-md-1">
                              <input type="checkbox" name="bm" checked> Com BM
                            </div>
                            <div class="col-md-1">
                              <input type="checkbox" name="frs" checked> Com FRS
                            </div>
                            <div class="col-md-1">
                              <input type="checkbox" name="valor" checked> Mostrar Valores
                            </div>
                        </div>
                        <div class="row" style="margin-top:1em">
                          <div class="col-md-2">
                            <button class="btn btn-primary" type="submit" form="form-relgeral" value="Submit">Gerar Relatório</button>
                          </div>
                        </div>
                      </form>
     ';
  ActiveForm::end();
   $extrato_content = '<div style="margin-top:1em">
            <form id="form_extrato" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-4">
                  <select  id="executante-extrato-id" class="form-control executante" name="executante" >
                    <option value="">Selecione um Prestador</option>  
                    '.$prestadores.'
                  </select>
                </div>
                
                <div class="col-md-1">
                  <button type="button" class="btn btn-primary" id="filtrar-extrato">Filtrar</button>
                </div>
                <div class="col-md-1">
                  <button type="button" class="btn btn-primary" id="relatorio-extrato-btn" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Relatório</button>
                </div>
              </div>
               <div class="row">
                  <div class="col-md-2">
                  <input type="checkbox" name="pg_checkbox" id="pg_checkbox" checked> Exibir Pagos
                </div>
               </div> 
              </form>
            </div>
            <div style="margin-top: 1em">
              <table id="tabela_extrato">
                <tr>
                  <th rowspan="2">Item</th>
                  <th rowspan="2" style="width: 11em;" >Projeto</th>
                  <th rowspan="2">BM</th>
                  <th rowspan="2">Descrição</th>
                  <th colspan="5">Horas Executadas</th>
                  <th rowspan="2">Valor Total</th>
                  <th rowspan="2">Data Pagamento</th>
                  <th rowspan="2">Selecionar</th>
                </tr>
                <tr>
                  <th>EE</th>
                  <th>ES</th>
                  <th>EP</th>
                  <th>EJ</th>
                  <th>TP</th>                  
                </tr>             
                          
              </table>
              </div>
            ';

    $fluxo_caixa = '<div style="margin-top:1em">
            <form id="form_extrato" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-left:1em" id="autocomplete_div_0">
                  <label>Projeto</label>
                  <input class="np_autocomplete form-control" id="projeto" type="text" name="projeto" placeholder="Insira um Projeto"> 
                </div>
                <div class="col-md-1">
                  <button type="button" class="btn btn-primary" id="gerar-fluxocaixa">Gerar</button>
                </div>
                <div class="col-md-1">
                  <button type="button" class="btn btn-primary" id="relatorio-extrato-btn" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Relatório</button>
                </div>
              </div>
               <div class="row">                 
               </div> 
              </form>
            </div>
            <div style="margin-top: 1em">
              <table id="tabela_extrato">
                <tr>
                  <th rowspan="2">Item</th>
                  <th rowspan="2" style="width: 11em;" >Projeto</th>
                  <th rowspan="2">BM</th>
                  <th rowspan="2">Descrição</th>
                  <th colspan="5">Horas Executadas</th>
                  <th rowspan="2">Valor Total</th>
                  <th rowspan="2">Data Pagamento</th>
                  <th rowspan="2">Selecionar</th>
                </tr>
                <tr>
                  <th>EE</th>
                  <th>ES</th>
                  <th>EP</th>
                  <th>EJ</th>
                  <th>TP</th>                  
                </tr>             
                          
              </table>
              </div>
            ';


  $items = [
    
    [
        'label'=>'Extrato de Prestadores de Serviço',
        'content'=>$extrato_content,
        'active'=>true,       
    ],
    [
        'label'=>'Fluxo de Caixa',
        'content'=>$fluxo_caixa,
        'active'=>false,       
    ],
    [
        'label'=>'Relatório Geral',
        'content'=>$rel_geral_content,
        'active'=>false,       
    ],
      

  ]; 
?>

<?php
echo TabsX::widget([
    'items'=>$items,
    'position'=>TabsX::POS_ABOVE,
    'encodeLabels'=>false
]);
 ?>    
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

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
var i = 0;

  autocomplete(document.getElementById("projeto"), proj);
  autocomplete(document.getElementById("contato"), cont);

  autocomplete(document.getElementById("up_projeto"), proj);
  autocomplete(document.getElementById("up_contato"), cont);



</script>

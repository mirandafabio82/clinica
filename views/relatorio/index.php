<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\tabs\TabsX;
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
</style>

<?php
$this->registerJs('

  $( document ).ready(function() {
      document.title = "HCN - Relatórios";
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

  

  $("#filtrar-extrato").click(function(ev){
    var executante_id = $("#executante-extrato-id").val();
    var tabela = $("#tabela_extrato");
    console.log(executante_id);
    $.ajax({ 
        url: "index.php?r=relatorio/tabelaextrato",
        data: {executante_id: executante_id},
        type: "POST",
        success: function(response){
          var obj = JSON.parse(response);
          console.log(obj);
          var i = 0;

          //remove as rows
          $("#tabela_extrato").find("tr:gt(1)").remove();
          
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
            row.insertCell(9).innerHTML = formataDinheiro(executado_ee*obj[i].vl_hh_ee + executado_es*obj[i].vl_hh_es + executado_ep*obj[i].vl_hh_ep + executado_ej*obj[i].vl_hh_ej + executado_tp*obj[i].vl_hh_tp);
            row.insertCell(10).innerHTML = "<input type=\"date\" value="+obj[i].data_pgt+" class=\"extrato_date\" id=date_bm_"+obj[i].id+"-exe_"+executante_id+" >";
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
  
  
  $("#frs_btn").click(function(ev){
    
    var fileInput = document.getElementById("frs_file");
    var file = fileInput.files[0];
    var formData = new FormData();
    formData.append("file", file);
    formData.append("frs_num_bm", $("#frs_num_bm").val());
    
    $.ajax({ 
          url: "index.php?r=faturamento/readfrs",
          data: formData,
          type: "POST",
          cache: false,
            //dataType: "json",
            processData: false, // Dont process the files
            contentType: false,
          success: function(response){

            //se não tem numero de BM na FRS
            if(response=="sem_num_bm"){
              $("#frs_num_bm_div").removeAttr("hidden");
              $("#frs_content").val("Essa FRS não possui número do BM. Favor informar o número do BM no campo acima e tentar novamente clicando em Extrair Informações!");
            }
            else{
              console.log(response);
              $("#frs_content").val(response.split("##")[0]);
              $("#label_download").attr("href",response.split("##")[1]);
              $("#label_download").removeAttr("hidden");              
            }

            
          },
          error: function(){
           console.log("failure");
          }
    });
  });

  $("#nfse_btn").click(function(ev){
    file_nfse = "";
    console.log("click");
    $.ajax({ 
        url: "index.php?r=faturamento/readnfse",
        data: {file: file_nfse},
        type: "POST",
        success: function(response){
        
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
              </form>
            </div>
            <div style="margin-top: 1em">
              <table id="tabela_extrato">
                <tr>
                  <th rowspan="2">Item</th>
                  <th rowspan="2">Projeto</th>
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
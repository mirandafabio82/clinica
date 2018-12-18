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

      $form = ActiveForm::begin(); 
     $rel_geral_content = '<form action="/hcn/web/index.php?r=relatorio%2Frelatoriogeral" method="post" id="form-relgeral">
                          <div class="row">
                            <div class="col-md-2">
                              <b>Projetos</b>
                              '. 
                              Html::dropDownList('projeto', null, $listProjetos, ['multiple' => 'multiple', 'class' => 'form-control'])                           
                                .'
                            </div>
                            <div class="col-md-2">
                            <b>Contatos</b>
                              '. 
                             Html::dropDownList('contato', null, $listContatos, ['multiple' => 'multiple', 'class' => 'form-control'])                   
                                .'
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
                              <input type="checkbox" name="bm" > Com BM
                            </div>
                            <div class="col-md-1">
                              <input type="checkbox" name="frs" > Com FRS
                            </div>
                            <div class="col-md-1">
                              <input type="checkbox" name="valor" > Mostrar Valores
                            </div>
                        </row>
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
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    
    text-align: left;    
}
</style>

<img src="resources/dist/img/logo_hcn.png" alt="User Image" style="width: 8em">
<img src="logos/<?= Yii::$app->db->createCommand('SELECT cliente.logo FROM cliente JOIN projeto ON cliente.id=projeto.cliente_id WHERE projeto.cliente_id='.$projeto->cliente_id)->queryScalar()?>" alt="User Image" style="float: right;width: 10em">

<div style="margin-top:-3em;font-size: 15pt;font-family: arial; font-weight: bold" line-height= "2em" align="center">AUTORIZAÇÃO </div>
<div style="margin-bottom:1em;font-size: 15pt;font-family: arial; font-weight: bold" line-height= "2em" align="center">DE SERVIÇO (AS) </div>


<table border="1" align="center" width="100%">
<tbody>
      <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt" align="center">Número da AS</td> 
            <td style="font-family: arial;font-size: 8pt" align="center">Nº Projetista</td> 
            <td style="font-family: arial;font-size: 8pt" align="center">PJ</td> 
            <td style="font-family: arial;font-size: 8pt" align="center">CC</td> 
            <td style="font-family: arial;font-size: 8pt" align="center">DATA</td>       
            <td style="font-family: arial;font-size: 8pt" align="center">ASS</td>
            <td style="font-family: arial;font-size: 8pt" align="center">REVISÃO</td>    
      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" align="center"><?=$projeto->proposta ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center"></td>
            <td style="font-family: arial;font-size: 8pt" align="center"><?=$projeto->nome ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center"></td>
            <td style="font-family: arial;font-size: 8pt" align="center"><?=date_format(DateTime::createFromFormat('Y-m-d', $projeto->data_proposta), 'd/m/Y'); ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center">HCN</td>
            <td style="font-family: arial;font-size: 8pt" align="center"><?=$projeto->rev_proposta ?></td>            
      </tr>
       <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="7">Projeto</td>                        
      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="7"><?=$projeto->descricao ?></td>                        
      </tr>
      <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="3">ÓRGÃO SOLICITANTE</td> 
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2">CONTRATO Nº</td>                       
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2">UN</td>
      </tr>
      <tr >
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="3"><?=$projeto->site ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2">MUDAR</td>                       
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2"><?=$projeto->site?></td>
      </tr>
      </tbody>
      </table>

      <table border="1" align="center" width="100%" style="margin-top: 0.2em">
	  <tbody>
      <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="7">DESCRIÇÃO RESUMIDA DOS SERVIÇOS</td>                        
      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="7">MUDAR</td>                        
      </tr>
      </tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.2em">
	  <tbody>
      <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2">MODALIDADE</td> 
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2">SERVIÇO</td>                       
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="3">VALOR TOTAL</td>
      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2"><input type="checkbox"  >ADMINISTRAÇÃO <input type="checkbox"  checked="checked">PREÇO GLOBAL</td> 
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2"><input type="checkbox"  <?=$basico ?>>BÁSICO <input type="checkbox"  <?=$detalhamento ?>>DETALHAMENTO  <input type="checkbox"  <?=$config ?>>CONFIGURAÇÃO</td>                       
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="3"><?=$projeto->valor_proposta ?> (Valor por extenso)</td>
      </tr>
      </tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.2em">
	  <tbody>
      <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="13">DETALHAMENTO DOS CUSTOS</td>                        
      </tr>
      <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt; width: 20em" >Categoria Profissional</td>  
            <td style="font-family: arial;font-size: 8pt" align="center" >EE</td>
            <td style="font-family: arial;font-size: 8pt" align="center" >ES</td>
            <td style="font-family: arial;font-size: 8pt" align="center" >EP</td>
            <td style="font-family: arial;font-size: 8pt" align="center" >EJ</td> 
            <td style="font-family: arial;font-size: 8pt" align="center" >TP</td>    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>         
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2">TOTAL</td>          
      </tr>
      <?php 
      $p_horas_ee=0; $p_horas_es=0; $p_horas_ep=0; $p_horas_ej=0; $p_horas_tp=0; 
      foreach ($processo as $key => $proc) {
      		$p_horas_ee += $proc['horas_ee'];
      		$p_horas_es += $proc['horas_es'];
      		$p_horas_ep += $proc['horas_ep'];
      		$p_horas_ej += $proc['horas_ej'];
      		$p_horas_tp += $proc['horas_tp'];
      }

      $i_horas_ee=0; $i_horas_es=0; $i_horas_ep=0; $i_horas_ej=0; $i_horas_tp=0; 
      foreach ($instrumentacao as $key => $inst) {
      		$i_horas_ee += $inst['horas_ee'];
      		$i_horas_es += $inst['horas_es'];
      		$i_horas_ep += $inst['horas_ep'];
      		$i_horas_ej += $inst['horas_ej'];
      		$i_horas_tp += $inst['horas_tp'];
      }

      $a_horas_ee=0; $a_horas_es=0; $a_horas_ep=0; $a_horas_ej=0; $a_horas_tp=0; 
      foreach ($automacao as $key => $aut) {
      		$a_horas_ee += $aut['horas_ee'];
      		$a_horas_es += $aut['horas_es'];
      		$a_horas_ep += $aut['horas_ep'];
      		$a_horas_ej += $aut['horas_ej'];
      		$a_horas_tp += $aut['horas_tp'];
      }

      $p_tot = $p_horas_ee+$p_horas_es+$p_horas_ep+$p_horas_ej+$p_horas_tp;
      $i_tot = $i_horas_ee+$i_horas_es+$i_horas_ep+$i_horas_ej+$i_horas_tp;
      $a_tot = $a_horas_ee+$a_horas_es+$a_horas_ep+$a_horas_ej+$a_horas_tp;

      $money_proc = $p_horas_ee*$tipo_executante[4]['valor_hora']+$p_horas_es*$tipo_executante[3]['valor_hora']+$p_horas_ep*$tipo_executante[2]['valor_hora']+$p_horas_ej*$tipo_executante[1]['valor_hora']+$p_horas_tp*$tipo_executante[0]['valor_hora'];

      $money_inst = $i_horas_ee*$tipo_executante[4]['valor_hora']+$i_horas_es*$tipo_executante[3]['valor_hora']+$i_horas_ep*$tipo_executante[2]['valor_hora']+$i_horas_ej*$tipo_executante[1]['valor_hora']+$i_horas_tp*$tipo_executante[0]['valor_hora'];

      $money_aut = $a_horas_ee*$tipo_executante[4]['valor_hora']+$a_horas_es*$tipo_executante[3]['valor_hora']+$a_horas_ep*$tipo_executante[2]['valor_hora']+$a_horas_ej*$tipo_executante[1]['valor_hora']+$a_horas_tp*$tipo_executante[0]['valor_hora'];
      ?>

      <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt" >PU Médios (R$)</td>  
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$tipo_executante[4]['valor_hora'] ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$tipo_executante[3]['valor_hora'] ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$tipo_executante[2]['valor_hora'] ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$tipo_executante[1]['valor_hora'] ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$tipo_executante[0]['valor_hora'] ?></td>    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>                  
            <td style="font-family: arial;font-size: 8pt" align="center" >H/h</td> 
            <td style="font-family: arial;font-size: 8pt" align="center" >R$</td>
      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" >PROCESSO</td>  
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_ee ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_es ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_ep ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_ej ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_tp ?></td>    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>                 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_tot?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$money_proc ?></td> 
      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" >TUBULAÇÃO</td>  
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>    
            <td style="font-family: arial;font-size: 8pt" align="center" ></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>   
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>               
      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" >CIVIL/METÁLICA</td>  
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>    
            <td style="font-family: arial;font-size: 8pt" align="center" ></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>                     
      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" >ELÉTRICA</td>  
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>    
            <td style="font-family: arial;font-size: 8pt" align="center" ></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ></td> 
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>             
      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" >INSTRUMENTAÇÃO</td>  
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$i_horas_ee ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$i_horas_es ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$i_horas_ep ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$i_horas_ej ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$i_horas_tp ?></td>    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>                  
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$i_tot?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$money_inst ?></td>
      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" >AUTOMAÇÃO</td>  
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$a_horas_ee ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$a_horas_es ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$a_horas_ep ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$a_horas_ej ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$a_horas_tp ?></td>    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>                 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$a_tot?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$money_aut ?></td> 
      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" >CALDEIRARIA</td>  
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>    
            <td style="font-family: arial;font-size: 8pt" align="center" ></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>              
      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" >ROTATIVOS</td>  
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>    
            <td style="font-family: arial;font-size: 8pt" align="center" ></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>              
      </tr>
      <tr style="background-color: #d3d3d3;"> 
            <td style="font-family: arial;font-size: 8pt" >TOTAL DISCIPLINAS</td>  
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_ee+$i_horas_ee+$a_horas_ee?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_es+$i_horas_es+$a_horas_es?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_ep+$i_horas_ep+$a_horas_ep?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_ej+$i_horas_ej+$a_horas_ej?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_tp+$i_horas_tp+$a_horas_tp?></td>    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>              
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_tot+$i_tot+$a_tot ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$money_proc+$money_inst+$money_aut ?></td>
      </tr>
      <tr> 
            <td style="font-family: arial;font-size: 8pt" colspan="11">SUB-CONTRATAÇÃO</td>              
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>      
            
      </tr>
      <tr style="background-color: #d3d3d3;"> 
            <td style="font-family: arial;font-size: 8pt" colspan="12">SUBTOTAL</td>              
              
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="1"><?=$money_proc+$money_inst+$money_aut ?></td>    
      </tr>
      
	</tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.2em">
	  <tbody>
	<tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="10" >CUSTOS DIVERSOS</td>            
     </tr>
     <tr style="background-color: #d3d3d3; width:10px" >
            <td style="font-family: arial;font-size: 8pt"  >Viagens</td> 
            <td style="font-family: arial;font-size: 8pt"  colspan="8"></td>
            <td style="font-family: arial;font-size: 8pt"  align="center">-</td>           
     </tr>
     <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt"   rowspan="2">Translados <br>(140 Km por viagem)</td> 
            <td style="font-family: arial;font-size: 8pt"  >Coordenação</td>
            <td style="font-family: arial;font-size: 8pt; width:10px" rowspan="2">Qnt.</td>    
            <td style="font-family: arial;font-size: 8pt; width:20px" align="center">2</td>       
            <td style="font-family: arial;font-size: 8pt"  >Instrumentação</td>
            <td style="font-family: arial;font-size: 8pt; width:10px" rowspan="2">Qnt.</td> 
            <td style="font-family: arial;font-size: 8pt; width:20px" align="center">2</td>
            <td style="font-family: arial;font-size: 8pt; width: 10px" rowspan="2">Quantidade<br> de viagens:</td>
            <td style="font-family: arial;font-size: 8pt"  rowspan="2" align="center">2</td>  
            <td style="font-family: arial;font-size: 8pt"  rowspan="2" align="center">2</td>
     </tr>
     <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt"  >Processo</td> 
            <td style="font-family: arial;font-size: 8pt"  align="center">2</td>
            <td style="font-family: arial;font-size: 8pt"  >Automação</td>    
            <td style="font-family: arial;font-size: 8pt"  align="center">1</td> 
                   
     </tr>
     <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt"  >Softwares Especiais</td> 
            <td style="font-family: arial;font-size: 8pt"  colspan="8"></td>
            <td style="font-family: arial;font-size: 8pt"  align="center">-</td>           
     </tr>
     <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt"  >Aut. Sub-Contratações</td> 
            <td style="font-family: arial;font-size: 8pt"  colspan="8"></td>
            <td style="font-family: arial;font-size: 8pt"  align="center">-</td>           
     </tr>
	</tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.2em">
	  <tbody>
	<tbody>
	<tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt" colspan="5">DOCUMENTOS QUE SOLICITARAM A MUDANÇA DE OBJETO:</td>            
     </tr>
     <tr>
            <td style="font-family: arial;font-size: 8pt" align="center"><input type="checkbox"> E-MAIL</td> 
            <td style="font-family: arial;font-size: 8pt" align="center"><input type="checkbox"> DR-____</td>
            <td style="font-family: arial;font-size: 8pt" align="center"><input type="checkbox"> NR-____</td> 
            <td style="font-family: arial;font-size: 8pt" align="center"><input type="checkbox"> CT-____</td>        
            <td style="font-family: arial;font-size: 8pt" align="center"><input type="checkbox"> OUTROS</td>  
     </tr>
	</tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.2em">
	  <tbody>
	<tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt" align="center"> Orçamento do Empreendimento (Devido esta M.O.)</td>
            <td style="font-family: arial;font-size: 8pt" align="center"> Alterações no Cronograma</td>            
     </tr>
     <tr>
            <td style="font-family: arial;font-size: 8pt" ><input type="checkbox"> Sujeito a Acréscimo</td> 
            <td style="font-family: arial;font-size: 8pt" ><input type="checkbox"> Prazos não sofrem Alterações</td>            
     </tr>
     <tr>
            <td style="font-family: arial;font-size: 8pt" ><input type="checkbox"> Sujeito a Reduções</td> 
            <td style="font-family: arial;font-size: 8pt" ><input type="checkbox"> Prazos Parciais Alterados - Prazo Final Mantido</td>            
     </tr>
     <tr>
            <td style="font-family: arial;font-size: 8pt" ><input type="checkbox"> Esta M.O. não interfere no Budget</td> 
            <td style="font-family: arial;font-size: 8pt" ><input type="checkbox"> Prazos Final Alterado</td>            
     </tr>
	</tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.2em">
	  <tbody>
	<tr>
            <td style="font-family: arial;font-size: 8pt" colspan="2">ANEXOS</td>            
     </tr>
	<tr>
            <td style="font-family: arial;font-size: 8pt; width: 50%" ><input type="checkbox" checked="checked" > LDP</td>
            <td style="font-family: arial;font-size: 8pt" ><input type="checkbox" checked="checked"> Hh POR ATIVIDADE</td>            
     </tr>
     <tr>
            <td style="font-family: arial;font-size: 8pt" ><input type="checkbox"> CRONOGRAMA</td> 
            <td style="font-family: arial;font-size: 8pt" ><input type="checkbox"> ASC (AUTORIZAÇÃO DE SUBCONTRATAÇÃO)</td>            
     </tr>
     <tr>
            <td style="font-family: arial;font-size: 8pt" ><input type="checkbox"> PLANO DE PROJETO</td> 
            <td style="font-family: arial;font-size: 8pt" ><input type="checkbox"> OUTRO</td>            
     </tr>
     <tr>
            <td style="font-family: arial;font-size: 8pt" ><input type="checkbox"> GSS Nº </td> 
            <td style="font-family: arial;font-size: 8pt" ></td>            
     </tr>
	</tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.2em">
	  <tbody>
	<tr>
            <td style="font-family: arial;font-size: 8pt" colspan="2">DIAGRAMA DE REDE</td>            
     </tr>
	<tr>
            <td style="font-family: arial;font-size: 8pt; width: 50%" >PROCESSO: </td>
            <td style="font-family: arial;font-size: 8pt" >INSTRUMENTAÇÃO: </td>            
     </tr>
     <tr>
            <td style="font-family: arial;font-size: 8pt" >TUBULAÇÃO: </td> 
            <td style="font-family: arial;font-size: 8pt" >AUTOMAÇÃO: </td>            
     </tr>
     <tr>
            <td style="font-family: arial;font-size: 8pt" >CIVIL / METÁLICA: </td> 
            <td style="font-family: arial;font-size: 8pt" >CALDEIRARIA: </td>            
     </tr>
     <tr>
            <td style="font-family: arial;font-size: 8pt" >ELÉTRICA: </td> 
            <td style="font-family: arial;font-size: 8pt" >ROTATIVOS: </td>            
     </tr>
	</tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.2em">
	  <tbody>
	<tr>
            <td style="font-family: arial;font-size: 8pt" >NOTAS GERAIS</td>            
     </tr>
     <tr>
            <td style="font-family: arial;font-size: 8pt" ><?= nl2br($projeto->nota_geral)?></td>            
     </tr>
	</tbody>
      </table>
      
      <table  align="center" width="100%" style="margin-top: 0.2em">
	  <tbody>
	<tr style="border:1mm solid #ffffff;">
        <td style="font-family: arial;font-size: 8pt;height: 5em" align="center">ASSINATURA</td>     
        <td style="font-family: arial;font-size: 8pt;border:1mm solid #ffffff;" align="center">DATA</td> 
        <td style="font-family: arial;font-size: 8pt;border:1mm solid #ffffff;" align="center">ASSINATURA</td>     
        <td style="font-family: arial;font-size: 8pt;border:1mm solid #ffffff;" align="center">DATA</td>        
     </tr>
     <tr style="border:1mm solid #ffffff;">
            <td style="font-family: arial;font-size: 8pt;border:1mm solid #ffffff;" colspan="2" align="center">____________________________________</td>    
            <td style="font-family: arial;font-size: 8pt;border:1mm solid #ffffff;" colspan="2" align="center">____________________________________</td>        
     </tr>
     <tr style="border:1mm solid #ffffff;">
            <td style="font-family: arial;font-size: 8pt;border:1mm solid #ffffff;" colspan="2" align="center">Coordenação Projetista</td> 
            <td style="font-family: arial;font-size: 8pt;border:1mm solid #ffffff;" colspan="2" align="center">Coordenador do Projeto-<?=Yii::$app->db->createCommand('SELECT cliente.nome FROM cliente JOIN projeto ON cliente.id=projeto.cliente_id WHERE projeto.cliente_id='.$projeto->cliente_id)->queryScalar() ?></td>            
     </tr>
</tbody>
</table>
	
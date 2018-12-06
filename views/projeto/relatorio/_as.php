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

      $vl_total_conceitual = Yii::$app->db->createCommand('SELECT SUM(horas_ee) AS horas_ee, SUM(horas_es) AS horas_es, SUM(horas_ep) AS horas_ep, SUM(horas_ej)AS horas_ej, SUM(horas_tp) AS horas_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$projeto->id.' AND escopopadrao_id=1')->queryOne();

      $vl_total_basico = Yii::$app->db->createCommand('SELECT SUM(horas_ee) AS horas_ee, SUM(horas_es) AS horas_es, SUM(horas_ep) AS horas_ep, SUM(horas_ej)AS horas_ej, SUM(horas_tp) AS horas_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$projeto->id.' AND escopopadrao_id=2')->queryOne();

      $vl_total_detalhamento = Yii::$app->db->createCommand('SELECT SUM(horas_ee) AS horas_ee, SUM(horas_es) AS horas_es, SUM(horas_ep) AS horas_ep, SUM(horas_ej)AS horas_ej, SUM(horas_tp) AS horas_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$projeto->id.' AND escopopadrao_id=3')->queryOne();

      $vl_total_configuracao = Yii::$app->db->createCommand('SELECT SUM(horas_ee) AS horas_ee, SUM(horas_es) AS horas_es, SUM(horas_ep) AS horas_ep, SUM(horas_ej)AS horas_ej, SUM(horas_tp) AS horas_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$projeto->id.' AND escopopadrao_id=4')->queryOne();

      $vl_total_servico = Yii::$app->db->createCommand('SELECT SUM(horas_ee) AS horas_ee, SUM(horas_es) AS horas_es, SUM(horas_ep) AS horas_ep, SUM(horas_ej)AS horas_ej, SUM(horas_tp) AS horas_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$projeto->id.' AND escopopadrao_id=5')->queryOne();

     

      ?>

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
            <td style="font-family: arial;font-size: 8pt" align="center"><?=preg_replace('/[^0-9]/', '', $projeto->nome) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center"></td>
            <td style="font-family: arial;font-size: 8pt" align="center"><?=isset($projeto->data_proposta) ? date_format(DateTime::createFromFormat('Y-m-d', $projeto->data_proposta), 'd/m/Y') : ''; ?></td>
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
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="1">ÓRGÃO </td> 
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2">SOLICITANTE </td> 
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2">CONTRATO Nº</td>                       
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2">UN</td>
      </tr>
      <tr >
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="1"><?=$projeto->setor ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2"><?=Yii::$app->db->createCommand('SELECT nome FROM user JOIN contato ON contato.usuario_id=user.id WHERE user.id='.$projeto->contato_id)->queryScalar() ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2"><?=$projeto->contrato ?></td>                       
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
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="7"><?=$projeto->desc_resumida ?></td>                        
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
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="2"><input type="checkbox"  <?=$basico ?>>BÁSICO <input type="checkbox"  <?=$detalhamento ?>>DETALHAMENTO  <input type="checkbox"  <?=$config ?>>CONFIGURAÇÃO <input type="checkbox"  <?=$servico ?>>SERVIÇO</td>                       
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="3"><?=number_format($money_proc+$money_inst+$money_aut+$projeto->vl_km, 2, ',', '.') ?> (<?= escreverValorMoeda(number_format($money_proc+$money_inst+$money_aut+$projeto->vl_km, 2, ',', '.'))?>)</td>
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
      

      <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt" >PU Médios (R$)</td>  
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=str_replace('.',',',sprintf("%.2f",$tipo_executante[4]['valor_hora'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=str_replace('.',',',sprintf("%.2f",$tipo_executante[3]['valor_hora'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=str_replace('.',',',sprintf("%.2f",$tipo_executante[2]['valor_hora'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=str_replace('.',',',sprintf("%.2f",$tipo_executante[1]['valor_hora'])) ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=str_replace('.',',',sprintf("%.2f",$tipo_executante[0]['valor_hora'])) ?></td>    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>                  
            <td style="font-family: arial;font-size: 8pt" align="center" >H/h</td> 
            <td style="font-family: arial;font-size: 8pt" align="center" >R$</td>
      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" >CONCEITUAL</td>              
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_basico['horas_ee']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_basico['horas_ee'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_basico['horas_es']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_basico['horas_es'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_basico['horas_ep']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_basico['horas_ep'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_basico['horas_ej']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_basico['horas_ej'])) ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_basico['horas_tp']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_basico['horas_tp'])) ?></td>    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>                 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_basico['horas_ee']+$vl_total_basico['horas_es']+$vl_total_basico['horas_ep']+$vl_total_basico['horas_ej']+$vl_total_basico['horas_tp']==0 ? '':str_replace('.',',',sprintf("%.1f",$vl_total_basico['horas_ee']+$vl_total_basico['horas_es']+$vl_total_basico['horas_ep']+$vl_total_basico['horas_ej']+$vl_total_basico['horas_tp'])) ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_basico['horas_ee']*$tipo_executante[4]['valor_hora']+$vl_total_basico['horas_es']*$tipo_executante[3]['valor_hora']+$vl_total_basico['horas_ep']*$tipo_executante[2]['valor_hora']+$vl_total_basico['horas_ej']*$tipo_executante[1]['valor_hora']+$vl_total_basico['horas_tp']*$tipo_executante[0]['valor_hora']==0 ? '':number_format($vl_total_basico['horas_ee']*$tipo_executante[4]['valor_hora']+$vl_total_basico['horas_es']*$tipo_executante[3]['valor_hora']+$vl_total_basico['horas_ep']*$tipo_executante[2]['valor_hora']+$vl_total_basico['horas_ej']*$tipo_executante[1]['valor_hora']+$vl_total_basico['horas_tp']*$tipo_executante[0]['valor_hora'], 2, ',', '.') ?></td> 

      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" >BÁSICO</td>              
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_basico['horas_ee']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_basico['horas_ee'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_basico['horas_es']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_basico['horas_es'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_basico['horas_ep']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_basico['horas_ep'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_basico['horas_ej']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_basico['horas_ej'])) ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_basico['horas_tp']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_basico['horas_tp'])) ?></td>    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>                 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_basico['horas_ee']+$vl_total_basico['horas_es']+$vl_total_basico['horas_ep']+$vl_total_basico['horas_ej']+$vl_total_basico['horas_tp']==0 ? '':str_replace('.',',',sprintf("%.1f",$vl_total_basico['horas_ee']+$vl_total_basico['horas_es']+$vl_total_basico['horas_ep']+$vl_total_basico['horas_ej']+$vl_total_basico['horas_tp'])) ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_basico['horas_ee']*$tipo_executante[4]['valor_hora']+$vl_total_basico['horas_es']*$tipo_executante[3]['valor_hora']+$vl_total_basico['horas_ep']*$tipo_executante[2]['valor_hora']+$vl_total_basico['horas_ej']*$tipo_executante[1]['valor_hora']+$vl_total_basico['horas_tp']*$tipo_executante[0]['valor_hora']==0 ? '':number_format($vl_total_basico['horas_ee']*$tipo_executante[4]['valor_hora']+$vl_total_basico['horas_es']*$tipo_executante[3]['valor_hora']+$vl_total_basico['horas_ep']*$tipo_executante[2]['valor_hora']+$vl_total_basico['horas_ej']*$tipo_executante[1]['valor_hora']+$vl_total_basico['horas_tp']*$tipo_executante[0]['valor_hora'], 2, ',', '.') ?></td> 

      </tr>

      <tr>
            <td style="font-family: arial;font-size: 8pt" >DETALHAMENTO</td>              
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_detalhamento['horas_ee']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_detalhamento['horas_ee'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_detalhamento['horas_es']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_detalhamento['horas_es'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_detalhamento['horas_ep']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_detalhamento['horas_ep'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_detalhamento['horas_ej']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_detalhamento['horas_ej'])) ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_detalhamento['horas_tp']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_detalhamento['horas_tp'])) ?></td>    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>                 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_detalhamento['horas_ee']+$vl_total_detalhamento['horas_es']+$vl_total_detalhamento['horas_ep']+$vl_total_detalhamento['horas_ej']+$vl_total_detalhamento['horas_tp']==0 ? '':str_replace('.',',',sprintf("%.1f",$vl_total_detalhamento['horas_ee']+$vl_total_detalhamento['horas_es']+$vl_total_detalhamento['horas_ep']+$vl_total_detalhamento['horas_ej']+$vl_total_detalhamento['horas_tp'])) ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_detalhamento['horas_ee']*$tipo_executante[4]['valor_hora']+$vl_total_detalhamento['horas_es']*$tipo_executante[3]['valor_hora']+$vl_total_detalhamento['horas_ep']*$tipo_executante[2]['valor_hora']+$vl_total_detalhamento['horas_ej']*$tipo_executante[1]['valor_hora']+$vl_total_detalhamento['horas_tp']*$tipo_executante[0]['valor_hora']==0 ? '':number_format($vl_total_detalhamento['horas_ee']*$tipo_executante[4]['valor_hora']+$vl_total_detalhamento['horas_es']*$tipo_executante[3]['valor_hora']+$vl_total_detalhamento['horas_ep']*$tipo_executante[2]['valor_hora']+$vl_total_detalhamento['horas_ej']*$tipo_executante[1]['valor_hora']+$vl_total_detalhamento['horas_tp']*$tipo_executante[0]['valor_hora'], 2, ',', '.') ?></td> 

      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" >CONFIGURAÇÃO</td>              
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_configuracao['horas_ee']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_configuracao['horas_ee'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_configuracao['horas_es']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_configuracao['horas_es'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_configuracao['horas_ep']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_configuracao['horas_ep'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_configuracao['horas_ej']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_configuracao['horas_ej'])) ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_configuracao['horas_tp']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_configuracao['horas_tp'])) ?></td>    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>                 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_configuracao['horas_ee']+$vl_total_configuracao['horas_es']+$vl_total_configuracao['horas_ep']+$vl_total_configuracao['horas_ej']+$vl_total_configuracao['horas_tp']==0 ? '':str_replace('.',',',sprintf("%.1f",$vl_total_configuracao['horas_ee']+$vl_total_configuracao['horas_es']+$vl_total_configuracao['horas_ep']+$vl_total_configuracao['horas_ej']+$vl_total_configuracao['horas_tp'])) ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_configuracao['horas_ee']*$tipo_executante[4]['valor_hora']+$vl_total_configuracao['horas_es']*$tipo_executante[3]['valor_hora']+$vl_total_configuracao['horas_ep']*$tipo_executante[2]['valor_hora']+$vl_total_configuracao['horas_ej']*$tipo_executante[1]['valor_hora']+$vl_total_configuracao['horas_tp']*$tipo_executante[0]['valor_hora']==0 ? '':number_format($vl_total_configuracao['horas_ee']*$tipo_executante[4]['valor_hora']+$vl_total_configuracao['horas_es']*$tipo_executante[3]['valor_hora']+$vl_total_configuracao['horas_ep']*$tipo_executante[2]['valor_hora']+$vl_total_configuracao['horas_ej']*$tipo_executante[1]['valor_hora']+$vl_total_configuracao['horas_tp']*$tipo_executante[0]['valor_hora'], 2, ',', '.') ?></td> 
      </tr>
      <tr>
            <td style="font-family: arial;font-size: 8pt" >SERVIÇO</td>              
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_servico['horas_ee']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_servico['horas_ee'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_servico['horas_es']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_servico['horas_es'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_servico['horas_ep']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_servico['horas_ep'])) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_servico['horas_ej']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_servico['horas_ej'])) ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_servico['horas_tp']==0 ? '': str_replace('.',',',sprintf("%.1f",$vl_total_servico['horas_tp'])) ?></td>    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>                 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_servico['horas_ee']+$vl_total_servico['horas_es']+$vl_total_servico['horas_ep']+$vl_total_servico['horas_ej']+$vl_total_servico['horas_tp']==0 ? '':str_replace('.',',',sprintf("%.1f",$vl_total_servico['horas_ee']+$vl_total_servico['horas_es']+$vl_total_servico['horas_ep']+$vl_total_servico['horas_ej']+$vl_total_servico['horas_tp'])) ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$vl_total_servico['horas_ee']*$tipo_executante[4]['valor_hora']+$vl_total_servico['horas_es']*$tipo_executante[3]['valor_hora']+$vl_total_servico['horas_ep']*$tipo_executante[2]['valor_hora']+$vl_total_servico['horas_ej']*$tipo_executante[1]['valor_hora']+$vl_total_servico['horas_tp']*$tipo_executante[0]['valor_hora']==0 ? '':number_format($vl_total_servico['horas_ee']*$tipo_executante[4]['valor_hora']+$vl_total_servico['horas_es']*$tipo_executante[3]['valor_hora']+$vl_total_servico['horas_ep']*$tipo_executante[2]['valor_hora']+$vl_total_servico['horas_ej']*$tipo_executante[1]['valor_hora']+$vl_total_servico['horas_tp']*$tipo_executante[0]['valor_hora'], 2, ',', '.') ?></td> 

      </tr>

      <tr style="background-color: #d3d3d3;"> 
            <td style="font-family: arial;font-size: 8pt" >TOTAL DISCIPLINAS</td>  
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_ee+$i_horas_ee+$a_horas_ee==0 ? '':str_replace('.',',',sprintf("%.1f",$p_horas_ee+$i_horas_ee+$a_horas_ee)) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_es+$i_horas_es+$a_horas_es==0 ? '':str_replace('.',',',sprintf("%.1f",$p_horas_es+$i_horas_es+$a_horas_es)) ?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_ep+$i_horas_ep+$a_horas_ep==0 ? '': str_replace('.',',',sprintf("%.1f",$p_horas_ep+$i_horas_ep+$a_horas_ep))?></td>
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_ej+$i_horas_ej+$a_horas_ej==0 ? '':str_replace('.',',',sprintf("%.1f",$p_horas_ej+$i_horas_ej+$a_horas_ej)) ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_horas_tp+$i_horas_tp+$a_horas_tp==0 ?'':str_replace('.',',',sprintf("%.1f",$p_horas_tp+$i_horas_tp+$a_horas_tp)) ?></td>    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>              
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$p_tot+$i_tot+$a_tot==0 ? '': str_replace('.',',',sprintf("%.1f",$p_tot+$i_tot+$a_tot)) ?></td> 
            <td style="font-family: arial;font-size: 8pt" align="center" ><?=$money_proc+$money_inst+$money_aut==0 ? '':number_format($money_proc+$money_inst+$money_aut, 2, ',', '.') ?></td>
      </tr>
      <tr> 
            <td style="font-family: arial;font-size: 8pt" colspan="11">SUB-CONTRATAÇÃO</td>              
            <td style="font-family: arial;font-size: 8pt" align="center" ></td>      
            
      </tr>
      <tr style="background-color: #d3d3d3;"> 
            <td style="font-family: arial;font-size: 8pt" colspan="12">SUBTOTAL</td>              
              
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="1"><?=number_format($money_proc+$money_inst+$money_aut, 2, ',', '.')  ?></td>    
      </tr>
      
	</tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.2em">
	  <tbody>
	<tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt" align="center" colspan="3" >CUSTOS DIVERSOS</td>            
     </tr>
     <tr style="background-color: #d3d3d3" >
            <td style="font-family: arial;font-size: 8pt; width:10px">Viagens</td> 
            <td style="font-family: arial;font-size: 8pt" ></td>
            <td style="font-family: arial;font-size: 8pt" ></td>           
     </tr>
     <tr style="background-color: #d3d3d3" >
            <td style="font-family: arial;font-size: 8pt; width:10px">Passagens aéreas</td> 
            <td style="font-family: arial;font-size: 8pt" ></td>
            <td style="font-family: arial;font-size: 8pt" align="right"><?= number_format($projeto->vl_passagem_aerea, 2, ',', '.') ?></td>           
     </tr>
     <tr style="background-color: #d3d3d3" >
            <td style="font-family: arial;font-size: 8pt; width:10px">Hospedagem</td> 
            <td style="font-family: arial;font-size: 8pt" ></td>
            <td style="font-family: arial;font-size: 8pt" align="right"><?= number_format($projeto->vl_hospedagem, 2, ',', '.') ?></td>           
     </tr>
     <tr style="background-color: #d3d3d3" >
            <td style="font-family: arial;font-size: 8pt; width:10px">Taxi</td> 
            <td style="font-family: arial;font-size: 8pt" ></td>
            <td style="font-family: arial;font-size: 8pt" align="right"><?= number_format($projeto->vl_taxi, 2, ',', '.') ?></td>           
     </tr>
    <!--  <tr style="background-color: #d3d3d3;">
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
     </tr> -->
     <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt; width:20em">Translados</td> 
           <td style="font-family: arial;font-size: 8pt; width:20em" align="center">Deslocamento para <?= $projeto->qtd_km ?> Km</td> 
           <td style="font-family: arial;font-size: 8pt; width:2em" align="right"><?= number_format($projeto->vl_km, 2, ',', '.') ?></td> 
      </tr>

     <tr style="background-color: #d3d3d3;">
            <td style="font-family: arial;font-size: 8pt; width:20em"  >Softwares Especiais</td> 
            <td style="font-family: arial;font-size: 8pt; width:20em" ></td>
            <td style="font-family: arial;font-size: 8pt; width:2em"  align="center"></td>           
     </tr>
     <tr style="background-color: #605f5f;">
            <td style="font-family: arial;font-size: 8pt; width:20em;color:white"  colspan="2">SUBTOTAL</td>             
            <td style="font-family: arial;font-size: 8pt; width:2em;color:white"  align="right"><?= number_format($projeto->vl_passagem_aerea+$projeto->vl_hospedagem+$projeto->vl_taxi+$projeto->vl_km, 2, ',', '.') ?></td>           
     </tr>
      <tr style="background-color: #605f5f;">
            <td style="font-family: arial;font-size: 8pt; width:20em;color:white" colspan="2">TOTAL GERAL</td> 
            <td style="font-family: arial;font-size: 8pt; width:2em;color:white"  align="right"><?= number_format($money_proc+$money_inst+$money_aut+$projeto->vl_passagem_aerea+$projeto->vl_hospedagem+$projeto->vl_taxi+$projeto->vl_km, 2, ',', '.') ?></td>           
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
      
      <!-- <table border="1" align="center" width="100%" style="margin-top: 0.2em">
	  <tbody>
	<tr>
            <td style="font-family: arial;font-size: 8pt" >NOTAS GERAIS</td>            
     </tr>
     <tr>
            <td style="font-family: arial;font-size: 8pt" ></td>            
     </tr>
	</tbody>
      </table> -->
      
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
	

<?php
/**
 * Retorna uma string do valor 
 *  
 * @param string $n - Valor a ser traduzido, pode ser no formato americano ou brasileiro
 * @example escreverValorMoeda('1.530,64');
 * @example escreverValorMoeda('1530.64');
 * @return string 
 */
function escreverValorMoeda($n){
    //Converte para o formato float 
    if(strpos($n, ',') !== FALSE){
        $n = str_replace('.','',$n); 
        $n = str_replace(',','.',$n);
    }
 
    //Separa o valor "reais" dos "centavos"; 
    $n = explode('.',$n);
 
    return ucfirst(numeroEscrito($n[0])). ' reais' . ((isset($n[1]) && $n[1] > 0)?' e '.numeroEscrito($n[1]).' centavos.':'');
 
}

/**
 * Retorna uma string do numero
 * 
 * @param string $n - Valor a ser traduzido,  apenas numeros inteiros
 * @example numeroEscrito('500');
 * @return string 
 */
function numeroEscrito($n) {
 
    $numeros[1][0] = '';
    $numeros[1][1] = 'um';
    $numeros[1][2] = 'dois';
    $numeros[1][3] = 'três';
    $numeros[1][4] = 'quatro';
    $numeros[1][5] = 'cinco';
    $numeros[1][6] = 'seis';
    $numeros[1][7] = 'sete';
    $numeros[1][8] = 'oito';
    $numeros[1][9] = 'nove';
 
    $numeros[2][0] = '';
    $numeros[2][10] = 'dez';
    $numeros[2][11] = 'onze';
    $numeros[2][12] = 'doze';
    $numeros[2][13] = 'treze';
    $numeros[2][14] = 'quatorze';
    $numeros[2][15] = 'quinze';
    $numeros[2][16] = 'dezesseis';
    $numeros[2][17] = 'dezesete';
    $numeros[2][18] = 'dezoito';
    $numeros[2][19] = 'dezenove';
    $numeros[2][2] = 'vinte';
    $numeros[2][3] = 'trinta';
    $numeros[2][4] = 'quarenta';
    $numeros[2][5] = 'cinquenta';
    $numeros[2][6] = 'sessenta';
    $numeros[2][7] = 'setenta';
    $numeros[2][8] = 'oitenta';
    $numeros[2][9] = 'noventa';
 
    $numeros[3][0] = '';
    $numeros[3][1] = 'cem';
    $numeros[3][2] = 'duzentos';
    $numeros[3][3] = 'trezentos';
    $numeros[3][4] = 'quatrocentos';
    $numeros[3][5] = 'quinhentos';
    $numeros[3][6] = 'seiscentos';
    $numeros[3][7] = 'setecentos';
    $numeros[3][8] = 'oitocentos';
    $numeros[3][9] = 'novecentos';
 
    $qtd = strlen($n);
 
    $compl[0] = ' mil ';
    $compl[1] = ' milhão ';
    $compl[2] = ' milhões ';
    $numero = "";
    $casa = $qtd;
    $pulaum = false;
    $x = 0;
    for ($y = 0; $y < $qtd; $y++) {
 
        if ($casa == 5) {
 
            if ($n[$x] == '1') {
 
                $indice = '1' . $n[$x + 1];
                $pulaum = true;
            } else {
 
                $indice = $n[$x];
            }
 
            if ($n[$x] != '0') {
 
                if (isset($n[$x - 1])) {
 
                    $numero .= ' e ';
                }
 
                $numero .= $numeros[2][$indice];
 
                if ($pulaum) {
 
                    $numero .= ' ' . $compl[0];
                }
            }
        }
 
        if ($casa == 4) {
 
            if (!$pulaum) {
 
                if ($n[$x] != '0') {
 
                    if (isset($n[$x - 1])) {
 
                        $numero .= ' e ';
                    }
                }
            }
 
            $numero .= $numeros[1][$n[$x]] . ' ' . $compl[0];
        }
 
        if ($casa == 3) {
 
            if ($n[$x] == '1' && $n[$x + 1] != '0') {
 
                $numero .= 'cento ';
            } else {
 
                if ($n[$x] != '0') {
 
                    if (isset($n[$x - 1])) {
 
                        $numero .= ' e ';
                    }
 
                    $numero .= $numeros[3][$n[$x]];
                }
            }
        }
 
        if ($casa == 2) {
 
            if ($n[$x] == '1') {
 
                $indice = '1' . $n[$x + 1];
                $casa = 0;
            } else {
 
                $indice = $n[$x];
            }
 
            if ($n[$x] != '0') {
 
                if (isset($n[$x - 1])) {
 
                    $numero .= ' e ';
                }
 
                $numero .= $numeros[2][$indice];
            }
        }
 
        if ($casa == 1) {
 
            if ($n[$x] != '0') {
                if ($numeros[1][$n[$x]] <= 10)
                    $numero .= ' ' . $numeros[1][$n[$x]];
                else
                    $numero .= ' e ' . $numeros[1][$n[$x]];
            } else {
 
                $numero .= '';
            }
        }
 
        if ($pulaum) {
 
            $casa--;
            $x++;
            $pulaum = false;
        }
 
        $casa--;
        $x++;
    }
 
    return $numero;
}
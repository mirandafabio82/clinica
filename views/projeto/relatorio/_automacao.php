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

<div style="margin-top:-3em;font-size: 10pt;font-family: arial; font-weight: bold" line-height= "2em" align="center">ANEXO <?=$index ?> </div>
<div style="font-size: 10pt;font-family: arial; font-weight: bold;" line-height= "2em" align="center">ESTIMATIVA DE CUSTO POR ESPECIALIDADE</div>
<div style="font-size: 10pt;font-family: arial; font-weight: bold" line-height= "2em" align="center">AUTOMAÇÃO </div>

<div style="font-size: 10pt;font-family: arial; font-weight: bold; float: right;margin-top: 1em;margin-left: 41em" line-height= "2em" ><?=$projeto->nome ?> </div>
<div style="margin-bottom:1em; font-size: 10pt;font-family: arial; font-weight: bold;margin-left: 41em " line-height= "2em" >PLANTA: <?=$projeto->site ?> </div>


<table width="100%" border="1" align="center" style="page-break-inside:avoid">
	<tbody>
	<thead>
        <tr style="background-color: #d3d3d3;">
	        <th style="font-size: 8pt" align="center">ITEM</th>
	        <th style="font-size: 8pt" width="300" align="center">DESCRIÇÃO</th>
	        <th style="font-size: 8pt" align="center">QTD</th>
	        <th style="font-size: 8pt" align="center">FOR.</th>
	        <th style="font-size: 8pt" colspan="6" align="center">DISTRIBUIÇÃO POR CATEGORIA</th>
        </tr>
        <tr style="background-color: #d3d3d3">
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th style="font-size: 8pt" align="center">EE</th>
          <th style="font-size: 8pt" align="center">ES</th>
          <th style="font-size: 8pt" align="center">EP</th>
          <th style="font-size: 8pt" align="center">EJ</th>
          <th style="font-size: 8pt" align="center">TP</th>
          <th style="font-size: 8pt" align="center">Total</th>
        </tr>
        </thead>
        <?php 
        $item1 = '1.'; 
        $count = 0; $total_horas_ee=0;$total_horas_es=0;$total_horas_ep=0;$total_horas_ej=0;$total_horas_tp=0;$total=0;
        foreach ($escopos as $key => $escopo) { 
        	if(!$escopo['isEntregavel']){?>
        <?php
        	if($escopo['horas_ee']|| $escopo['horas_es']|| $escopo['horas_ep']|| $escopo['horas_ej']|| $escopo['horas_tp']){
        		$count++;
        		$item = $item1.$count; 
        	}
        	else{
        		$item ='';
        	}

        	$total_horas_ee += $escopo['horas_ee'];
        	$total_horas_es += $escopo['horas_es'];
        	$total_horas_ep += $escopo['horas_ep'];
        	$total_horas_ej += $escopo['horas_ej'];
        	$total_horas_tp += $escopo['horas_tp']; 
        	$tot = $escopo["horas_tp"]+$escopo["horas_ej"]+$escopo["horas_ep"]+$escopo["horas_es"]+$escopo["horas_ee"];
        	$total += $escopo["horas_tp"]+$escopo["horas_ej"]+$escopo["horas_ep"]+$escopo["horas_es"]+$escopo["horas_ee"];

        	if($tot==0)
        		$tot='';
         ?>
		<tr>
			<td style="font-size: 8pt" align="center"><?= $item ?></td>
			<td style="font-size: 8pt" ><?= $escopo['descricao']?></td>
			<td style="font-size: 8pt;background-color: #d3d3d3;" align="center"></td>
			<td style="font-size: 8pt;background-color: #d3d3d3;" align="center"></td>
			<td style="font-size: 8pt" align="center"><?= $escopo['horas_ee']?></td>
			<td style="font-size: 8pt" align="center"><?= $escopo['horas_es']?></td>
			<td style="font-size: 8pt" align="center"><?= $escopo['horas_ep']?></td>
			<td style="font-size: 8pt" align="center"><?= $escopo['horas_ej']?></td>
			<td style="font-size: 8pt" align="center"><?= $escopo['horas_tp']?></td>
			<td style="font-size: 8pt" align="center"><?=$tot?></td>
		</tr>
		<?php }} ?>
		<?php 
			if($total_horas_ee==0) $total_horas_ee='';
			if($total_horas_es==0) $total_horas_es='';
			if($total_horas_ep==0) $total_horas_ep='';
			if($total_horas_ej==0) $total_horas_ej='';
			if($total_horas_tp==0) $total_horas_tp='';
			if($total==0) $total='';
		?>
		<tr style="background-color: #d3d3d3;">
			<td style="font-size: 8pt" align="center"></td>
			<td style="font-size: 8pt" align="center">SUBTOTAL ATIVIDADES GERAIS DE PROJETO</td>
			<td style="font-size: 8pt" align="center"></td>
			<td style="font-size: 8pt" align="center"></td>
			<td style="font-size: 8pt" align="center"><?= $total_horas_ee; ?></td>
			<td style="font-size: 8pt" align="center"><?= $total_horas_es; ?></td>
			<td style="font-size: 8pt" align="center"><?= $total_horas_ep; ?></td>
			<td style="font-size: 8pt" align="center"><?= $total_horas_ej; ?></td>
			<td style="font-size: 8pt" align="center"><?= $total_horas_tp; ?></td>
			<td style="font-size: 8pt" align="center"><?= $total; ?></td>
		</tr>

		<?php 
		$item2 = '2.'; 
        $count = 0; $total_horas_ee_2=0;$total_horas_es_2=0;$total_horas_ep_2=0;$total_horas_ej_2=0;$total_horas_tp_2=0;$total_2=0;
		foreach ($escopos as $key => $escopo_2) { 
        	if($escopo_2['isEntregavel']){?>
        <?php
        	if($escopo_2['horas_ee']|| $escopo_2['horas_es']|| $escopo_2['horas_ep']|| $escopo_2['horas_ej']|| $escopo_2['horas_tp']){
        		$count++;
        		$item = $item2.$count; 
        	}
        	else{
        		$item ='';
        	}

        	$total_horas_ee_2 += $escopo_2['horas_ee'];
        	$total_horas_es_2 += $escopo_2['horas_es'];
        	$total_horas_ep_2 += $escopo_2['horas_ep'];
        	$total_horas_ej_2 += $escopo_2['horas_ej'];
        	$total_horas_tp_2 += $escopo_2['horas_tp']; 
        	$tot_2 = $escopo_2["horas_tp"]+$escopo_2["horas_ej"]+$escopo_2["horas_ep"]+$escopo_2["horas_es"]+$escopo_2["horas_ee"];
        	$total += $escopo_2["horas_tp"]+$escopo_2["horas_ej"]+$escopo_2["horas_ep"]+$escopo_2["horas_es"]+$escopo_2["horas_ee"];

        	if($tot_2==0)
        		$tot_2='';
         ?>
		<tr>
			<td style="font-size: 8pt" align="center"><?= $item ?></td>
			<td style="font-size: 8pt" ><?= $escopo_2['descricao']?></td>
			<td style="font-size: 8pt" align="center"></td>
			<td style="font-size: 8pt" align="center"></td>
			<td style="font-size: 8pt" align="center"><?= $escopo_2['horas_ee']?></td>
			<td style="font-size: 8pt" align="center"><?= $escopo_2['horas_es']?></td>
			<td style="font-size: 8pt" align="center"><?= $escopo_2['horas_ep']?></td>
			<td style="font-size: 8pt" align="center"><?= $escopo_2['horas_ej']?></td>
			<td style="font-size: 8pt" align="center"><?= $escopo_2['horas_tp']?></td>
			<td style="font-size: 8pt" align="center"><?=$tot_2?></td>
		</tr>
		<?php }} ?>
		<?php 
			if($total_horas_ee_2==0) $total_horas_ee_2='';
			if($total_horas_es_2==0) $total_horas_es_2='';
			if($total_horas_ep_2==0) $total_horas_ep_2='';
			if($total_horas_ej_2==0) $total_horas_ej_2='';
			if($total_horas_tp_2==0) $total_horas_tp_2='';
			if($total_2==0) $total_2='';
		?>
		<tr style="background-color: #d3d3d3;">
			<td style="font-size: 8pt" align="center"></td>
			<td style="font-size: 8pt" align="center">SUBTOTAL ATIVIDADES GERAIS DE PROJETO</td>
			<td style="font-size: 8pt" align="center"></td>
			<td style="font-size: 8pt" align="center"></td>
			<td style="font-size: 8pt" align="center"><?= $total_horas_ee_2; ?></td>
			<td style="font-size: 8pt" align="center"><?= $total_horas_es_2; ?></td>
			<td style="font-size: 8pt" align="center"><?= $total_horas_ep_2; ?></td>
			<td style="font-size: 8pt" align="center"><?= $total_horas_ej_2; ?></td>
			<td style="font-size: 8pt" align="center"><?= $total_horas_tp_2; ?></td>
			<td style="font-size: 8pt" align="center"><?= $total_2; ?></td>
		</tr>
		<?php 
			$hh_total_horas_ee = $total_horas_ee_2 + $total_horas_ee;
			$hh_total_horas_es = $total_horas_es_2 + $total_horas_es;
			$hh_total_horas_ep = $total_horas_ep_2 + $total_horas_ep;
			$hh_total_horas_ej = $total_horas_ej_2 + $total_horas_ej;
			$hh_total_horas_tp = $total_horas_tp_2 + $total_horas_tp;
			$hh_total = $total_2 + $total;

			if($hh_total_horas_ee==0) $hh_total_horas_ee='';
			if($hh_total_horas_es==0) $hh_total_horas_es='';
			if($hh_total_horas_ep==0) $hh_total_horas_ep='';
			if($hh_total_horas_ej==0) $hh_total_horas_ej='';
			if($hh_total_horas_tp==0) $hh_total_horas_tp='';
			if($hh_total==0) $hh_total='';
		?>
		<tr style="background-color: #d3d3d3;">
			<td style="font-size: 8pt" align="center"></td>
			<td style="font-size: 8pt" align="center">HH TOTAL DE ENGENHARIA</td>
			<td style="font-size: 8pt" align="center"></td>
			<td style="font-size: 8pt" align="center"></td>
			<td style="font-size: 8pt" align="center"><?= $hh_total_horas_ee; ?></td>
			<td style="font-size: 8pt" align="center"><?= $hh_total_horas_es; ?></td>
			<td style="font-size: 8pt" align="center"><?= $hh_total_horas_ep; ?></td>
			<td style="font-size: 8pt" align="center"><?= $hh_total_horas_ej; ?></td>
			<td style="font-size: 8pt" align="center"><?= $hh_total_horas_tp; ?></td>
			<td style="font-size: 8pt" align="center"><?= $hh_total; ?></td>
		</tr>

	</tbody>
</table>
<?php $item = 1; 
 
?>
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

<div style="margin-bottom:1em;margin-top:-3em;font-size: 15pt;font-family: arial; font-weight: bold" line-height= "2em" align="center">Extrato </div>

<div style="margin-bottom:1em;font-size: 15pt;font-family: arial; font-weight: bold" line-height= "2em" align="center"><?= $prestador['nome'] ?> </div>

<div style="font-size: 10pt;font-family: arial; font-weight: bold" line-height= "2em" align="center"><?= /*Yii::$app->db->createCommand('SELECT cliente.nome FROM cliente JOIN projeto ON cliente.id=projeto.cliente_id WHERE projeto.cliente_id='.$projeto->cliente_id)->queryScalar().*/'Unidade '.Yii::$app->db->createCommand('SELECT cliente.site FROM cliente JOIN projeto ON cliente.id=projeto.cliente_id WHERE projeto.cliente_id='.$projeto->cliente_id)->queryScalar() ?> </div>

<table border="1" align="center" width="100%">
<tbody>
	<tr>
	    <td style="margin-top: 4em;font-size: 10pt;font-family: arial;" line-height= "2em" >Contrato: <?= $bm['contrato'] ?> </td>
	    <td style="margin-top: 4em;font-size: 10pt;font-family: arial;" line-height= "2em" >BM Número: <?= $bm['numero_bm'].'/'.date('Y') ?> </td>
	</tr>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >Objeto: <?= $bm['objeto'] ?> </td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >Data: <?=!empty($bm['data']) ? date_format(DateTime::createFromFormat('Y-m-d', $bm['data']), 'd/m/Y') : '' ?></td>
	</tr>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >Contratada: <?=$bm['contratada'] ?> </td>
		
	</tr>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >CNPJ: <?=$bm['cnpj'] ?> </td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >Período de: <?=!empty($bm['de']) ? date_format(DateTime::createFromFormat('Y-m-d', $bm['de']), 'd/m/Y') : '' ?></td>
	</tr>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >Contato: <?=$bm['contato'] ?> </td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >a: <?=!empty($bm['para']) ? date_format(DateTime::createFromFormat('Y-m-d', $bm['para']), 'd/m/Y') : '' ?> </td>
    </tr>

</tbody>
</table>

<table border="1" align="center" width="100%" style="margin-top: 1em">
	<tr>
		<td style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 10pt;font-family: arial;font-weight: bold"  >Descrição do Serviço </td>
    </tr>	
    <tr>
		<td style="margin-top: 0.5em;font-size:8pt;font-family: arial;height: 5em"  valign="top"><?=nl2br($bm['descricao']) ?> </td>
    </tr>
</table>

<table border="1" align="center" width="100%" style="margin-top: 1em">
	<tr>
		<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;height: 4em"><input type="checkbox" checked="checked">Nas unidades do(a) <?= Yii::$app->db->createCommand('SELECT cliente.nome FROM cliente JOIN projeto ON cliente.id=projeto.cliente_id WHERE projeto.cliente_id='.$projeto->cliente_id)->queryScalar() ?>
  		</td>
  		<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;height: 4em">NF <?= Yii::$app->db->createCommand('SELECT cliente.nome FROM cliente JOIN projeto ON cliente.id=projeto.cliente_id WHERE projeto.cliente_id='.$projeto->cliente_id)->queryScalar() ?> Nº
  		</td>
    </tr>	
    <tr>
		<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;height: 4em"><input type="checkbox" >Fora das unidades do(a) <?= Yii::$app->db->createCommand('SELECT cliente.nome FROM cliente JOIN projeto ON cliente.id=projeto.cliente_id WHERE projeto.cliente_id='.$projeto->cliente_id)->queryScalar() ?>
  		</td>
  		<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;height: 4em">NF Devolução Nº
  		</td>
    </tr>
</table>

<table border="1" align="center" width="100%" style="margin-top: 1em">
	<tr>
		<td style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;font-weight: bold"  >Item </td>
		<td style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 10pt;font-family: arial;font-weight: bold"  >Descrição</td>
		<td style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;font-weight: bold"  >Un</td>
		<td style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;font-weight: bold"  >Quantidade</td>
		<td style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;font-weight: bold"  >Valor Unitário</td>
		<td style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;font-weight: bold"  >Total</td>
	</tr>
	<?php if(!empty($horas_exe_ee) && $horas_exe_ee!=0){ ?>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >0<?= $item ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Mão de Obra (EE-AUT)</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;"  >Hh</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= str_replace('.',',',sprintf("%.1f",$horas_exe_ee)) ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= str_replace('.',',',sprintf("%.2f",$prestador['vl_hh_ee'])) ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= number_format($bm['executado_ee'] * $prestador['vl_hh_ee'], 2, ',', '.') ?></td>
	</tr>	
	<?php $item++; } ?>
	<?php if(!empty($horas_exe_es) && $horas_exe_es!=0){ ?>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;"  >0<?= $item ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Mão de Obra (ES-AUT)</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;"  >Hh</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= str_replace('.',',',sprintf("%.1f",$horas_exe_es)) ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?=str_replace('.',',',sprintf("%.2f",$prestador['vl_hh_es'])) ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= number_format($bm['executado_es'] * $prestador['vl_hh_es'], 2, ',', '.') ?></td>
	</tr>	
	<?php $item++; } ?>	
	<?php if(!empty($horas_exe_ep) && $horas_exe_ep!=0){ ?>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;"  >0<?= $item ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Mão de Obra (EP-AUT)</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;"  >Hh</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= str_replace('.',',',sprintf("%.1f",$horas_exe_ep)) ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= str_replace('.',',',sprintf("%.2f",$prestador['vl_hh_ep'])) ?></td>
		<td style="margin-top: 1.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= number_format($bm['executado_ep'] * $prestador['vl_hh_ep'], 2, ',', '.') ?></td>
	</tr>	
	<?php $item++; } ?>
	<?php if(!empty($horas_exe_ej) && $horas_exe_ej!=0){ ?>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;"  >0<?= $item ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Mão de Obra (EJ-AUT)</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;"  >Hh</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= str_replace('.',',',sprintf("%.1f",$horas_exe_ej)) ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= str_replace('.',',',sprintf("%.2f",$prestador['vl_hh_ej'])) ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= number_format($bm['executado_ej'] * $prestador['vl_hh_ej'], 2, ',', '.') ?></td>
	</tr>	
	<?php $item++; } ?>
	<?php if(!empty($horas_exe_tp) && $horas_exe_tp!=0){ ?>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;"  >0<?= $item ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Mão de Obra (TP-AUT)</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;"  >Hh</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= str_replace('.',',',sprintf("%.1f",$horas_exe_tp)) ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= str_replace('.',',',sprintf("%.2f",$prestador['vl_hh_tp'])) ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= number_format($bm['executado_tp'] * $prestador['vl_hh_tp'], 2, ',', '.') ?></td>
	</tr>	
	<?php $item++; } ?>
	
	<!-- <tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;"  >0<?= $item ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Deslocamento para <//?= $bm['qtd_dias'] ?> dias</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;"  >Km</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><//?= number_format($bm['km'], 1, ',', '.') ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><//?= number_format(Yii::$app->db->createCommand('SELECT vl_km FROM executante WHERE usuario_id=61')->queryScalar(), 2, ',', '.') ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><//?= number_format($bm['km'] * Yii::$app->db->createCommand('SELECT vl_km FROM executante WHERE usuario_id=61')->queryScalar(), 2, ',', '.') ?></td>
	</tr> -->
	<?php $item++; ?>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: center;"  >0<?= $item ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Total</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;text-align: right;"  ><?= number_format($horas_exe_ee * $prestador['vl_hh_ee']+
																			$horas_exe_es * $prestador['vl_hh_es'] +
																			$horas_exe_ep * $prestador['vl_hh_ep']+
																			$horas_exe_ej * $prestador['vl_hh_ej']+
																			$horas_exe_tp * $prestador['vl_hh_tp']/*+
																			$bm['km'] * Yii::$app->db->createCommand('SELECT vl_km FROM executante WHERE usuario_id=61')->queryScalar()*/, 2, ',', '.')?>
																				
		</td>
	</tr>
</table>
<table  align="center" width="100%" style="margin-top: 3em">
	  <tbody>
	<tr style="border:1mm solid #ffffff;">
        <td style="font-family: arial;font-size: 8pt;height: 5em" align="center">ASSINATURA</td>     
        <td style="font-family: arial;font-size: 8pt;border:1mm solid #ffffff;" align="center">DATA</td> 
        <td style="font-family: arial;font-size: 8pt;border:1mm solid #ffffff;" align="center">ASSINATURA</td>     
        <td style="font-family: arial;font-size: 8pt;border:1mm solid #ffffff;" align="center">DATA</td>        
     </tr>
     <tr style="border:1mm solid #ffffff;">
            <td style="font-family: arial;font-size: 8pt;border:1mm solid #ffffff;" colspan="2" align="center"><img src="resources/dist/img/assinatura.png" alt="User Image" style="width: 8em"><br>____________________________________</td>    
            <td style="font-family: arial;font-size: 8pt;border:1mm solid #ffffff;" colspan="2" align="center"><br><br><br><br>____________________________________</td>        
     </tr>
     <tr style="border:1mm solid #ffffff;">
            <td style="font-family: arial;font-size: 8pt;border:1mm solid #ffffff;" colspan="2" align="center">Assinatura da Contratada</td> 
            <td style="font-family: arial;font-size: 8pt;border:1mm solid #ffffff;" colspan="2" align="center"><?=Yii::$app->db->createCommand('SELECT cliente.nome FROM cliente JOIN projeto ON cliente.id=projeto.cliente_id WHERE projeto.cliente_id='.$projeto->cliente_id)->queryScalar() ?></td>            
     </tr>
</tbody>
</table>
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

<div style="margin-top:-3em;font-size: 15pt;font-family: arial; font-weight: bold" line-height= "2em" align="center">Demonstrativo </div>
<div style="margin-top:-0.5em;margin-bottom:1em;font-size: 15pt;font-family: arial; font-weight: bold" line-height= "2em" align="center">de Medição </div>


<div style="font-size: 10pt;font-family: arial; font-weight: bold" line-height= "2em" align="center"><?= Yii::$app->db->createCommand('SELECT cliente.nome FROM cliente JOIN projeto ON cliente.id=projeto.cliente_id WHERE projeto.cliente_id='.$projeto->cliente_id)->queryScalar().' '.Yii::$app->db->createCommand('SELECT cliente.site FROM cliente JOIN projeto ON cliente.id=projeto.cliente_id WHERE projeto.cliente_id='.$projeto->cliente_id)->queryScalar() ?> </div>

<table border="1" align="center" width="100%">
<tbody>
	<tr>
	    <td style="margin-top: 4em;font-size: 10pt;font-family: arial;" line-height= "2em" >Contrato: <?= $projeto->contrato ?> </td>
	    <td style="margin-top: 4em;font-size: 10pt;font-family: arial;" line-height= "2em" >BM Número: <?= '/'.date('Y') ?> </td>
	</tr>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >Objeto: HCN AUTOMAÇÃO LTDA </td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >Data: <?=$projeto->data_proposta ?></td>
	</tr>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >CNPJ: 10.486.000/0001-05 </td>
	</tr>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >Contato: HÉLDER CÂMARA DO NASCIMENTO </td>
    </tr>

</tbody>
</table>

<table border="1" align="center" width="100%" style="margin-top: 1em">
	<tr>
		<td style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Descrição do Serviço </td>
    </tr>	
    <tr>
		<td style="margin-top: 0.5em;font-size:8pt;font-family: arial;height: 5em"  valign="top"><?=$projeto->descricao ?> </td>
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
		<td style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Item </td>
		<td style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Descrição</td>
		<td style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Un</td>
		<td style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Quantidade</td>
		<td style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Valor Unitário</td>
		<td style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Total</td>
	</tr>
	<?php if(!empty($escopos['h_ee'])){ ?>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >0.</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Mão de Obra (EE-AUT)</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Hh</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $escopos['h_ee'] ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $tipo_exec[4]['valor_hora'] ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $escopos['h_ee'] * $tipo_exec[4]['valor_hora'] ?></td>
	</tr>	
	<?php } ?>
	<?php if(!empty($escopos['h_es'])){ ?>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >0.</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Mão de Obra (ES-AUT)</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Hh</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $escopos['h_es'] ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $tipo_exec[3]['valor_hora'] ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $escopos['h_es'] * $tipo_exec[3]['valor_hora'] ?></td>
	</tr>	
	<?php } ?>	
	<?php if(!empty($escopos['h_ep'])){ ?>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >0.</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Mão de Obra (EP-AUT)</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Hh</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $escopos['h_ep'] ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $tipo_exec[2]['valor_hora'] ?></td>
		<td style="margin-top: 1.5em;font-size: 10pt;font-family: arial;"  ><?= $escopos['h_ep'] * $tipo_exec[2]['valor_hora'] ?></td>
	</tr>	
	<?php } ?>
	<?php if(!empty($escopos['h_ej'])){ ?>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >0.</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Mão de Obra (EJ-AUT)</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Hh</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $escopos['h_ej'] ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $tipo_exec[1]['valor_hora'] ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $escopos['h_ej'] * $tipo_exec[1]['valor_hora'] ?></td>
	</tr>	
	<?php } ?>
	<?php if(!empty($escopos['h_tp'])){ ?>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >0.</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Mão de Obra (TP-AUT)</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Hh</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $escopos['h_tp'] ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $tipo_exec[0]['valor_hora'] ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $escopos['h_tp'] * $tipo_exec[0]['valor_hora'] ?></td>
	</tr>	
	<?php } ?>
	
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >0</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Deslocamento para <?= $projeto->qtd_dias ?> dias</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Km</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $projeto->qtd_km ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $projeto->vl_km ?></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $projeto->qtd_km * $projeto->vl_km ?></td>
	</tr>
	<tr>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  >Total desta medição</td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ></td>
		<td style="margin-top: 0.5em;font-size: 10pt;font-family: arial;"  ><?= $escopos['h_ee'] * $tipo_exec[4]['valor_hora']+
																			$escopos['h_es'] * $tipo_exec[3]['valor_hora'] +
																			$escopos['h_ep'] * $tipo_exec[2]['valor_hora']+
																			$escopos['h_ej'] * $tipo_exec[1]['valor_hora']+
																			$escopos['h_tp'] * $tipo_exec[0]['valor_hora']+
																			$projeto->qtd_km * $projeto->vl_km?>
																				
		</td>
	</tr>
</table>
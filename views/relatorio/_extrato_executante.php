
<style>

th, td {
    
    text-align: left;    
}
</style>


<div style="margin-bottom:1em;margin-top:-3em;font-size: 15pt;font-family: arial; font-weight: bold" line-height= "2em" align="center">Autorização de Faturamento </div>

<div style="margin-bottom:1em;font-size: 12pt;font-family: arial; font-weight: bold" line-height= "2em" align="center"><?= $prestador['nome'] ?> </div>

<div style="margin-bottom:1em;font-size: 10pt;font-family: arial;" line-height= "2em" align="right">Data: <?= $date ?> </div>
    
<table style="border: 1px solid black;" align="center" width="100%">
<tbody>
  <tr >
    <td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" rowspan="6"><img src="resources/dist/img/logo_hcn.png" alt="User Image" style="width: 8em"></td>
  </tr>	
	<tr >    
		<td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" colspan="4">Nome/Razão Social: HCN AUTOMAÇÃO LTDA </td>
		
	</tr>
	<tr>
		<td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" colspan="2">CNPJ:  10.486.000/0002-88 </td>
		<td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" colspan="2">Insc. Municipal:  0035383001 </td>
		
	</tr>
	<tr>
		<td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" colspan="3">Logradouro.: RUA FRANCISCO DRUMOND Nº: 41 </td>
		<td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" colspan="2">Compl.: EDIF. MACEDO </td>
    
    </tr>
	<tr>
		
		
    </tr>
    <tr>
      <td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" colspan="2">Bairro: CENTRO </td>
    	<td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >CEP: 42800500 </td>    	
    </tr>
    <tr>
      <td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >Município: CAMAÇARI </td>
      <td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >UF: BA </td>
    </tr>

</tbody>
</table>

<div style="text-align:center; margin: 0.5em"> <label  > FORNECEDOR </label> </div>

<table style="border: 1px solid black;" align="center" width="100%">
<tbody> 
  <tr >
    <td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" colspan="4">Nome/Razão Social: <?= $prestador['nome_empresa'] ?> </td>
    
  </tr>
  <tr>
    <td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" colspan="2">CNPJ:  <?= $prestador['cnpj'] ?> </td>
    <td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" colspan="2">Insc. Municipal:  <?= $prestador['insc_municipal'] ?> </td>
    
  </tr>
  <tr>
    <td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" colspan="3">Logradouro.: <?= $prestador['endereco_empresa'] ?> </td>
    
    </tr>
  <tr>
    
    
    </tr>
    <tr>
      <td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" colspan="2">Bairro: <?= $prestador['bairro'] ?> </td>
      <td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >CEP: <?= $prestador['cep'] ?> </td>
      <td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >Município: <?= $prestador['cidade'] ?> </td>
      <td style="padding-left:5px;margin-top: 0.5em;font-size: 10pt;font-family: arial;" line-height= "2em" >UF: BA </td>
    </tr>

</tbody>
</table>



<table  border="1" align="center" width="100%" style="margin-top: 1em; border: 1px solid black; border-collapse: collapse;">
	<tr>
		<td style="padding-left:5px;margin-top: 0.5em;font-size: 8pt;font-family: arial;height: 4em"><input type="checkbox" checked="checked">Nas unidades do(a) BRASKEM
  		</td>
  		<td style="padding-left:5px;margin-top: 0.5em;font-size: 8pt;font-family: arial;height: 4em">NF BRASKEM Nº
  		</td>
    </tr>	
    <tr>
		<td style="padding-left:5px;margin-top: 0.5em;font-size: 8pt;font-family: arial;height: 4em"><input type="checkbox" >Fora das unidades do(a) BRASKEM
  		</td>
  		<td style="padding-left:5px;margin-top: 0.5em;font-size: 8pt;font-family: arial;height: 4em">NF Devolução Nº
  		</td>
    </tr>
</table>

<table id="tabela_extrato" border="1" align="center" width="100%" style="margin-top: 1em; border: 1px solid black; border-collapse: collapse;">
    <tr>
      <th style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;font-weight: bold" rowspan="2">Item</th>
      <th style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;font-weight: bold" rowspan="2">Projeto</th>
      <th style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;font-weight: bold" rowspan="2">BM</th>
      <th style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;font-weight: bold" rowspan="2">Descrição</th>
      <th style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;font-weight: bold" colspan="5">Horas Executadas</th>
      <th style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;font-weight: bold" rowspan="2">Valor Total (R$)</th>
      <th style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;font-weight: bold" rowspan="2">Previsão Pagamento</th>
      
    </tr>
    <tr>
      <th style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;font-weight: bold">EE</th>
      <th style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;font-weight: bold">ES</th>
      <th style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;font-weight: bold">EP</th>
      <th style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;font-weight: bold">EJ</th>
      <th style="background-color: #d3d3d3;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;font-weight: bold">TP</th>                  
    </tr>             

    <?php
    	$total = 0;
    	foreach ($bms as $key => $bm) {

    		$infos = Yii::$app->db->createCommand('SELECT numero_bm, projeto.nome, projeto.descricao, previsao_pgt FROM bm JOIN projeto ON projeto.id=bm.projeto_id JOIN bm_executante ON bm.id=bm_executante.bm_id WHERE bm.id='.$bm.' AND bm_executante.bm_id='.$bm)->queryOne(); 

    		$horas_exe_ee = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ee) h_ee FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$bm.' AND exe_ee_id='.$prestador['usuario_id'])->queryScalar()) ? '' :  number_format(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ee) h_ee FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$bm.' AND exe_ee_id='.$prestador['usuario_id'])->queryScalar(), 1, '.', '.');

            $horas_exe_es = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_es) h_es FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$bm.' AND exe_es_id='.$prestador['usuario_id'])->queryScalar()) ? '' :  number_format(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_es) h_es FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$bm.' AND exe_es_id='.$prestador['usuario_id'])->queryScalar(), 1, '.', '.');

            $horas_exe_ep = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ep) h_ep FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$bm.' AND exe_ep_id='.$prestador['usuario_id'])->queryScalar()) ? '' :  number_format(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ep) h_ep FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$bm.' AND exe_ep_id='.$prestador['usuario_id'])->queryScalar(), 1, '.', '.');

            $horas_exe_ej = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ej) h_ej FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$bm.' AND exe_ej_id='.$prestador['usuario_id'])->queryScalar()) ? '' :  number_format(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ej) h_ej FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$bm.' AND exe_ej_id='.$prestador['usuario_id'])->queryScalar(), 1, '.', '.');

            $horas_exe_tp = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_tp) h_tp FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$bm.' AND exe_tp_id='.$prestador['usuario_id'])->queryScalar()) ? '' :  number_format(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_tp) h_tp FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$bm.' AND exe_tp_id='.$prestador['usuario_id'])->queryScalar(), 1, '.', '.');

              if($horas_exe_tp == 0.00) $horas_exe_tp = '';
              if($horas_exe_ej == 0.00) $horas_exe_ej = '';
              if($horas_exe_ep == 0.00) $horas_exe_ep = '';
              if($horas_exe_es == 0.00) $horas_exe_es = '';
              if($horas_exe_ee == 0.00) $horas_exe_ee = '';

            ?>
            <tr>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;"  >0<?= $key+1 ?></td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;"  ><?= $infos['nome'] ?></td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;"  ><?= $infos['numero_bm'] ?></td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;"  ><?= $infos['descricao'] ?></td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;"  ><?= $horas_exe_ee ?></td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;"  ><?= $horas_exe_es ?></td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;"  ><?= $horas_exe_ep ?></td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;"  ><?= $horas_exe_ej ?></td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;"  ><?= $horas_exe_tp ?></td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;"  ><?= number_format($valor_pago[4]['valor_pago'] * $horas_exe_ee + $valor_pago[3]['valor_pago'] * $horas_exe_es + $valor_pago[2]['valor_pago'] * $horas_exe_ep + $valor_pago[1]['valor_pago'] * $horas_exe_ej + $valor_pago[0]['valor_pago'] * $horas_exe_tp, 2, ',', '.') ?></td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;"  ><?= !empty($infos['previsao_pgt']) ? date_format(DateTime::createFromFormat('Y-m-d', $infos['previsao_pgt']), 'd/m/Y') : '-' ?></td>
			</tr>	

			
     <?php	
     		$total = $total + $valor_pago[4]['valor_pago'] * $horas_exe_ee + $valor_pago[3]['valor_pago'] * $horas_exe_es + $valor_pago[2]['valor_pago'] * $horas_exe_ep + $valor_pago[1]['valor_pago'] * $horas_exe_ej + $valor_pago[0]['valor_pago'] * $horas_exe_tp;
 		}
 	 ?>

     		<tr>
				<td style="padding-left:5px;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: left;" colspan="9">Total</td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;" colspan="1"><?= number_format($total, 2, ',', '.') ?></td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;" colspan="1"></td>
			</tr>
			<tr>
				<td style="padding-left:5px;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: left;" colspan="9">Adiantamento</td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;" colspan="1">0.00</td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;" colspan="1"></td>
			</tr>
			<tr>
				<td style="padding-left:5px;margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: left;" colspan="9">Total a Receber</td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;" colspan="1"><?= number_format($total, 2, ',', '.') ?></td>
				<td style="margin-top: 0.5em;font-size: 8pt;font-family: arial;text-align: center;" colspan="1"></td>
			</tr>
              
  </table>
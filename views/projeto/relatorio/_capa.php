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
<img src="resources/dist/img/logo_clientes/brasken_logo.png" alt="User Image" style="float: right;width: 10em">

<div style="margin-top:7em;font-size: 30pt;font-family: arial" line-height= "2em" align="center">Autorização de Serviço </div>
<div style="margin-top:2em;font-size: 15pt;font-family: arial" align="center">Nº HCN <?=$projeto->proposta?> </div>
<div style="margin-top:1.5em;font-size: 30pt;font-family: arial" align="center"><?=$projeto->descricao?> </div>
<div style="margin-top:0.1em;margin-bottom:12em;font-size: 15pt;font-family: arial" align="center"><?=$projeto->nome?> </div>

<table border="1" align="center">
<tbody>
      <tr>
            <td width="64" align="center">REV.</td>
            <td width="73" align="center">DATA</td>
            <td colspan="7" width="404">DESCRIÇÃO</td>
            <td width="64" align="center">POR</td>
      </tr>
      <tr>
            <td align="center"><?=$projeto->rev_proposta?></td>
            <td><?=date_format(DateTime::createFromFormat('Y-m-d', $projeto->data_proposta), 'd/m/Y');?></td>
            <td colspan="7">Emiss&atilde;o Inicial</td>
            <td align="center">HCN</td>
      </tr>
      <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="7">&nbsp;</td>
            <td>&nbsp;</td>
      </tr>
      <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="7">&nbsp;</td>
            <td>&nbsp;</td>
      </tr>
      <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="7">&nbsp;</td>
            <td>&nbsp;</td>
      </tr>
</tbody>
</table>

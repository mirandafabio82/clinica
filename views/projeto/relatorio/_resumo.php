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

<div style="margin-top:-3em;font-size: 10pt;font-family: arial; font-weight: bold" line-height= "2em" align="center">ANEXO IV </div>
<div style="font-size: 10pt;font-family: arial; font-weight: bold;" line-height= "2em" align="center">RESUMO DO PROJETO</div>
<div style="font-size: 10pt;font-family: arial; font-weight: bold" line-height= "2em" align="center"><?=$projeto->descricao ?> </div>

<div style="font-size: 10pt;font-family: arial; font-weight: bold; float: right;" line-height= "2em" ><?=$projeto->nome ?> </div>
<div style="margin-bottom:1em; font-size: 10pt;font-family: arial; font-weight: bold; float: right;" line-height= "2em" >ÁREA: <?=$projeto->planta ?> </div>

<table border="1" align="center" width="100%">
<tbody>
      <tr>
            <td style="background-color: #d3d3d3;font-size: 8pt">1. ESCOPO</td>            
      </tr>
      <tr>
            <td style="font-size: 8pt"><?=nl2br($projeto->resumo_escopo) ?></td>
            
      </tr>
      <tr>
            <td style="background-color: #d3d3d3;font-size: 8pt">2. EXCLUSÕES DE ESCOPO</td>            
      </tr>
      <tr>
            <td style="font-size: 8pt"><?=nl2br($projeto->resumo_exclusoes) ?></td>            
      </tr>
      <tr>
            <td style="background-color: #d3d3d3;font-size: 8pt">3. PREMISSAS ADOTADAS</td>            
      </tr>
      <tr>
            <td style="font-size: 8pt"><?=nl2br($projeto->resumo_premissas) ?></td>            
      </tr>
      <tr>
            <td style="background-color: #d3d3d3;font-size: 8pt">4. RESTRIÇÕES</td>            
      </tr>
      <tr>
            <td style="font-size: 8pt"><?=nl2br($projeto->resumo_restricoes) ?></td>            
      </tr>
      <tr>
            <td style="background-color: #d3d3d3;font-size: 8pt">5. NORMAS E ESPECIFICAÇÕES A SEREM UTILIZADAS</td>            
      </tr>
      <tr>
            <td style="font-size: 8pt"><?=nl2br($projeto->resumo_normas) ?></td>            
      </tr>
      <tr>
            <td style="background-color: #d3d3d3;font-size: 8pt">6. DOCUMENTOS DE REFÊRENCIA</td>            
      </tr>
      <tr>
            <td style="font-size: 8pt"><?=nl2br($projeto->resumo_documentos) ?></td>            
      </tr>
      <tr>
            <td style="background-color: #d3d3d3;font-size: 8pt">7. ITENS DE COMPRA</td>            
      </tr>
      <tr>
            <td style="font-size: 8pt"><?=nl2br($projeto->resumo_itens) ?></td>            
      </tr>
      <tr>
            <td style="background-color: #d3d3d3;font-size: 8pt">8. PRAZO</td>            
      </tr>
      <tr>
            <td style="font-size: 8pt"><?=nl2br($projeto->resumo_prazo)?></td>            
      </tr>
      
</tbody>
</table>
<br>
<table border="1" align="center" width="100%">
<tbody>
      <tr>
            <td style="background-color: #d3d3d3;font-size: 8pt" align="center">OBSERVAÇÕES</td>            
      </tr>
      <tr>
            <td style="font-size: 8pt"><?=$projeto->resumo_observacoes ?></td>            
      </tr>
      
</tbody>
</table>

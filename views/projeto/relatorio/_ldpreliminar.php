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
<div style="font-size: 10pt;font-family: arial; font-weight: bold;" line-height= "2em" align="center">RESUMO DO PROJETO</div>
<div style="font-size: 10pt;font-family: arial; font-weight: bold" line-height= "2em" align="center"><?=$projeto->descricao ?> </div>

<div style="font-size: 10pt;font-family: arial; font-weight: bold; float: right;margin-top: 1em;margin-left: 41em" line-height= "2em" ><?=$projeto->nome ?> </div>
<div style="margin-bottom:1em; font-size: 10pt;font-family: arial; font-weight: bold;margin-left: 41em " line-height= "2em" >ÁREA: <?=$projeto->planta ?> </div>

<table border="1" align="center" width="100%">
<tbody>
      <tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" align="center">ITEM</td> 
            <td style="font-size: 8pt" align="center">Nº HCN</td> 
            <td style="font-size: 8pt" align="center">Nº BRASKEM</td> 
            <td style="font-size: 8pt" align="center">TÍTULO</td>            
      </tr>
      <?php foreach ($ldpreliminarArray as $key => $ldpreliminar) {  ?>
      <tr>
            <td style="font-size: 8pt"><?= $ldpreliminar['id'] ?></td>
            <td style="font-size: 8pt"><?= $ldpreliminar['hcn'] ?></td>
            <td style="font-size: 8pt"><?= $ldpreliminar['cliente'] ?></td>
            <td style="font-size: 8pt"><?= $ldpreliminar['titulo'] ?></td>
            
      </tr>
      <?php } ?>
      
</tbody>
</table>
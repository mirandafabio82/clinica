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

<?php if($projeto->tipo=="A"){ ?>
      <div style="margin-top:5em;font-size: 30pt;font-family: arial" line-height= "2em" align="center">Autorização de Serviço </div>
<?php } else{ ?>
      <div style="margin-top:5em;font-size: 30pt;font-family: arial" line-height= "2em" align="center">Proposta </div>
<?php } ?>
<div style="margin-top:2em;font-size: 15pt;font-family: arial" align="center">Nº HCN <?=$projeto->proposta?> </div>
<div style="margin-top:2em;font-size: 15pt;font-family: arial" align="center"><?=$projeto->nome?> </div>
<div style="margin-top:1.5em;font-size: 20pt;font-family: arial" align="center"><?=$projeto->descricao?> </div>

<?php if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante']) && (Yii::$app->db->createCommand('SELECT cargo FROM executante WHERE usuario_id='.Yii::$app->user->id)->queryScalar() <> 2)){ ?>
      <div style="margin-top:2em;font-size: 12pt;font-family: arial;" align="center" >
            EXECUTANTE: <?=Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.Yii::$app->user->getId())->queryScalar() ?> </div>
<?php } ?>

<table border="1" align="center" style="margin-top:15em;">
<tbody>
      <tr>
            <td width="64" align="center">REV.</td>
            <td width="73" align="center">DATA</td>
            <td colspan="7" width="404">DESCRIÇÃO</td>
            <td width="64" align="center">POR</td>
      </tr>
      
      <?php 
            $revisoes = Yii::$app->db->createCommand('SELECT * FROM revisao_projeto WHERE projeto_id = '.$projeto->id)->queryAll();
            foreach ($revisoes as $key => $revisao) { ?>                        
                  <tr>
                      <td align="center" padding="10px" style="padding: 2px"><?= $key?></td>
                      <td align="center" padding="10px" style="padding: 2px"><?= date_format(DateTime::createFromFormat('Y-m-d', $revisao['data']), 'd/m/Y'); ?></td>
                      <td padding="10px" colspan="7" style="padding: 2px"><?= $revisao['descricao'] ?></td>
                      <td align="center" padding="10px" style="padding: 2px"><?= $revisao['por'] ?></td>
                  </tr>
      <?php } ?>
      <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="7">&nbsp;</td>
            <td>&nbsp;</td>
      </tr>
      
</tbody>
</table>

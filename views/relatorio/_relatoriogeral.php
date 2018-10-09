
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
    font-size: 8px;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    /*background-color: #dddddd;*/
}

div.scrollmenu {
    overflow: auto;
    white-space: nowrap;
}

div.scrollmenu a {
    display: inline-block;
    color: white;
    text-align: center;
    padding: 14px;
    text-decoration: none;
}

th {
    background-color: #3c8dbc;
    color: white;
}
</style>
</head>

<div class="box box-primary">
    <div class="box-header with-border scrollmenu">
   
<table>
  <tr>
    <th rowspan="2">Projeto</th>
    <th rowspan="2">Descrição</th>
    <th rowspan="2">Planta</th>
    <th rowspan="2">Solicitante</th>
    <th rowspan="2">AS</th>
    <th rowspan="2">Valor</th>
    <th rowspan="2">ES-AUT</th>
    <th rowspan="2">EP-AUT</th>
    <th rowspan="2">TEC-AUT</th>
    <th rowspan="2">KM</th>
    <th rowspan="2">BM</th>
    <th rowspan="2">ES-AUT</th>
    <th rowspan="2">EP-AUT</th>
    <th rowspan="2">TEC-AUT</th>
    <th rowspan="2">KM</th>
    <th rowspan="2">VALOR</th>
    <th rowspan="2">Data</th>
    <th rowspan="2">Faturado</th>
    <th colspan="4">Saldo</th>
    <th rowspan="2">Saldo</th>
    <th rowspan="2">Status</th>
  </tr>
  <tr>
    <th>ES-AUT</th>
    <th>EP-AUT</th>
    <th>TEC-AUT</th>
    <th>KM</th>
  </tr>
  
  <?php foreach ($projetos as $key => $projeto) { 
    //$solicitante = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.$projeto['cliente_id'])->queryScalar();
    $bms = Yii::$app->db->createCommand('SELECT * FROM bm WHERE projeto_id='.$projeto['id'])->queryAll();
    $qtd_bms = count($bms);
    $rowspan = 'rowspan="'.$qtd_bms.'"';

    $escopos = Yii::$app->db->createCommand('SELECT SUM(horas_ee) horas_ee, SUM(horas_es) horas_es, SUM(horas_ep) horas_ep, SUM(horas_ej) horas_ej, SUM(horas_tp) horas_tp FROM escopo WHERE projeto_id='.$projeto['id'])->queryOne();

    if($qtd_bms==0){
      $rowspan = '';
      continue;
    }
  ?>
  
  <tr>
    <td <?= $rowspan ?> ><?= $projeto['nome'] ?></td>
    <td <?= $rowspan ?> ><?= $projeto['descricao'] ?></td>
    <td <?= $rowspan ?> ><?= $projeto['site'] ?></td>
    <td <?= $rowspan ?> ><?= $projeto['contato'] ?></td>
    <td <?= $rowspan ?> ><?= $projeto['proposta'] ?></td>
    <td <?= $rowspan ?> ><?= number_format($projeto['valor_proposta'], 2, ',', '.') ?></td>
    <td <?= $rowspan ?> ><?= $escopos['horas_es']?></td>
    <td <?= $rowspan ?> ><?= $escopos['horas_ep'] ?></td>
    <td <?= $rowspan ?> ><?= $escopos['horas_tp'] ?></td>
    <td <?= $rowspan ?> ><?= $projeto['qtd_km'] ?> </td>

    <?php 
      $faturado = 0;
    foreach ($bms as $key => $bm) { 
        $bm_escopo = Yii::$app->db->createCommand('SELECT SUM(horas_es) horas_es, SUM(horas_ep) horas_ep, SUM(horas_tp) horas_tp FROM bm_escopo WHERE bm_id='.$bm['id'])->queryOne();
        $tipo_executante = Yii::$app->db->createCommand('SELECT * FROM tipo_executante')->queryAll();
                    
        $valor_bm = $bm['executado_tp']*$tipo_executante[0]['valor_hora']+$bm['executado_ej']*$tipo_executante[1]['valor_hora']+$bm['executado_ep']*$tipo_executante[2]['valor_hora']+$bm['executado_es']*$tipo_executante[3]['valor_hora']+$bm['executado_ee']*$tipo_executante[4]['valor_hora']+$bm['km'] * Yii::$app->db->createCommand('SELECT vl_km FROM executante WHERE usuario_id=61')->queryScalar();

        $executado = Yii::$app->db->createCommand('SELECT SUM(executado_ee) executado_ee, SUM(executado_es) executado_es, SUM(executado_ep) executado_ep, SUM(executado_ej) executado_ej, SUM(executado_tp) executado_tp, SUM(km) km FROM bm WHERE projeto_id='.$projeto['id'])->queryOne();

        $saldo = ($escopos['horas_ee']+$escopos['horas_es']+$escopos['horas_ep']+$escopos['horas_ej']+$escopos['horas_tp'])-($executado['executado_ee']+$executado['executado_es']+$executado['executado_ep']+$executado['executado_ej']+$executado['executado_tp']);

        $faturado = $faturado + $valor_bm;

        $valor_bm = number_format($valor_bm, 2, ',', '.');

      ?>
      <?php if($key==0){ ?>
        <td ><?= 'BM-'.explode('AS-', explode('_', $projeto['proposta'])[0])[1].'_'.$bm['numero_bm'] ?></td>
        <td ><?= $bm_escopo['horas_es']?></td>
        <td ><?= $bm_escopo['horas_ep']?></td>
        <td ><?= $bm_escopo['horas_tp']?></td>
        <td ><?= $bm['km']?></td>
        <td ><?= $valor_bm?></td>
        <td ><?= date('d/m/Y', strtotime($bm['data']))?></td>
        <td <?= $rowspan ?> ><?= number_format($faturado, 2, ',', '.') ?></td>
        <td <?= $rowspan ?> ><?= $executado['executado_es'] ?></td>
        <td <?= $rowspan ?> ><?= $executado['executado_ep'] ?></td>
        <td <?= $rowspan ?> ><?= $executado['executado_tp'] ?></td>
        <td <?= $rowspan ?> ><?= $executado['km'] ?></td>
        <td <?= $rowspan ?> ><?= $saldo ?></td>
        <td <?= $rowspan ?> ><?= 'nota geral' ?></td>
      <?php } else{?>
      <tr>        
        <td ><?= 'BM-'.explode('AS-', explode('_', $projeto['proposta'])[0])[1].'_'.$bm['numero_bm'] ?></td>
        <td ><?= $bm_escopo['horas_es']?></td>
        <td ><?= $bm_escopo['horas_ep']?></td>
        <td ><?= $bm_escopo['horas_tp']?></td>
        <td ><?= $bm['km'] ?></td>
        <td ><?= $valor_bm ?></td>
        <td ><?= date('d/m/Y', strtotime($bm['data'])) ?></td>
       
      </tr>


    <?php }} ?>

    
  </tr>
<?php } ?>
</table>
</div>
</div>
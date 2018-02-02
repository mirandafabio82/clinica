<?php 
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Escopo;

$escopo_status = Yii::$app->db->createCommand('SELECT id, status as nome FROM escopo_status')->queryAll();
$listStatus = ArrayHelper::map($escopo_status,'id','nome');

$escopos = Yii::$app->db->createCommand('SELECT * FROM escopo WHERE (horas_tp IS NOT NULL OR horas_ej IS NOT NULL OR horas_ep IS NOT NULL OR horas_es IS NOT NULL OR horas_ee IS NOT NULL) AND projeto_id='.$model->id)->queryAll();



?>
<style>
  .form-group{
    margin-bottom: 0;

  }

  .help-block {    
     margin-bottom: 0; 
  }

  .control-label{
    font-size:10px;
  }

#tabela-escopo {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#tabela-escopo td, #tabela-escopo th {
    border: 1px solid #ddd;
    padding-left: 8px;
}

#tabela-escopo tr:nth-child(even){background-color: #f2f2f2;}

#tabela-escopo tr:hover {background-color: #ddd;}

#tabela-escopo th {
    padding-top: 6px;
    padding-bottom: 6px;
    text-align: left;
    background-color: #337ab7;
    color: white;
}
tr{
      background-color: white !important;      
}
/*thead {   
    display:table;
   
    width:100%;
}
tbody {
    height:350px;
    overflow:auto;
    overflow-x:hidden;
    display:block;
    
}
*/


</style>
<div class="box-header with-border">
     <div class="form-group">
<table style="width:100%; margin-bottom: 1em" id="tabela-escopo">
    <col width="600">
    <thead>
       
        <tr>
          <th style="width:1em;">Status</th>
          <th style="width:100em;">Nome</th>
          <th style="width:1em;padding-right: 1em;">Executado</th>
        </tr>
      </thead>
      <?php $form = ActiveForm::begin(); ?>
      <?php foreach ($escopos as $key => $escopo) { 
            $escopoModel = Escopo::findIdentity($escopo['id']);
      ?> 
      <tr>
        <td style="font-size: 15px; padding: 1px;"><?=$form->field($escopoModel, 'status')->dropDownList($listStatus)->label(false) ?></td>
        <td style="font-size: 15px; padding: 1px;padding-left: 1em;"><?=$escopo['nome'] ?></td>
        <td style="font-size: 15px; padding-right: 1em;"><?=$escopo['executado'] ?></td>
      </tr>
      <?php } ?>

      <?php ActiveForm::end(); ?>
</table>
</div>
</div>
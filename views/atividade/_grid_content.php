<?php 
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Escopo;
use yii\widgets\Pjax;

$escopo_status = Yii::$app->db->createCommand('SELECT id, status as nome, cor FROM escopo_status')->queryAll();
$listStatus = ArrayHelper::map($escopo_status,'id','nome');

if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante'])){
  $escopos = Yii::$app->db->createCommand('SELECT * FROM escopo WHERE ((horas_tp IS NOT NULL AND exe_tp_id ='.Yii::$app->user->getId().') OR (horas_ej IS NOT NULL AND exe_ej_id ='.Yii::$app->user->getId().') OR (horas_ep IS NOT NULL AND exe_ep_id ='.Yii::$app->user->getId().') OR (horas_es IS NOT NULL AND exe_es_id ='.Yii::$app->user->getId().') OR (horas_ee IS NOT NULL AND exe_ee_id ='.Yii::$app->user->getId().')) AND projeto_id='.$model->id)->queryAll();
}
else{
  $escopos = Yii::$app->db->createCommand('SELECT * FROM escopo WHERE (horas_tp IS NOT NULL OR horas_ej IS NOT NULL OR horas_ep IS NOT NULL OR horas_es IS NOT NULL OR horas_ee IS NOT NULL) AND projeto_id='.$model->id)->queryAll();
}

if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['contato'])) {
  $editable = true;
}
else{
   $editable = false;
}

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
<?php Pjax::begin() ?>
<div id="pjax-status">

<table style="width:100%; margin-bottom: 1em" id="tabela-escopo">
    <col width="600">
    <thead>
       
        <tr>
          <th style="width:150em;">Nome</th>
          <th style="width:1em;padding-right: 1em;">Horas</th>
          <th style="width:1em;padding-right: 1em;">Executado</th>
          <th style="width:50em;padding-right: 1em;">Progresso</th>
          <th style="width:30em;">Status</th>
        </tr>
      </thead>
      <?php $form = ActiveForm::begin(); ?>
      <?php foreach ($escopos as $key => $escopo) { 
            $cor = Yii::$app->db->createCommand('SELECT cor FROM escopo_status WHERE id='.$escopo['status'])->queryScalar();
            $escopoModel = Escopo::findIdentity($escopo['id']);
            $progress = $escopoModel->executado / ($escopoModel['horas_ee']+$escopoModel['horas_es']+$escopoModel['horas_ep']+$escopoModel['horas_ej']+$escopoModel['horas_tp']) * 100;
            
            if($progress <= 30) $progressColor = 'danger'; 
            if($progress <= 99.9 && $progress > 30) $progressColor = 'warning';
            if($progress == 100) $progressColor = 'success';
      ?> 
      
      <tr style="background-color: <?=$cor?> !important">
        <td style="font-size: 15px; padding: 1px;padding-left: 1em;color: white"><?=$escopo['nome'] ?></td>
        <td style="font-size: 15px; padding-right: 1em;color: white"><?= $escopoModel['horas_ee']+$escopoModel['horas_es']+$escopoModel['horas_ep']+$escopoModel['horas_ej']+$escopoModel['horas_tp'] ?>  </td>
        <td style="font-size: 15px; padding-right: 1em;"><?= $form->field($escopoModel, 'executado')->textInput(['maxlength' => true, 'readonly'=>$editable, 'name'=>'Escopo['.$escopo['id'].'][executado]', 'class' =>'form-control executado'])->label(false) ?>  </td>
        <td style="font-size: 15px; padding-right: 1em;">
        <div class="progress progress-xs">
          <div class="progress-bar progress-bar-<?=$progressColor ?> progress-bar-striped" style="width: <?= $progress ?>%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
            <!-- <span class="sr-only">40% Complete</span> -->
          </div>
        </div>
          </td>
          <?php $escopoModel->status = $escopoModel->isNewRecord ? 2 : $escopoModel->status; ?>
        <td style="font-size: 15px; padding: 1px;"><?=$form->field($escopoModel, 'status')->dropDownList($listStatus,['class' =>'form-control status', 'id'=>'status-'.$escopo['id'], 'name'=>'Escopo['.$escopo['id'].'][status]', 'disabled'=>$editable])->label(false) ?></td>
      </tr>
      
      <?php } ?>

      <?php ActiveForm::end(); ?>
</table>
</div>
</div>
</div>
<?php Pjax::end() ?>
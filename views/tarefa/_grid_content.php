<?php 
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Escopo;
use yii\widgets\Pjax;


$escopo_status = Yii::$app->db->createCommand('SELECT id, status as nome, cor FROM escopo_status')->queryAll();
$listStatus = ArrayHelper::map($escopo_status,'id','nome');

//se for executante
if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante'])){
  $escopos = Yii::$app->db->createCommand('SELECT * FROM escopo WHERE ((horas_tp !="" AND exe_tp_id ='.Yii::$app->user->getId().') OR (horas_ej !="" AND exe_ej_id ='.Yii::$app->user->getId().') OR (horas_ep !="" AND exe_ep_id ='.Yii::$app->user->getId().') OR (horas_es !="" AND exe_es_id ='.Yii::$app->user->getId().') OR (horas_ee !="" AND exe_ee_id ='.Yii::$app->user->getId().')) AND projeto_id='.$model->id)->queryAll();
}
else{ //se for ADMIN
  if(empty($executante_id)){//não implementado
    $escopos = Yii::$app->db->createCommand('SELECT * FROM escopo WHERE (horas_tp !="" OR horas_ej !="" OR horas_ep !="" OR horas_es !="" OR horas_ee !="") AND projeto_id='.$model->id)->queryAll();
  }
  else{
    $escopos = Yii::$app->db->createCommand('SELECT * FROM escopo WHERE ((horas_tp !="" AND exe_tp_id ='.$executante_id.') OR (horas_ej !="" AND exe_ej_id ='.$executante_id.') OR (horas_ep !="" AND exe_ep_id ='.$executante_id.') OR (horas_es !="" AND exe_es_id ='.$executante_id.') OR (horas_ee !="" AND exe_ee_id ='.$executante_id.')) AND projeto_id='.$model->id)->queryAll();

   
  }
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

<?php
$this->registerJs("

  $( document ).ready(function() {    
    // $('input').removeClass('form-control');     
  });

    $('.executado').change(function (e) {
        
        id = this.name.split('[')[1];
        id = id.split(']')[0];
        console.log($('#progress-bar['+id+']'));

        // $('#progress-bar['+id+']').width('10%');

    });
");

?>



<div class="box-header with-border">
     <div class="form-group">
<?php Pjax::begin() ?>
<div id="pjax-status">

<?php $form = ActiveForm::begin(); ?>

<!-- <?//= $form->field($model, 'obs_atividade')->textArea(['maxlength' => true, 'class' =>'form-control obs_atividade', 'id'=>'Projeto['.$model->id.']'])->label(false) ?> -->

<table style="width:100%; margin-bottom: 1em" id="tabela-escopo">
    <col width="600">
    <thead>
       
        <tr>
          <th style="width:150em;text-align: center;" rowspan="2">Nome</th>                    
          <th style="width:1em;padding-right: 1em;text-align: center;" colspan="5">Horas</th>          
        </tr>
        <tr>                            
          <!-- <th style="width:1em;padding-right: 1em;text-align: center;">AS</th> -->
          <th style="width:50em;padding-right: 1em;text-align: center;">Executada</th>
          <th style="width:50em;padding-right: 1em;text-align: center;">BM</th>
          <th style="width:30em;text-align: center;">Acumulada</th>
          <th style="width:30em;text-align: center;">Saldo</th>
        </tr>
      </thead>
      <?php foreach ($escopos as $key => $escopo) { 
           $escopoModel = Escopo::findIdentity($escopo['id']);
            if($escopoModel['horas_ee']+$escopoModel['horas_es']+$escopoModel['horas_ep']+$escopoModel['horas_ej']+$escopoModel['horas_tp'] > 0){
              $progress = $escopoModel->executado / ($escopoModel['horas_ee']+$escopoModel['horas_es']+$escopoModel['horas_ep']+$escopoModel['horas_ej']+$escopoModel['horas_tp']) * 100;
            }
            else{
              $progress = 0;
            }
            
           
      ?> 
      <?php if($escopo['exe_tp_id']==Yii::$app->user->getId() || $escopo['exe_ej_id']==Yii::$app->user->getId() || $escopo['exe_ep_id']==Yii::$app->user->getId() || $escopo['exe_es_id']==Yii::$app->user->getId() || $escopo['exe_ee_id']==Yii::$app->user->getId() || isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin'])){ ?>
      <tr style="background-color: #fff !important">
        <td style="font-size: 15px; padding: 1px;padding-left: 1em;color: #000"><?=$escopo['nome'] ?></td>
          
        
        <td style="font-size: 15px; padding-right: 1em;text-align: center; ">
        <div class="row"> 
          <?php if($escopo['exe_tp_id']==$executante_id && !empty($escopoModel['horas_tp'])){ ?>
              <div class="col-md-5"> 
                <?= $form->field($escopoModel, 'executado_tp')->textInput(['maxlength' => true, 'readonly'=>$editable, 'name'=>'Escopo['.$escopo['id'].'][executado_tp]', 'class' =>'form-control executado_tp', 'value'=> '' ])->label('TP:'.explode('.',$escopoModel['executado_tp'])[0].'/'.$escopoModel['horas_tp'].'; BM:'.$escopo['horas_tp_bm']) ?>
              </div>
              <?php } ?>  

            <?php if($escopo['exe_ej_id']==$executante_id && !empty($escopoModel['horas_ej'])){ ?>
              <div class="col-md-5"> 
                <?= $form->field($escopoModel, 'executado_ej')->textInput(['maxlength' => true, 'readonly'=>$editable, 'name'=>'Escopo['.$escopo['id'].'][executado_ej]', 'class' =>'form-control executado_ej', 'value'=> '' ])->label('EJ:'.explode('.',$escopoModel['executado_ej'])[0].'/'.$escopoModel['horas_ej']) ?>
              </div>
              <?php } ?>  

            <?php if($escopo['exe_ep_id']==$executante_id && !empty($escopoModel['horas_ep'])){ ?>
              <div class="col-md-5"> 
              <?= $form->field($escopoModel, 'executado_ep')->textInput(['maxlength' => true, 'readonly'=>$editable, 'name'=>'Escopo['.$escopo['id'].'][executado_ep]', 'class' =>'form-control executado_ep', 'value'=> '' ])->label('EP:'.explode('.',$escopoModel['executado_ep'])[0].'/'.$escopoModel['horas_ep'].'; BM:'.$escopo['horas_ep_bm']) ?>
              </div>
              <?php } ?>  

            <?php if($escopo['exe_es_id']==$executante_id && !empty($escopoModel['horas_es'])){ ?>
              <div class="col-md-5"> 
              <?= $form->field($escopoModel, 'executado_es')->textInput(['maxlength' => true, 'readonly'=>$editable, 'name'=>'Escopo['.$escopo['id'].'][executado_es]', 'class' =>'form-control executado_es', 'value'=> '' ])->label('ES:'.explode('.',$escopoModel['executado_es'])[0].'/'.$escopoModel['horas_es']) ?>
              </div>
              <?php } ?>  

            <?php if($escopo['exe_ee_id']==$executante_id && !empty($escopoModel['horas_ee'])){ ?>
              <div class="col-md-5"> 
              <?= $form->field($escopoModel, 'executado_ee')->textInput(['maxlength' => true, 'readonly'=>$editable, 'name'=>'Escopo['.$escopo['id'].'][executado_ee]', 'class' =>'form-control executado_ee', 'value'=> '' ])->label('EE:'.explode('.',$escopoModel['executado_ee'])[0].'/'.$escopoModel['horas_ee']) ?>
              </div>
              <?php } ?>  
            </div>
        </td>
       
      
        <td style=" text-align: center;font-size: 15px; padding-right: 0.5em;color: #000"><?= $escopoModel['horas_bm'] ?>  </td>

        <td style=" text-align: center;font-size: 15px; padding-right: 0.5em;color: #000"><?= $escopoModel['horas_acumulada'] ?>  </td>    

         <td style=" text-align: center;font-size: 15px; padding-right: 0.5em;color: #000"><?= $escopoModel['horas_saldo'] ?>  </td>      

         
      </tr>
      
      <?php } } ?>

</table>
      <?php ActiveForm::end(); ?>
</div>
</div>
</div>
<?php Pjax::end() ?>
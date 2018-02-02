<?php 
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Escopo;
use yii\widgets\Pjax;

$escopo_status = Yii::$app->db->createCommand('SELECT id, status as nome, cor FROM escopo_status')->queryAll();
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

<?php 
  $this->registerJs('

      $(".status").change(function(){
      id = this.id.split("-")[1];
      status = $(this).val();

         $.ajax({ 
            url: "index.php?r=atividade/attatividade",
            data: {id: id, status: status},
            container:"#pjax-status",
            type: "POST",
            success: function(response){
             console.log(response);
             location.reload();
           },
           error: function(){
            console.log("failure");
          }
  });
      })

  ');
?>


<div class="box-header with-border">
     <div class="form-group">
<?php Pjax::begin() ?>
<div id="pjax-status">

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
            $cor = Yii::$app->db->createCommand('SELECT cor FROM escopo_status WHERE id='.$escopo['status'])->queryScalar();
            $escopoModel = Escopo::findIdentity($escopo['id']);
      ?> 
      
      <tr style="background-color: <?=$cor?> !important">
        <td style="font-size: 15px; padding: 1px;"><?=$form->field($escopoModel, 'status')->dropDownList($listStatus,['class' =>'form-control status', 'id'=>'status-'.$escopo['id']])->label(false) ?></td>
        <td style="font-size: 15px; padding: 1px;padding-left: 1em;color: white"><?=$escopo['nome'] ?></td>
        <td style="font-size: 15px; padding-right: 1em;"><?= $form->field($escopoModel, 'executado')->textInput(['maxlength' => true])->label(false) ?>  </td>
      </tr>
      
      <?php } ?>

      <?php ActiveForm::end(); ?>
</table>
</div>
</div>
</div>
<?php Pjax::end() ?>
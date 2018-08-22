<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model app\models\Bm */
/* @var $form yii\widgets\ActiveForm */

$fileName = '';
if(!$model->isNewRecord){ 
  $fileName = '/web/uploaded-files/'.$model->projeto_id.'/BM-'.Yii::$app->db->createCommand("SELECT proposta FROM projeto WHERE id=".$model->projeto_id)->queryScalar().'_'.$model->numero_bm; 
}


$this->registerJs('

    $("#salvarHoras").click(function(){
        var horas_escopo = "";
        
        $(".horas_bm").each(function(i, obj) {            
            horas_escopo = horas_escopo + obj.id+"-"+obj.value+";";            
        });
        console.log(horas_escopo);

        $.ajax({ 
            url: "index.php?r=bm/editahoras",
            data: {horas_escopo: horas_escopo},
            type: "POST",
            success: function(response){
              alert("Atualizado com sucesso!");
              location.reload();
           },
           error: function(request, status, error){
              alert(request.responseText);
          }
        });
    });

    $("#bm-projeto_id").change(function (e) {
    	var id = $(this).val();
	     console.log(id);
	
       $.ajax({ 
          url: "index.php?r=bm/preencheform",
          data: {id: id},
          type: "POST",
          success: function(response){
           console.log(response);
           var resposta = $.parseJSON(response);
           $("#bm-objeto").val(resposta["setor"]);
           $("#bm-descricao").val(resposta["nome"]+"\nDescrição: "+resposta["descricao"]+"\n"+resposta["proposta"]+"\nSite: "+resposta["site"]);
           $("#bm-acumulado").val(resposta[1]);
           $("#bm-saldo").val(resposta[0]);
           $("#bm-executado_ee").val(resposta[2]);
           $("#bm-executado_es").val(resposta[3]);
           $("#bm-executado_ep").val(resposta[4]);
           $("#bm-executado_ej").val(resposta[5]);
           $("#bm-executado_tp").val(resposta[6]);
           
         },
         error: function(){
          console.log("failure");
        }
      });
    });

    $("td").click(function (e) {
        var id = $(this).closest("tr").attr("data-key");
        if(id != null){
            if(e.target == this)
                location.href = "' . Url::to(["bm/update"]) . '&id="+id;
        }
    });

    $("#enviarEmail").click(function (e) {
      $("#loading").show(); // show the gif image when ajax starts
        $.ajax({ 
          url: "index.php?r=bm/enviaremail",
          data: {remetentes: $("#remetente").val(), assunto: $("#assunto").val(), corpoEmail: $("#corpoEmail").val(), nomeArquivo: "'.$fileName.'"},
          type: "POST",
          success: function(response){
           $("#loading").hide();
           $("#close_modal").click(); 
           alert("Email enviado com sucesso!");
           console.log(response);
         },
         error: function(request, status, error){
          alert(request.responseText);
        }
  });
    });

');
?>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 50%;
}
td, th {
    border: 1px solid #dddddd;    
}
.table-bordered > tbody > tr > td{
  padding-top: 3px !important;
  padding-bottom: 3px !important;
}

.table-striped > tbody > tr:nth-of-type(odd){
  background-color: #b6b6b6 !important;
}
.pagination{
    margin: 0px;
}

.summary{
  display: none;
}

#w2{
    display: none;
}

.barra-btn{
  display:block;
  position:fixed;
  width:100%;
  bottom:0vh;
  left:0;
  background:#3c8dbc;
  text-align:center;
  padding: 0px 0;
  z-index: 99;
}

</style>
<!-- mask so funciona com isso -->
<?php $this->head() ?>

<div class="bm-form">
	<div class="box box-primary">
    <div class="box-header with-border">
    <div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-list"></i> BM </div>
<div style="margin-bottom:1em;margin-top: 1em">
    <?= Html::a('Mostrar Todos', ['/bm/create', 'pagination' => true], ['class'=>'btn btn-primary grid-button']) ?>
</div>
<div style="overflow: auto;overflow-y: hidden;Height:?">
    		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['style' => 'font-size:12px;'],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
        	[
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            [
              'header' => '<span style="color:#337ab7">Projeto</span>',
              'attribute' => 'projeto',              
              'format' => 'raw',
               'value' => function ($data) {
                  if(isset($data->projeto_id))
                   return Yii::$app->db->createCommand('SELECT nome FROM projeto WHERE id='.$data->projeto_id)->queryScalar();
               },
            ],
            [
                'header'=>'AS',
                'headerOptions' => ['style' => 'color:#337ab7; width:15em'],
                'value'=>function($data){  
                  if(isset($data->projeto_id))                  
                        return Yii::$app->db->createCommand('SELECT proposta FROM projeto WHERE id='.$data->projeto_id)->queryScalar();
                }
            ],
            [
                'header'=>'Valor AS',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){  
                  if(isset($data->projeto_id))                  
                        return number_format(Yii::$app->db->createCommand('SELECT valor_proposta FROM projeto WHERE id='.$data->projeto_id)->queryScalar(), 2, ',', '.');
                }
            ],     
            [
                'header'=>'BM',
                'headerOptions' => ['style' => 'color:#337ab7;width:15em'],
                'value'=>function($data){    
                if(isset($data->projeto_id))                  
                        $projeto = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN projeto ON escopo.projeto_id = projeto.id WHERE projeto.id='.$data->projeto_id)->queryOne();      
                        if(!empty($projeto))                            
                              return 'BM-'.$projeto['codigo'].'-'.$projeto['site'].'-'.preg_replace('/[^0-9]/', '', $projeto['nome']).'_'.$data->num_bm_proj;
                }
            ],       
            [
                'attribute'=>'numero_bm',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){    
                    if(isset($data->data))
                      return $data->numero_bm.'/'.explode('-',$data->data)[0];
                    else
                      return $data->numero_bm;
                }

            ],
            [
                'header'=>'Valor BM',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){    
                  $tipo_executante = Yii::$app->db->createCommand('SELECT * FROM tipo_executante')->queryAll();
                    
                    return number_format($data->executado_tp*$tipo_executante[0]['valor_hora']+$data->executado_ej*$tipo_executante[1]['valor_hora']+$data->executado_ep*$tipo_executante[2]['valor_hora']+$data->executado_es*$tipo_executante[3]['valor_hora']+$data->executado_ee*$tipo_executante[4]['valor_hora']+$data->km * Yii::$app->db->createCommand('SELECT vl_km FROM executante WHERE usuario_id=61')->queryScalar(), 2, ',', '.');
                }

            ],
            'acumulado',
            'saldo',
            [
                'attribute'=>'data',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){    
                  if(isset($data->data))
                    return date('d/m/Y', strtotime($data->data));
                }

            ],


            /*
            [
                'header'=>'EE-AUT',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){                                      
                        if(isset($data->projeto_id))                  
                        return Yii::$app->db->createCommand('SELECT SUM(horas_ee) FROM escopo JOIN projeto ON escopo.projeto_id = projeto.id WHERE projeto.id='.$data->projeto_id)->queryScalar();
                }
            ],
            [
                'header'=>'ES-AUT',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){                                      
                        if(isset($data->projeto_id))                  
                        return Yii::$app->db->createCommand('SELECT SUM(horas_es) FROM escopo JOIN projeto ON escopo.projeto_id = projeto.id WHERE projeto.id='.$data->projeto_id)->queryScalar();
                }
            ],
            [
                'header'=>'EP-AUT',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){                                      
                        if(isset($data->projeto_id))                  
                        return Yii::$app->db->createCommand('SELECT SUM(horas_ep) FROM escopo JOIN projeto ON escopo.projeto_id = projeto.id WHERE projeto.id='.$data->projeto_id)->queryScalar();
                }
            ],
            [
                'header'=>'EJ-AUT',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){                                      
                        if(isset($data->projeto_id))                  
                        return Yii::$app->db->createCommand('SELECT SUM(horas_ej) FROM escopo JOIN projeto ON escopo.projeto_id = projeto.id WHERE projeto.id='.$data->projeto_id)->queryScalar();
                }
            ],
            [
                'header'=>'TP-AUT',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){                                      
                        if(isset($data->projeto_id))                  
                        return Yii::$app->db->createCommand('SELECT SUM(horas_tp) FROM escopo JOIN projeto ON escopo.projeto_id = projeto.id WHERE projeto.id='.$data->projeto_id)->queryScalar();
                }
            ],*/
            /*[
                'header'=>'EXEC EE-AUT',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){                                      
                        return $data->executado_ee;
                }
            ],
            [
                'header'=>'EXEC ES-AUT',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){                                      
                        return $data->executado_es;
                }
            ],
            [
                'header'=>'EXEC EP-AUT',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){                                      
                        return $data->executado_ep;
                }
            ],
            [
                'header'=>'EXEC EJ-AUT',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){                                      
                        return $data->executado_ej;
                }
            ],
            [
                'header'=>'EXEC TP-AUT',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){                                      
                        return $data->executado_tp;
                }
            ],
            [
                'header'=>'KM',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){                                      
                        return $data->km;
                }
            ],*/
           /* [
                'header'=>'Valor',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){            
                      $tipo_exec = Yii::$app->db->createCommand('SELECT * FROM tipo_executante')->queryAll();
                      
                      if(isset($data->projeto_id))                  
                        $qtd_km =  Yii::$app->db->createCommand('SELECT qtd_km FROM projeto WHERE projeto.id='.$data->projeto_id)->queryScalar();

                        $vl_km = Yii::$app->db->createCommand('SELECT vl_km FROM executante WHERE usuario_id=61')->queryScalar();

                        return number_format($data->executado_es * $tipo_exec[3]['valor_hora'] +
                                      $data->executado_ep * $tipo_exec[2]['valor_hora']+
                                      $data->executado_ej * $tipo_exec[1]['valor_hora']+
                                      $data->executado_tp * $tipo_exec[0]['valor_hora']+
                                      $qtd_km * $vl_km, 2, ',', '.');
                }
            ],*/
            /*[
                'header'=>'Descrição do Projeto',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'value'=>function($data){  
                  if(isset($data->projeto_id))                  
                        return Yii::$app->db->createCommand('SELECT descricao FROM projeto WHERE id='.$data->projeto_id)->queryScalar();
                }
            ],*/
            // 'contrato',
            // 'objeto',
            // 'contratada',
            //'cnpj',
            //'contato',
            // 'data',
            //'de',
            //'para',
            //'descricao:ntext',
            

            
        ],
    ]); ?>
    </div>
    </div>
    </div>
    	<div class="box box-primary">
    <div class="box-header with-border">
    <?php if($model->isNewRecord){ ?>
    <?php $form = ActiveForm::begin(); ?>
    	<div class="row">
	    <div class="col-md-3">  
	    <?= $form->field($model, 'projeto_id')->dropDownList($listProjetos,['prompt'=>'Selecione um Projeto']) ?>
	    </div>
	    <div class="col-md-2">  
	    <?= $form->field($model, 'contrato')->textInput(['maxlength' => true, 'value' => '4600015210']) ?>
	    </div>
	    <div class="col-md-2"> 
	    <?= $form->field($model, 'objeto')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-md-3"> 
	    <?= $form->field($model, 'contratada')->textInput(['maxlength' => true, 'value' => 'HCN AUTOMAÇÃO LTDA']) ?>
 		</div>
	    <div class="col-md-2">  
	    <?= $form->field($model, 'cnpj')->textInput(['maxlength' => true, 'value' => '10.486.000/0002-88']) ?>
	    </div>
	    <div class="col-md-3"> 
	    <?= $form->field($model, 'contato')->textInput(['maxlength' => true, 'value' => 'HÉLDER CÂMARA DO NASCIMENTO']) ?>
	    </div>
	    <div class="col-md-1"> 
	    <?= $form->field($model, 'numero_bm')->textInput(['maxlength' => true, 'value' => Yii::$app->db->createCommand('SELECT ultimo_bm FROM config')->queryScalar()]) ?>
 		</div>
	    <div class="col-md-2">  
	    <?= $form->field($model, 'data')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
	    </div>
	    <div class="col-md-2"> 
	    <?= $form->field($model, 'de')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
	    </div>
	    <div class="col-md-2"> 
	    <?= $form->field($model, 'para')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
	    </div>
	     <div class="col-md-1"> 
	    <?= $form->field($model, 'acumulado')->textInput(['maxlength' => true]) ?>
 		</div>
    <div class="col-md-1"> 
      <?= $form->field($model, 'saldo')->textInput(['maxlength' => true]) ?>
    </div>
	    <div class="col-md-10"> 
	    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>
	    </div>
       <div class="col-md-1"> 
      <?= $form->field($model, 'qtd_dias')->textInput(['maxlength' => true]) ?>
    </div>
      <div class="col-md-1"> 
      <?= $form->field($model, 'km')->textInput(['maxlength' => true]) ?>
    </div>
    </div>
      EXECUTADO
    <div class="row" style="border:1px solid black;padding: 2px; margin-bottom: 1em">
      <div class="col-md-1" style="text-align: center"> 
      <?= $form->field($model, 'executado_ee')->textInput(['maxlength' => true]) ?> 
    </div>
    <div class="col-md-1" style="text-align: center"> 
      <?= $form->field($model, 'executado_es')->textInput(['maxlength' => true]) ?> 
    </div>
    <div class="col-md-1" style="text-align: center"> 
      <?= $form->field($model, 'executado_ep')->textInput(['maxlength' => true]) ?> 
    </div>
    <div class="col-md-1" style="text-align: center"> 
      <?= $form->field($model, 'executado_ej')->textInput(['maxlength' => true]) ?> 
    </div>
    <div class="col-md-1" style="text-align: center"> 
      <?= $form->field($model, 'executado_tp')->textInput(['maxlength' => true]) ?> 
    </div>
    </div>
	</div>
    <?php } else{ ?>
	    <?php $form = ActiveForm::begin(); ?>
	    <div class="row">
	    <div class="col-md-3">  
	    <?= $form->field($model, 'projeto_id')->dropDownList($listProjetos,['prompt'=>'Selecione um Projeto']) ?>
	    </div>
	    <div class="col-md-2">  
	    <?= $form->field($model, 'contrato')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-md-2"> 
	    <?= $form->field($model, 'objeto')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-md-3"> 
	    <?= $form->field($model, 'contratada')->textInput(['maxlength' => true]) ?>
 		</div>
	    <div class="col-md-2">  
	    <?= $form->field($model, 'cnpj')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-md-3"> 
	    <?= $form->field($model, 'contato')->textInput(['maxlength' => true]) ?>
	    </div>
	    <div class="col-md-1"> 
	    <?= $form->field($model, 'numero_bm')->textInput(['maxlength' => true]) ?>
 		</div>
	    <div class="col-md-2">  
	    <?= $form->field($model, 'data')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
	    </div>
	    <div class="col-md-2"> 
	    <?= $form->field($model, 'de')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
	    </div>
	    <div class="col-md-2"> 
	    <?= $form->field($model, 'para')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '99/99/9999',
                    ]) ?>
	    </div>
	    <div class="col-md-1"> 
	    <?= $form->field($model, 'acumulado')->textInput(['maxlength' => true]) ?>
 		</div>
    <div class="col-md-1"> 
      <?= $form->field($model, 'saldo')->textInput(['maxlength' => true]) ?>
    </div>
	    <div class="col-md-10"> 
	    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>
	    </div>
      <div class="col-md-1"> 
      <?= $form->field($model, 'qtd_dias')->textInput(['maxlength' => true]) ?>
    </div>
      <div class="col-md-1"> 
      <?= $form->field($model, 'km')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-2"> 
      <label>Valor Total</label>
      <input type="text" name="total_bm" value="R$ <?= $valor_total ?>" class="form-control" disabled>
    </div>
    </div>
     EXECUTADO
    <div class="row" style="border:1px solid black;padding: 2px; margin-bottom: 1em">
      <div class="col-md-1" style="text-align: center"> 
        <?php if(!empty(Yii::$app->db->createCommand('SELECT SUM(horas_ee) FROM escopo WHERE projeto_id='.$model->projeto_id)->queryScalar())){ ?>
      <?= $form->field($model, 'executado_ee')->textInput(['maxlength' => true])->label("EE / ".Yii::$app->db->createCommand('SELECT SUM(horas_ee) FROM escopo WHERE projeto_id='.$model->projeto_id)->queryScalar()) ?> 
      <?php } else{ ?>
        <?= $form->field($model, 'executado_ee')->textInput(['maxlength' => true])?>
      <?php  } ?>
    </div>

    <div class="col-md-1" style="text-align: center"> 
      <?php if(!empty(Yii::$app->db->createCommand('SELECT SUM(horas_es) FROM escopo WHERE projeto_id='.$model->projeto_id)->queryScalar())){ ?>
      <?= $form->field($model, 'executado_es')->textInput(['maxlength' => true])->label("ES / ".Yii::$app->db->createCommand('SELECT SUM(horas_es) FROM escopo WHERE projeto_id='.$model->projeto_id)->queryScalar()) ?> 
      <?php } else{ ?>
        <?= $form->field($model, 'executado_es')->textInput(['maxlength' => true])?>
      <?php  } ?>
    </div>

    <div class="col-md-1" style="text-align: center"> 
       <?php if(!empty(Yii::$app->db->createCommand('SELECT SUM(horas_ep) FROM escopo WHERE projeto_id='.$model->projeto_id)->queryScalar())){ ?>
      <?= $form->field($model, 'executado_ep')->textInput(['maxlength' => true])->label("EP / ".Yii::$app->db->createCommand('SELECT SUM(horas_ep) FROM escopo WHERE projeto_id='.$model->projeto_id)->queryScalar()) ?> 
      <?php } else{ ?>
        <?= $form->field($model, 'executado_ep')->textInput(['maxlength' => true])?>
      <?php  } ?>
    </div>

    <div class="col-md-1" style="text-align: center"> 
      <?php if(!empty(Yii::$app->db->createCommand('SELECT SUM(horas_ej) FROM escopo WHERE projeto_id='.$model->projeto_id)->queryScalar())){ ?>
      <?= $form->field($model, 'executado_ej')->textInput(['maxlength' => true])->label("EJ / ".Yii::$app->db->createCommand('SELECT SUM(horas_ej) FROM escopo WHERE projeto_id='.$model->projeto_id)->queryScalar()) ?> 
      <?php } else{ ?>
        <?= $form->field($model, 'executado_ej')->textInput(['maxlength' => true])?>
      <?php  } ?>
    </div>

    <div class="col-md-1" style="text-align: center"> 
      <?php if(!empty(Yii::$app->db->createCommand('SELECT SUM(horas_tp) FROM escopo WHERE projeto_id='.$model->projeto_id)->queryScalar())){ ?>
          <?= $form->field($model, 'executado_tp')->textInput(['maxlength' => true])->label("TP / ".Yii::$app->db->createCommand('SELECT SUM(horas_tp) FROM escopo WHERE projeto_id='.$model->projeto_id)->queryScalar()) ?> 
      <?php } else{ ?>
          <?= $form->field($model, 'executado_tp')->textInput(['maxlength' => true])?> 
      <?php  } ?>

    </div>
    </div>
    <table>
  <tr>
    <th>Executante</th>
    <th>Horas TP</th>
    <th>Horas EJ</th>
    <th>Horas EP</th>
    <th>Horas ES</th>
    <th>Horas EE</th>
  </tr>
  
 <?php 
  $executantes = Yii::$app->db->createCommand('SELECT user.id, nome FROM projeto_executante JOIN user ON projeto_executante.executante_id=user.id WHERE projeto_id='.$model->projeto_id)->queryAll();
 foreach ($executantes as $key => $exec) { ?>
  <tr>
   <td><?= $exec['nome']?></td> 

   <td align="center"><?= empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_tp) h_tp FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$model->id.' AND exe_tp_id='.$exec['id'])->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_tp) h_tp FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$model->id.' AND exe_tp_id='.$exec['id'])->queryScalar();
   ?></td>
  
  <td align="center"><?= empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ej) h_ej FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$model->id.' AND exe_ej_id='.$exec['id'])->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ej) h_ej FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$model->id.' AND exe_ej_id='.$exec['id'])->queryScalar();
   ?></td>

   <td align="center"><?= empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ep) h_ep FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$model->id.' AND exe_ep_id='.$exec['id'])->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ep) h_ep FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$model->id.' AND exe_ep_id='.$exec['id'])->queryScalar();
   ?></td>

   <td align="center"><?= empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_es) h_es FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$model->id.' AND exe_es_id='.$exec['id'])->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_es) h_es FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$model->id.' AND exe_es_id='.$exec['id'])->queryScalar();
   ?></td>

   <td align="center"><?= empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ee) h_ee FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$model->id.' AND exe_ee_id='.$exec['id'])->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ee) h_ee FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$model->id.' AND exe_ee_id='.$exec['id'])->queryScalar();
   ?></td>
  </tr>
 <?php } ?>

</table>
	</div>
   <?php } ?>
    <div class="barra-btn">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-primary', 'style' => 'background-color: #3c8dbc; border-color: #3c8dbc']) ?>
        <?php if(!$model->isNewRecord){ ?>
        <?= Html::a('<span class="btn-label">Visualizar BM</span>', ['gerarbm', 'id' => $model->id], ['class' => 'btn btn-primary', 'target'=>'_blank', 'style'=> ' margin-right: 1em', 'style' => 'background-color: #3c8dbc; border-color: #3c8dbc']) ?>

        <button type="button" class="btn btn-primary" style="background-color: #3c8dbc; border-color: #3c8dbc" data-toggle="modal" data-target="#horasModal">Editar Horas</button>
        
        <button type="button" class="btn btn-info" style="background-color: #3c8dbc; border-color: #3c8dbc" data-toggle="modal" data-target="#emailModal">Email</button>
        <?php } ?>


        
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>

<?php if(!$model->isNewRecord){ ?>

<?php 
  $solicitante = Yii::$app->db->createCommand('SELECT user.nome FROM user JOIN projeto ON projeto.contato_id=user.id WHERE projeto.id='.$model->projeto_id)->queryScalar();

  $projetoNome = Yii::$app->db->createCommand('SELECT nome FROM projeto WHERE id='.$model->projeto_id)->queryScalar();

  $descricaoProjeto = Yii::$app->db->createCommand('SELECT desc_resumida FROM projeto WHERE id='.$model->projeto_id)->queryScalar();

  $emailContato = Yii::$app->db->createCommand('SELECT email FROM projeto WHERE id='.$model->projeto_id)->queryScalar();
  
  $descricao = $model->descricao;

  $dataEmail = date('d') < 15 ? '14/'.date('m/Y') : '25/'.date('m/Y'); 

  $numberBM = $model->num_bm_proj;

  $escopos_bm = Yii::$app->db->createCommand('SELECT escopo_id, bm_id, nome, bm_escopo.horas_ee, bm_escopo.horas_es, bm_escopo.horas_ep, bm_escopo.horas_ej, bm_escopo.horas_tp FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id='.$model->id)->queryAll();
//transforma o numero de decimal para romano
/*  $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $numRoman = '';
    while ($numberBM > 0) {
        foreach ($map as $roman => $int) {
            if($numberBM >= $int) {
                $numberBM -= $int;
                $numRoman .= $roman;
                break;
            }
        }
    }*/

?>

<!-- Modal -->
<div id="emailModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <div  class="col-md-12" align="center">  
            <img style="z-index: 999999999" src="resources/dist/img/loading.gif" type="hidden" name="loading" id="loading" value="" width="64px" hidden/>        
          </div> 
        <h4 class="modal-title">Email</h4>
      </div>

      <div class="modal-body">

        <label>Destinatário(s)</label>
        <input type="text" id="remetente" name="remetente" class="form-control" value="helder010161@uol.com.br ">

        <label>Assunto</label>
        <input type="text" id="assunto" name="assunto" class="form-control" value="HCN - BM <?= $model->numero_bm ?>/<?= Date('Y') ?> - <?= $projetoNome ?> ">
       
        <label>Corpo do Email</label>
        <textarea rows="15" cols="100" id="corpoEmail" name="corpoEmail" class="form-control">         
<?= $emailContato ?> 
Bom dia, <?= explode(" ", $solicitante)[0] ?>!

Segue nosso <?= $numberBM ?>º Boletim de Medição para:
<?= $descricaoProjeto ?>.


Solicitamos sua aprovação para a emissão da FRS, preferencialmente até <?= $dataEmail ?>.




Atenciosamente,

Hélder Câmara
HCN Automação
71 98867-3010 (Vivo)
71 99295-5214 (Tim)
        </textarea>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="close_modal">Fechar</button>
        <button type="button" class="btn btn-success" id="enviarEmail" >Enviar</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="horasModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width: 64%;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <div  class="col-md-12" align="center">  
            <img style="z-index: 999999999" src="resources/dist/img/loading.gif" type="hidden" name="loading" id="loading" value="" width="64px" hidden/>        
          </div> 
        <h4 class="modal-title">Horas BM</h4>
      </div>

      <div class="modal-body">
      <table style="width: 100%;">
        <tr>
          <th>Escopo</th>
          <th>Horas TP</th>
          <th>Horas EJ</th>
          <th>Horas EP</th>
          <th>Horas ES</th>
          <th>Horas EE</th>
        </tr>
        <?php foreach ($escopos_bm as $key => $esc) {   ?>
          <tr>
            <td><?= $esc['nome'] ?></td>
            <td><input type="text" class="horas_bm" name="fname" id="bm-horas_tp-<?= $esc['escopo_id'] ?>" value="<?= $esc['horas_tp'] ?>" style="width: 3em;"></td>
            <td><input type="text" class="horas_bm" name="fname" id="bm-horas_ej-<?= $esc['escopo_id'] ?>" value="<?= $esc['horas_ej'] ?>" style="width: 3em;"></td>
            <td><input type="text" class="horas_bm" name="fname" id="bm-horas_ep-<?= $esc['escopo_id'] ?>" value="<?= $esc['horas_ep'] ?>" style="width: 3em;"></td>
            <td><input type="text" class="horas_bm" name="fname" id="bm-horas_es-<?= $esc['escopo_id'] ?>" value="<?= $esc['horas_es'] ?>" style="width: 3em;"></td>
            <td><input type="text" class="horas_bm" name="fname" id="bm-horas_ee-<?= $esc['escopo_id'] ?>" value="<?= $esc['horas_ee'] ?>" style="width: 3em;"></td>
          </tr>         
        
        <?php } ?>
        
      </table>
     
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="close_modal">Fechar</button>
        <button type="button" class="btn btn-success" id="salvarHoras" >Salvar</button>
      </div>
    </div>

  </div>
</div>
<?php } ?>
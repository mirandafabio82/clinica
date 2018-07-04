<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\tabs\TabsX;
?>

<?php
$this->registerJs('
	$("#bm_btn").click(function(ev){
		file_bm = "";
		console.log("click");
		$.ajax({ 
	      url: "index.php?r=faturamento/readbm",
	      data: {file: file_bm},
	      type: "POST",
	      success: function(response){
	      
	      },
	      error: function(){
	       console.log("failure");
	      }
	  });
	});

	$("#frs_btn").click(function(ev){
		file_frs = "";
		console.log("click");
		$.ajax({ 
	      url: "index.php?r=faturamento/readfrs",
	      data: {file: file_frs},
	      type: "POST",
	      success: function(response){
	      
	      },
	      error: function(){
	       console.log("failure");
	      }
	  });
	});

	$("#nfse_btn").click(function(ev){
		file_nfse = "";
		console.log("click");
		$.ajax({ 
	      url: "index.php?r=faturamento/readnfse",
	      data: {file: file_nfse},
	      type: "POST",
	      success: function(response){
	      
	      },
	      error: function(){
	       console.log("failure");
	      }
	  });
	});
   
');
?>


<div class="box box-primary">
    <div class="box-header with-border">
<div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-calculator"></i> Faturamento </div>

<?php
	$bm_content = '<div style="margin-top:1em">
							<form action="/action_page.php">
								<div class="row">
									<div class="col-md-12"> 
										<label>Arquivo BM</label>
									</div
								</div
								<div class="row">
									<div class="col-md-4"> 
				  						<input type="file" id="bm_file" accept="application/pdf">
				  					</div>
				  					<div class="col-md-4">
				  						<button type="button" class="btn btn-primary" id="bm_btn">Extrair Informações</button>
				  					</div>			  							  					
			  					</div>
			  					<div class="row" style="margin-top:1em">
			  						<div class="col-md-12">
			  							<textarea rows="10" cols="100" id="bm_content"></textarea>
			  						</div>
			  					</div>	
		  					</form>
		  				</div>
	  					';

	$frs_content = '<div style="margin-top:1em">
						<form action="/action_page.php">
							<div class="row">
								<div class="col-md-12"> 
									<label>Arquivo FRS</label>
								</div
							</div
							<div class="row">
								<div class="col-md-4"> 
			  						<input type="file" id="frs_file" accept="application/pdf">
			  					</div>
			  					<div class="col-md-4">
			  						<button type="button" class="btn btn-primary" id="nfse_btn">Extrair Informações</button>
			  					</div>			  							  					
		  					</div>
		  					<div class="row" style="margin-top:1em">
		  						<div class="col-md-12">
		  							<textarea rows="10" cols="100" id="frs_content"></textarea>
		  						</div>
		  					</div>	
	  					</form>
	  				</div>
  					';

	$nfse_content = '<div style="margin-top:1em">
						<form action="/action_page.php">
							<div class="row">
								<div class="col-md-12"> 
									<label>Arquivo NFSe</label>
								</div
							</div
							<div class="row">
								<div class="col-md-4"> 
			  						<input type="file" id="nfse_file" accept="application/pdf">
			  					</div>
			  					<div class="col-md-4">
			  						<button type="button" class="btn btn-primary" id="nfse_btn">Extrair Informações</button>
			  					</div>			  							  					
		  					</div>
		  					<div class="row" style="margin-top:1em">
		  						<div class="col-md-12">
		  							<textarea rows="10" cols="100" id="nfse_content"></textarea>
		  						</div>
		  					</div>	
	  					</form>
	  				</div>
  					';

	$items = [
		[
		    'label'=>'BM',
		    'content'=>$bm_content,
		    'active'=>true,		    
		],
		[
		    'label'=>'FRS',
		    'content'=>$frs_content,
		],
		[
		    'label'=>'NFSe',
		    'content'=>$nfse_content,   
		],		

	]; 
?>

<?php
echo TabsX::widget([
    'items'=>$items,
    'position'=>TabsX::POS_ABOVE,
    'encodeLabels'=>false
]);
 ?>    
</div>
</div>
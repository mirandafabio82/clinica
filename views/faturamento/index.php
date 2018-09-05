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

	$( document ).ready(function() {
    	document.title = "HCN - Faturamento";
    });

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
		
		var fileInput = document.getElementById("frs_file");
		var file = fileInput.files[0];
		var formData = new FormData();
		formData.append("file", file);
		formData.append("frs_num_bm", $("#frs_num_bm").val());
		
		$.ajax({ 
		      url: "index.php?r=faturamento/readfrs",
		      data: formData,
		      type: "POST",
		      cache: false,
	          //dataType: "json",
	          processData: false, // Dont process the files
	          contentType: false,
		      success: function(response){

		      	//se não tem numero de BM na FRS
		      	if(response=="sem_num_bm"){
		      		$("#frs_num_bm_div").removeAttr("hidden");
		      		$("#frs_content").val("Essa FRS não possui número do BM. Favor informar o número do BM no campo acima e tentar novamente clicando em Extrair Informações!");
		      	}
		      	else{
		      		console.log(response);
			      	$("#frs_content").val(response.split("##")[0]);
			      	$("#label_download").attr("href",response.split("##")[1]);
			      	$("#label_download").removeAttr("hidden");			      	
		      	}

		      	
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
						<form id="form_frs" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="col-md-12"> 
									<label>Arquivo FRS</label>
								</div
							</div
							<div class="row">
								<div class="col-md-4"> 
			  						<input type="file" id="frs_file" accept="application/pdf" name="frs_file">
			  					</div>
			  					<div class="col-md-4">
			  						<button type="button" class="btn btn-primary" id="frs_btn">Extrair Informações</button>
			  					</div>
		  					</div>
		  					<div class="col-md-4" style="margin-bottom:1em" id="frs_num_bm_div" hidden>
			  					Número do BM:  
			  					<input type="text" id="frs_num_bm" name="frs_num_bm" style="width:3em">
			  				</div>
			  				<div class="col-md-12" style="margin-bottom:1em">
			  					<a target="_blank" id="label_download" style="color:#00a1ff" hidden>Download do Arquivo</a>
			  				</div>
		  					<div class="row" style="margin-top:1em">
		  						<div class="col-md-12">
		  							<textarea rows="15" cols="100" id="frs_content"></textarea>
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
		],
		[
		    'label'=>'FRS',
		    'content'=>$frs_content,
		    'active'=>true,		    
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
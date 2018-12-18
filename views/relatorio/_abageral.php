<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\tabs\TabsX;
use kartik\select2\Select2;
?>
<class="row">
    <div class="col-md-3">
      
        <?php 
        $form = ActiveForm::begin(); ?> 
      <?= Select2::widget([
            "name" => "projeto",
            "id" => "projeto-id",
            "data" => $listProjetos,
            "options" => [
                "placeholder" => "Projetos",
                "multiple" => false
              ],
          ]); ?>
           <?php ActiveForm::end(); ?>
        
    </div>
</row>
<div class="row">
  <div class="col-md-2">
      <?= Html::a('<span class="btn-label"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Gerar Relatório</span>', ['relatoriogeral'], ['class' => 'btn btn-primary', 'target'=>'_blank']) ?>                     
  </div>
  </div>
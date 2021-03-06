<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\FrsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Frs';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
.table-bordered > tbody > tr > td{
  padding-top: 3px !important;
  padding-bottom: 3px !important;
}

.table-striped > tbody > tr:nth-of-type(odd){
  background-color: #b6b6b6 !important;
}

.summary{
  display: none;
}

.pagination{
    margin: 0px;
}
</style>

<!-- <script>

function delete_frs() {

    var keys = $('#grid').yiiGridView('getSelectedRows');

    for(var i = 0; i < keys.length; i++) {
        
    }
}
</script> -->

    <div class="box box-primary">
        <div class="box-header with-border">
            <div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-bar-chart"></i> FRS </div>
                <div style="margin-top:1em">
                    <?php $form = ActiveForm::begin(); ?>
                        <div class="row">
                            <div class="col-md-12"> 
                                <label>Arquivo FRS</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"> 
                                    <?= $form->field($model, 'id')->fileInput()->label('') ?>
                                </div>
                                <div class="form-group">
                                    <?= Html::submitButton('Carregar Dados', ['class' => 'btn btn-success']) ?>
                                    <?= Html::buttonInput('Excluir Dados', ['class' => 'btn btn-danger', 'id' => 'buttonDelete', 'style' => 'margin-left: 10px']) ?>
                                </div>                                                                
                            </div>                          
                        <?php ActiveForm::end(); ?>
                    </div>

        <div class="frs-index" style="margin-top:1em;height: 50em; overflow-y: scroll;">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'id' => 'grid',
                'filterModel' => $searchModel,
                'options' => ['style' => 'font-size:12px;'],
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn'
                    ],

                    'contrato',
                    'pedido',
                    'frs',
                    'bm',
                    'criador',
                    'data_criacao',
                    'aprovador',
                    'data_aprovacao',
                    'cnpj_emitente',
                    'valor',
                    'nota_fiscal',
                    'referencia',
                    'texto_breve:ntext',

                ],
            ]); ?>
        </div>
    </div>
</div>

<?php
    $this->registerJs('
        $(document).ajaxStart(function() {
            $("#projeto-id").attr("readonly", true);
            $("#loading").show(); // show the gif image when ajax starts
                }).ajaxStop(function() {
                    $("#projeto-id").attr("readonly", false);
            $("#loading").hide(); // hide the gif image when ajax completes
        });
        
        $("#buttonDelete").click(function(){

            var keys = $("#grid").yiiGridView("getSelectedRows");

            for(var i = 0; i < keys.length; i++) {
                $.ajax({ 
                    url: "index.php?r=frs/delete",
                    data: {id: keys[i]},
                    type: "POST",
                    success: function(response){
                    console.log(response);
                    
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(xhr.responseText);
                }
                });
            }
        });
        ');
    ?>
          
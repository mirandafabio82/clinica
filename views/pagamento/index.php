<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PagamentoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pagamentos';
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="box box-primary">
        <div class="box-header with-border">
            <div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-barcode"></i> Pagamento </div>
                <div style="margin-top:1em">
                    <?php $form = ActiveForm::begin(); ?>
                        <div class="row">
                            <div class="col-md-12"> 
                                <label>Arquivo Pagamento</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"> 
                                    <?= $form->field($model, 'id')->fileInput()->label('') ?>
                            </div>
                                <div class="form-group">
                                    <?= Html::submitButton('Carregar Dados', ['class' => 'btn btn-success']) ?>
                                </div>                                                          
                        </div>                          
                        <?php ActiveForm::end(); ?>
                    </div>
                

        <div class="pagamento-index" style="margin-top:1em">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'nota_fiscal',
                    'tipo_documento',
                    'data_emissao',
                    'data_lancamento',
                    'data_pagamento',
                    'valor_bruto',
                    'retencoes',
                    'abatimentos',
                    'valor_liquido',
                    'forma_pagamento',
                    'conta',
                    'documento_contabil',
                    'compensacao',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
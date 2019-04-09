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

    <div class="box box-primary">
        <div class="box-header with-border">
            <div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-bar-chart"></i> FRS </div>
                <div style="margin-top:1em">
                    <form action="/action_page.php">
                        <div class="row">
                            <div class="col-md-12"> 
                                <label>Arquivo FRS</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"> 
                                    <?= $form->field($model, 'id')->fileInput()->label('') ?>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary" id="bm_btn">Carregar Dados</button>
                                </div>                                                          
                            </div>                          
                        </form>
                    </div>

        <div class="frs-index" style="margin-top:1em">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'contrato',
                    'pedido',
                    'frs',
                    'criador',
                    'data_criacao',
                    'aprovador',
                    'data_aprovacao',
                    'cnpj_emitente',
                    'valor',
                    'nota_fiscal',
                    'referencia',
                    'texto_breve:ntext',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$this->title = 'Impressao';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .table-bordered>tbody>tr>td {
        padding-top: 3px !important;
        padding-bottom: 3px !important;
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
        background-color: #b6b6b6 !important;
    }

    .summary {
        display: none;
    }

    .pagination {
        margin: 0px;
    }
</style>

<div class="box box-primary">
    <div class="box-header with-border">
        <div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-bar-chart"></i> Impressão </div>
        <div style="margin-top:1em">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-12">
                    <label>Arquivo FRS</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">

                </div>
                <div class="col-md-4">
                    <?= Html::dropDownList('Impressao[type]', 'inputType', $listStatus, ['class' => 'form-control', 'id' => 'inputType']) ?>
                    <?= Html::a('<span class="btn-label"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Visualizar BM</span>', ['geraratestadocomparecimento'], ['class' => 'btn btn-barra', 'target' => '_blank', 'style' => ' margin-right: 1em']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
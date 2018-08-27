<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\money\MaskMoney;
/* @var $this yii\web\View */
/* @var $model app\models\TipoExecutante */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
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

/*.summary{
  display: none;
}

#w2{
    display: none;
}*/
</style>
<?php
$this->registerJs("

    $('td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
          if(e.target == this)
              location.href = '" . Url::to(['tipoexecutante/update']) . "&id='+id;
        }
    });

");
?>
<!-- mask so funciona com isso -->
<?php $this->head() ?>

<div class="box box-primary">
    <div class="box-header with-border">

<div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-vcard"></i> Especialidades </div>
<div style="margin-bottom:1em;margin-top: 1em">
    <?= Html::a('Mostrar Todos', ['/tipoexecutante/create', 'pagination' => true], ['class'=>'btn btn-primary grid-button']) ?>
</div>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'options' => ['style' => 'font-size:12px;'],
        'rowOptions' => function ($model, $key, $index, $grid) {
                    return [
                        'style' => "cursor: pointer",
                        
                    ];
                },
        // 'pjax' => true,
        
        // 'hover' => true,
        /*'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-vcard"></i> Especialidades'
        ],*/
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            // 'id',
            'cargo',
            'codigo',
            [
            'attribute' => 'valor_hora',   
            'format' => 'raw',
            'contentOptions' => ['style' => 'width:10em;'],
            'value' => function ($data) {
               
              return str_replace('.',',',sprintf("%.2f",$data->valor_hora));

            },
            ],
            [
            'attribute' => 'valor_pago',   
            'format' => 'raw',
            'contentOptions' => ['style' => 'width:10em;'],
            'value' => function ($data) {
               
              return str_replace('.',',',sprintf("%.2f",$data->valor_pago));

            },
            ],

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>

<div class="tipo-executante-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
        <div class="box-header with-border">
    <div class="row">
        <div class="col-md-2">
    <?= $form->field($model, 'cargo')->textInput(['maxlength' => true]) ?>
    </div>
        <div class="col-md-2">
    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>
    </div>
        <div class="col-md-2">
    <?= $form->field($model, 'valor_hora')->textInput(['maxlength' => true])->widget(MaskMoney::classname(), [
          'pluginOptions' => [
              'prefix' => 'R$ ',
              'thousands' => '.',
              'decimal' => ',',
              // 'suffix' => ' ¢',
              'allowNegative' => false

          ]
      ]); ?>
</div>
        <div class="col-md-2">
    <?= $form->field($model, 'valor_pago')->textInput(['maxlength' => true])->widget(MaskMoney::classname(), [
          'pluginOptions' => [
              'prefix' => 'R$ ',
              'thousands' => '.',
              'decimal' => ',',
              // 'suffix' => ' ¢',
              'allowNegative' => false

          ]
      ]); ?>
</div>
</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Salvar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

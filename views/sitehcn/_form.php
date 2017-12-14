<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\Site */
/* @var $form yii\widgets\ActiveForm */
?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'toolbar' =>  [
        ['content' => Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['class' => 'btn btn-success'])
        ],
          '{export}',
          '{toggleData}',
        ],
        'export' => [
          'fontAwesome' => true
        ],
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-tasks"></i> Sites'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{update} {delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            'id',
            'nome',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<div class="site-form">
<div class="box box-primary">
    <div class="box-header with-border">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
   </div>
</div>

</div>

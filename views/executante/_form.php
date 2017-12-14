<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\Executante */
/* @var $form yii\widgets\ActiveForm */
?>

<!-- mask so funciona com isso -->
<?php $this->head() ?>

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
            'heading' => '<i class="fa fa-wrench"></i> Executantes'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{update} {delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
            ],
            'usuario_id',
            [
              'header' => 'Nome',              
              'format' => 'raw',
               'value' => function ($data) {

                   return Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.$data->usuario_id)->queryScalar();
               },
            ],
            [
              'header' => 'Tipo',              
              'format' => 'raw',
               'value' => function ($data) {
                  $executantes = Yii::$app->db->createCommand('SELECT cargo FROM tipo_executante JOIN executante_tipo ON tipo_executante.id=executante_tipo.tipo_id WHERE executante_tipo.executante_id='.$data->usuario_id)->queryAll();
                  $tipos = '';
                  foreach ($executantes as $key => $exec) {
                    $tipos .= $exec['cargo'].'; ';
                  }

                   return $tipos;
               },
            ],
            'cidade',            
            // 'cpf',
            [
              'header' => 'Email',              
              'format' => 'raw',
               'value' => function ($data) {

                   return Yii::$app->db->createCommand('SELECT email FROM user WHERE id='.$data->usuario_id)->queryScalar();
               },
            ],
            'telefone',
            'celular',
            'criado',
            // 'modificado',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

<div class="executante-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
        <div class="box-header with-border">
    <div class="row">       
    <div class="col-md-6">


    <div id="tipoExecutantes">
    <label>Funções</label>
    <br>
     <?php     
        foreach ($listTipos as $key => $tipo) { ?>
           <input type="checkbox" name="Tipos[<?=$key?>]" value="<?= $key?>"><?= $tipo ?>
        <?php } ?>
        
    </div>

    <?= $form->field($user, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uf')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'cidade')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'endereco')->textInput(['maxlength' => true]) ?>

    </div>
    <div class="col-md-6">

    <?= $form->field($model, 'cpf')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '999.999.999-99',
                    ]) ?>
    <?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefone')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '(99) 9999-9999',
                    ]) ?>

    <?= $form->field($model, 'celular')->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '(99) 99999-9999',
                    ]) ?>
    </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

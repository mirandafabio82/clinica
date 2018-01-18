<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\LdPreliminarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ld Preliminars';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ld-preliminar-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ld Preliminar', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box box-primary">
    <div class="box-header with-border">
      <div class="row">   
<div class="col-md-12"> 
          <div class="col-md-12">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,        
            'options' => ['style' => 'font-size:12px;'],                
            'hover' => true,            
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'hcn',
            'cliente',
            'titulo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
</div>
</div>
</div>
</div>
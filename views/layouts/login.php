<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<style>
body { 
    background-image: url('resources/dist/img/login_background.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center; 
}
</style>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?=Yii::$app->charset?>">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?=Html::csrfMetaTags()?>
        <title><?=Html::encode(Yii::$app->name)?></title>

        <?php $this->head()?>
        <!-- <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/img/favicon.ico" type="image/x-icon" /> -->

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <!-- <body background="resources/dist/img/login_background.jpg"> -->
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">
            <div class="container">    
            <h1 style="font-weight: 700;text-align: center; color:#fff">HCN Automação</h1>
                <?= $content ?>
               <!--  <a href="https://br.freepik.com/fotos-gratis/o-inspetor-financeiro-e-o-secretario-fazem-relatorio-calculam-ou-verificam-o-saldo-documento-de-verificacao-do-inspetor-do-servico-de-receita-federal-conceito-de-auditoria_1202418.htm">Projetado pelo Freepik</a> -->
            </div>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

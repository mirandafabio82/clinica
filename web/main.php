<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerJs('
  $("#logout-btn").click(function(){
    console.log("aa");
    $("#logout-form").submit();
  });

');

$usuario = Yii::$app->db->createCommand('SELECT * FROM user WHERE id='.Yii::$app->user->getId())->queryOne(); 
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>HCN</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="plugins/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="plugins/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="resources/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
  folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="resources/dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
  <?php $this->beginBody() ?>

  <div class="wrap">
    <header class="main-header">
      <!-- Logo -->
      <a href="plugins/index2.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>HCN</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>HCN</b>Automação</span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->



            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- <img src="resources/dist/img/user2-160x160.jpg" class="user-image" alt="User Image"> -->
                <span class="hidden-xs"><?= $usuario['email'] ?>

                </span>
              </a>
              <ul class="dropdown-menu">               
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <?php
                  echo Html::beginForm(Url::base().'/index.php?r=site/logout', 'post');
                  echo Html::submitButton('Sair', ['class' => 'btn btn-default btn-flat']);
                  echo Html::endForm();
                  ?>
                </div>
              </li>
            </ul>
          </li>          
        </ul>
      </div>
    </nav>
  </header>

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="resources/dist/img/logo_hcn.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Olá, <?= split(' ',$usuario['nome'])[0]?></p>
          <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
        </div>
      </div>
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU PRINCIPAL</li>
        
        <li><a href="<?= Url::to(['agenda/index']) ?>"><i class="fa fa-calendar"></i> <span>Agenda</span></a></li>
        <li><a href="<?= Url::to(['cliente/index']) ?>"><i class="fa fa-handshake-o"></i> <span>Clientes</span></a></li>
        <li><a href="<?= Url::to(['contato/index']) ?>"><i class="fa fa-address-book"></i> <span>Contatos</span></a></li>
        <li><a href="<?= Url::to(['executante/index']) ?>"><i class="fa fa-wrench"></i> <span>Executantes</span></a></li>
        <li><a href="<?= Url::to(['tipoexecutante/index']) ?>"><i class="fa fa-vcard"></i> <span>Tipos de Executantes</span></a></li>
        <li><a href="<?= Url::to(['projeto/index']) ?>"><i class="fa fa-folder-open"></i> <span>Projetos</span></a></li>
        <li><a href="<?= Url::to(['atividade/index']) ?>"><i class="fa fa-hourglass-half"></i> <span>Atividades</span></a></li>        
        <!-- <li><a href="<?//= Url::to(['escopo/index']) ?>"><i class="fa fa-university"></i> <span>Escopo</span></a></li> -->
        <li><a href="<?= Url::to(['sitehcn/index']) ?>"><i class="fa fa-tasks"></i> <span>Site</span></a></li>
        <li><a href="<?= Url::to(['planta/index']) ?>"><i class="fa fa-map"></i> <span>Planta</span></a></li>
        <!-- <li><a href="<?//= Url::to(['documento/index']) ?>"><i class="fa fa-file"></i> <span>Documentos</span></a></li> -->
        <li><a href="<?= Url::to(['config/index']) ?>"><i class="fa fa-cog"></i> <span>Configurações</span></a></li>
         <?php
                  /*echo Html::beginForm(Url::base().'/index.php?r=site/logout', 'post');
                  echo Html::submitButton('<i class="fa fa-sign-out"></i> Sair', ['class' => 'btn btn-default btn-flat']);
                  echo Html::endForm();*/
                  ?>
        <!-- <li><a href="#" id="logout-btn">
          <form action="/hcn/web/index.php?r=site/logout" method="post" id="logout-form">
          <input type="hidden" name="_csrf" value="Zwn6I2VPoAByPuAdYypoNsW3T1cgQAYa4ik2F6OhrxkkUL5VIB_OMiINrl80HTJm8uF4IXokaEzRRX4v8pXbIA==">
                 <i class="fa fa-sign-out"></i> <span> Sair</span>
        </form>
      </a></li> -->
        
      </ul>
    </li>

  </ul>
</section>
<!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <!-- Main content -->
  <section class="container-fluid" style="padding-top: 20px;">
    <?= $content ?>      
  </section>
  <!-- /.content -->

</div>

<footer class="footer">
  <div class="container">
    <p class="pull-left">Todos os direitos reservados &copy; <span style="color: #3c8dbc">HCN </span><?= date('Y') ?></p>

    <p class="pull-right">Desenvolvido por <span style="color: #3c8dbc">F-Sistemas </span></p>
  </div>
</footer>
</div>

<!-- jQuery 3 -->
<script src="plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="resources/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="resources/dist/js/demo.js"></script> -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

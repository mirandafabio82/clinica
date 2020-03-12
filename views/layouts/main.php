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

$usuario = Yii::$app->db->createCommand('SELECT * FROM user WHERE id=' . Yii::$app->user->getId())->queryOne();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>VITALSorrir</title>
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

  <link rel="icon" href="resources/dist/img/logo_vitalSorrir.png">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
  folder instead of downloading all of them to reduce the load. -->
  <!-- <link rel="stylesheet" href="resources/dist/css/skins/_all-skins.min.css"> -->
  <link rel="stylesheet" href="css/site.css">
  <!-- fullCalendar -->
  <link rel="stylesheet" href="plugins/fullcalendar/dist/fullcalendar.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar/dist/fullcalendar.print.min.css" media="print">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!--Required scripts-->
  <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <!-- External files for exporting -->
  <script src="https://www.igniteui.com/js/external/FileSaver.js"></script>
  <script src="https://www.igniteui.com/js/external/Blob.js"></script>

  <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/infragistics.core.js"></script>

  <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_core.js"></script>
  <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_collections.js"></script>
  <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_text.js"></script>
  <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_io.js"></script>
  <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_ui.js"></script>
  <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.documents.core_core.js"></script>
  <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_collectionsextended.js"></script>
  <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.excel_core.js"></script>
  <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_threading.js"></script>
  <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_web.js"></script>
  <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.xml.js"></script>
  <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.documents.core_openxml.js"></script>
  <script type="text/javascript" src="https://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.excel_serialization_openxml.js"></script>
</head>

<body class="hold-transition skin-blue fixed sidebar-mini">
  <?php $this->beginBody() ?>

  <style>
    @import "https://fonts.googleapis.com/css?family=Righteous:300,400,500,600,700";


    .sidebar-form,
    .sidebar-menu>li.header {
      background-color: #00BED8;
      color: #005154;
      border-radius: 15px;
      text-align: center;
      font-family: 'Righteous', sans-serif;
      font-size: 1.1em;
      font-weight: 300;
      line-height: 1.7em;
    }

    #sidebar {
      min-width: 250px;
      max-width: 250px;
      background-color: #d3e0e0;
      font-family: 'Righteous', sans-serif;
      color: #def4f5;
      transition: all 0.3s;
    }

    #sidebar.active {
      min-width: 80px;
      max-width: 80px;
      text-align: center;
    }

    #sidebar.active .sidebar-header h3,
    #sidebar.active .CTAs {
      display: none;
    }

    #sidebar.active .sidebar-header strong {
      display: block;
    }

    #sidebar ul li a {
      text-align: left;
    }

    #sidebar.active ul li a {
      padding: 20px 10px;
      text-align: center;
      font-size: 0.85em;
    }

    #sidebar.active ul li a i {
      margin-right: 0;
      display: block;
      font-size: 1.8em;
      margin-bottom: 5px;
    }

    #sidebar.active ul ul a {
      padding: 10px !important;
    }

    #sidebar.active .dropdown-toggle::after {
      top: auto;
      bottom: 10px;
      right: 50%;
      -webkit-transform: translateX(50%);
      -ms-transform: translateX(50%);
      transform: translateX(50%);
    }

    #sidebar .sidebar-header {
      padding: 20px;
      background: #fff1fe;
    }

    #sidebar .sidebar-header strong {
      display: none;
      font-size: 1.8em;
    }

    #sidebar ul.components {
      padding: 20px 0;
      border-bottom: 1px solid #FFFFFF;
    }

    #sidebar ul li a {
      padding: 10px;
      font-size: 1.1em;
      color: #718794;
      display: block;
    }

    #sidebar ul li a:hover {
      text-decoration: none;
      background: #80b7b7;
    }

    #sidebar ul li a i {
      margin-right: 10px;
    }

    #sidebar ul li.active>a,
    a[aria-expanded="true"] {
      color: #def4f5;
      background: #80b7b7;
    }

    a[data-toggle="collapse"] {
      position: relative;
    }

    .dropdown-toggle::after {
      display: block;
      position: absolute;
      top: 50%;
      right: 20px;
      transform: translateY(-50%);
    }

    ul ul a {
      font-size: 0.9em !important;
      padding-left: 30px !important;
      background: e1e4e4;
    }

    ul.CTAs {
      padding: 20px;
    }

    ul.CTAs a {
      text-align: center;
      font-size: 0.9em !important;
      display: block;
      border-radius: 5px;
      margin-bottom: 5px;
    }

    a.download {
      background: #fff;
      color: #ffc9fb;
    }

    a.article,
    a.article:hover {
      background: #6d7fcc !important;
      color: #fff !important;
    }
  </style>

  <div class="wrap">

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar" style="padding-top: 0px; background-color: #e1e4e4;">
      <!-- sidebar: style can be found in sidebar.less -->
      <section id="sidebar" class="sidebar">
        <!-- Sidebar user panel -->
        <a href="<?= Url::home() ?>">
          <div class="user-panel">
            <div class="pull-left image">
              <img src="resources/dist/img/logo_vitalsorrir_menu.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info" style="color: #3c8dbc">
              <p>Olá, <?= split(' ', $usuario['nome'])[0] ?></p>
              <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
            </div>
          </div>
        </a>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree" style=" color: #def4f5">
          <li class="header">MENU PRINCIPAL</li>

          <li><a href="<?= Url::to(['agenda/create']) ?>"><i class="fa fa-calendar"></i> <span>Agenda</span></a></li>
          <!-- <li><a href="<?= Url::to(['projeto/create']) ?>"><i class="fa fa-folder-open"></i> <span>Projetos</span></a></li> -->

          <!-- <li><a href="<?= Url::to(['bm/create']) ?>"><i class="fa fa-list"></i> <span>BM</span></a></li>
          <li><a href="<?= Url::to(['faturamento/index']) ?>"><i class="fa fa-calculator"></i> <span>Faturamento</span></a></li>
          <li><a href="<?= Url::to(['cliente/create']) ?>"><i class="fa fa-handshake-o"></i> <span>Clientes</span></a></li>
          <li><a href="<?= Url::to(['contato/create']) ?>"><i class="fa fa-address-book"></i> <span>Contatos</span></a></li>
          <li><a href="<?= Url::to(['tarefa/index']) ?>"><i class="fa fa-calendar"></i> <span>Gerenciamento das Atividades</span></a></li>
          <li><a href="<?= Url::to(['atividademodelo/create']) ?>"><i class="fa fa-tablet"></i> <span>Atividades Modelo</span></a></li> -->
          <!-- <li><a href="<?= Url::to(['paciente/create']) ?>"><i class="fa fa-user"></i> <span>Pacientes</span></a></li> -->

          <li><a href="<?= Url::to(['paciente/create']) ?>"><i class="fa fa-user"></i> <span>Paciente</span></a></li>

          <!-- <li class="treeview">
            <a href="#">
              <i class="fa fa-user"></i> <span>Pacientes</span>
              <span class="pull-right-container" style="margin-right: 20px;">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu" style="display: none;">
              <li><a href="<?= Url::to(['paciente/create']) ?>"><i class="fa fa-user-plus"></i> Criar / Listar </a></li>

              <li><a href="<?= Url::to(['paciente/profile']) ?>"><i class="fa fa-vcard"></i> Histórico </a></li>
            </ul>
          </li> -->

          <li class="treeview">
            <a href="#">
              <i class="fa fa-address-book"></i> <span>Tratamento</span>
              <span class="pull-right-container" style="margin-right: 20px;">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu" style="display: none;">
              <li><a href="<?= Url::to(['tratamento-realizado/index']) ?>"><i class="fa fa-bar-chart"></i> Tratamento realizado </a></li>

              <li><a href="<?= Url::to(['tratamento-planejamento/index']) ?>"><i class="fa fa-vcard"></i>Planejamento</a></li>
            </ul>
          </li>

          <li><a href="<?= Url::to(['documento/create']) ?>"><i class="fa fa-file"></i> <span>Documentos</span></a></li>

          <!-- <li><a href="<?= Url::to(['impressao/geraratestadocomparecimento']) ?>" target="_blank"><i class="fa fa-print"></i> <span>Impressão</span></a></li> -->

          <li class="treeview">
            <a href="#">
              <i class="fa fa-print"></i> <span>Impressão</span>
              <span class="pull-right-container" style="margin-right: 20px;">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu" style="display: none;">
              <li><a href="<?= Url::to(['impressao/gerarfileanaminese']) ?>" target="_blank"><i class="fa fa-file"></i> Anaminese </a></li>
              <li><a href="<?= Url::to(['impressao/gerarfileatestado']) ?> " target="_blank"><i class="fa fa-file"></i>Atestado</a></li>
              <li><a href="<?= Url::to(['impressao/gerarfilereceituario']) ?> " target="_blank"><i class="fa fa-file"></i>Receituario</a></li>
            </ul>
          </li>

          <!-- <li><a href="<?= Url::to(['executante/create']) ?>"><i class="fa fa-user"></i> <span>Executantes</span></a></li> --
          
            <li><a href="<?= Url::to(['tipoexecutante/create']) ?>"><i class="fa fa-vcard"></i> <span>Especialidades</span></a></li

          
            <li><a href="<?= Url::to(['relatorio/index']) ?>"><i class="fa fa-file-pdf-o"></i> <span>Relatórios</span></a></li>

            
 <li><a href="<? //= Url::to(['config/create']) 
              ?>"><i class="fa fa-cog"></i> <span>Configurações</span></a></li> -->
          <li><a>
              <?php
              echo Html::beginForm(Url::base() . '/index.php?r=site/logout', 'post');
              echo Html::submitButton('<li><i class="fa fa-sign-out"></i> Sair<li>', ['class' => 'btn-flat', 'style' => 'border: 0 !important; background: none !important; -webkit-appearance: none !important;']);
              echo Html::endForm();
              ?>
            </a></li>
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
    <div class="content-wrapper" style="padding-top: 0px;">
      <!-- Main content -->
      <section class="container-fluid" style="padding-top: 20px; ">
        <?= $content ?>
      </section>
      <!-- /.content -->

    </div>
    <!-- 
<footer class="footer">
  <div class="container">
    <p class="pull-left">Todos os direitos reservados &copy; <span style="color: #3c8dbc">HCN </span><?= date('Y') ?></p>

    <p class="pull-right">Desenvolvido por <span style="color: #3c8dbc">F-Sistemas </span></p>
  </div>
</footer> -->
    </a>

    <!-- jQuery 3 -->
    <script src="plugins/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="plugins/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- SlimScroll -->
    <script src="plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/lib/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="resources/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="resources/dist/js/demo.js"></script> -->
    <script src="resources/dist/js/chart.js/Chart.js"></script>
    <!-- FullCalendar -->
    <script src="plugins/moment/moment.js"></script>
    <script src="plugins/fullcalendar/dist/fullcalendar.min.js"></script>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
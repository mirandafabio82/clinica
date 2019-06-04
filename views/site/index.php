<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\money\MaskMoney;
use kartik\tabs\TabsX;
use kartik\popover\PopoverX;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */

$this->title = 'HCN Automação';
?>

<style>
  table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
      font-size: 8px;
  }

  td, th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
      font-size: 10px;
  }

  tr:nth-child(even) {
      /*background-color: #dddddd;*/
  }

  div.scrollmenu {
      overflow: auto;
      white-space: nowrap;
  }

  div.scrollmenu a {
      display: inline-block;
      color: white;
      text-align: center;
      padding: 14px;
      text-decoration: none;
  }

  th {
      background-color: #3c8dbc;
      color: white;
  }
  td { white-space:pre }
</style>

<?php
  $eventos = '';
  foreach ($arrayEventos as $key => $evt) {
          $cor = Yii::$app->db->createCommand('SELECT cor FROM executante JOIN user ON user.id = executante.usuario_id WHERE nome="'.$evt['responsavel'].'"')->queryScalar();
          $eventos .= "{
                        id             : ".$evt['id'].",
                        title          : '".$evt['assunto']."',
                        start          : '".$evt['hr_inicio']."',
                        end            : '".$evt['hr_final']."',
                        backgroundColor: '".$cor."', 
                        borderColor    : '".$cor."'
                      },";
        }     

    ?>

<?php if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin'])){ ?>
<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?= $numBm -1 ?></h3>

              <p>Nº do último BM</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $concluido ?></h3>

              <p>Projetos Concluídos</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= $aguardando ?></h3>

              <p>Projetos Aguardando Aprovação</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?= $emitirAS ?></h3>

              <p>Projetos Aguardando Emitir AS</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
      </div>
<?php } ?>
     <div class="row">
      <!-- /.col -->
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
                  <?= yii2fullcalendar\yii2fullcalendar::widget([
                        'options' => [
                          'lang' => 'pt',
                          'hidden' => 'hidden',
                          //... more options to be defined here!
                        ],
                        'events' => Url::to(['/timetrack/default/jsoncalendar'])
                      ]);
                  ?>
                  <div id="calendar"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      
      
<?php if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin'])){ ?>
      

      <div class="col-md-6">

      	 <!-- PRODUCT LIST -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Últimas Atividades</h3>

              <!-- <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
              	<?php foreach ($logs as $key => $log) { ?> 
	                <li class="item">	                  
	                  <div class="product-info">
	                    <a href="javascript:void(0)" class="product-description"><?= $log['descricao'] ?><span
	                        class="label label-info pull-right"><?= date_format(DateTime::createFromFormat('Y-m-d H:i:s', $log['data']), 'd/m/Y H:i:s') ?></span></a>                 
	                    
	                  </div>
	                </li>
                <?php } ?>
                <!-- /.item -->
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="<?= Url::to(['log/index']) ?>" class="uppercase">Visualizar o Log Completo</a>
            </div>
            <!-- /.box-footer -->
          </div>      
      </div>
  <div class="col-md-6">

         <!-- PRODUCT LIST -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Pagamentos Previstos</h3>              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <?php 
                  if(!empty($pagamentos_dia)){
                  foreach ($pagamentos_dia as $key => $pgt) { ?> 

                  <li class="item">                   
                    <div class="product-info">
                      <a href="<?= Url::to(['bm/update','id'=> $pgt['bm_id']]) ?>" class="product-description">Existe pagamento previsto para <?= Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.$pgt['executante_id'])->queryScalar()  ?> referente ao BM Nº <?= Yii::$app->db->createCommand('SELECT numero_bm FROM bm WHERE id='.$pgt['bm_id'])->queryScalar()  ?><span
                          class="label label-info pull-right"><?= date_format(DateTime::createFromFormat('Y-m-d', $pgt['previsao_pgt']), 'd/m/Y') ?></span></a>                 
                      
                    </div>
                  </li>
                <?php }} else{ ?>
                        <div class="product-info">
                      <a href="javascript:void(0)" class="product-description">Não existe pagamento a ser realizado</a>                 
                      
                    </div>
                <?php } ?>
                <!-- /.item -->
              </ul>
            </div>
            
          </div>      
      </div>

<?php } ?>


<div class="col-md-12">

<!-- PRODUCT LIST -->
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Relatório Geral</h3>    
    <form action="/web/index.php?r=relatorio%2Frelatoriogeral" method="post" id="form-relgeral">
            <div class="row">
              <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-left:1em" id="autocomplete_div_0">
                <label>Projeto</label>
                <input class="np_autocomplete form-control" id="projeto" type="text" name="projeto" placeholder="Insira um Projeto"> 
              </div>
              <div class="autocomplete col-md-3" style="width:300px;padding: 0; margin-left:1em" id="autocomplete_div_0">
                <label>Contato</label>
                <input class="np_autocomplete form-control" id="contato" type="text" name="contato" placeholder="Insira um Projeto"> 
              </div>
           
            </div>
            <div class="row" style="margin-top:1em">                            
              <div class="col-md-2">
                Período BM
                <input type="date" name="bm_de" class="form-control" placeholder="de">                           
                <input type="date" name="bm_ate" class="form-control" placeholder="até">
              </div>
              <div class="col-md-2">
                Período AS
                <input type="date" name="as_de" class="form-control" placeholder="de">                            
                <input type="date" name="as_ate" class="form-control" placeholder="até">
              </div>
              <div class="col-md-2">
                Período FRS
                <input type="date" name="frs_de" class="form-control" placeholder="de">                            
                <input type="date" name="frs_ate" class="form-control" placeholder="até">
              </div>
            </div>
            <div class="row" style="margin-top:1em">
              <div class="col-md-1">
                <input type="checkbox" name="bm" checked> Com BM
              </div>
              <div class="col-md-1">
                <input type="checkbox" name="frs" checked> Com FRS
              </div>
              <div class="col-md-1">
                <input type="checkbox" name="valor" checked> Mostrar Valores
              </div>
          </div>
          <div class="row" style="margin-top:1em">
            <div class="col-md-2">
              <button class="btn btn-primary" type="submit" form="form-relgeral" value="Submit">Gerar Relatório</button>
            </div>
          </div>
        </form>
        
    </div>            
  </div>      
</div>        








<script>
  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        /*$(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })*/

      })
    }

    init_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
    $('#calendar').fullCalendar({
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'hoje',
        month: 'mês',
        week : 'semana',
        day  : 'dia'
      },
      //Random default events
      events    : [ <?=$eventos?> ],
      editable  : false, // this allows things to be dropped onto the calendar !!!
    
    })

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    
  
  })
</script>

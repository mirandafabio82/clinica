<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\models\Documento;
use \Datetime;

/**
 * TarefaController implements the CRUD actions for Tarefa model.
 */
class RelatorioController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Tarefa models.
     * @return mixed
     */
    public function actionIndex()
    {       

       $prestadores = Yii::$app->db->createCommand('SELECT * FROM executante JOIN user ON user.id=executante.usuario_id WHERE is_prestador=1')->queryAll();
       $listPrestadores = ArrayHelper::map($prestadores,'id','nome');

       $projetos = Yii::$app->db->createCommand('SELECT * FROM projeto')->queryAll();
       $listProjetos = ArrayHelper::map($projetos,'id','nome');

       $contato = Yii::$app->db->createCommand('SELECT * FROM contato JOIN user ON user.id = contato.usuario_id')->queryAll();
       $listContatos = ArrayHelper::map($contato,'id','nome');
       

        return $this->render('index', [
            'listPrestadores' => $listPrestadores,
            'listProjetos' => $listProjetos,
            'listContatos' =>$listContatos
        ]);
    }

    /**
     * Displays a single Tarefa model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tarefa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
    }

    /**
     * Updates an existing Tarefa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
    }

    /**
     * Deletes an existing Tarefa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        
    }

    //habilitar ajax
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionTabelaextrato(){  
        if (Yii::$app->request->isAjax) {                 
            $executante_id = Yii::$app->request->post()['executante_id'];

            $exibePagos = '';
            if(Yii::$app->request->post()['check_extrato']== 'false'){
              $exibePagos = ' AND bm_executante.data_pgt IS NULL ';
            }

            $total = Yii::$app->db->createCommand('SELECT bm.id, projeto.nome, bm.numero_bm, projeto.descricao, bm.executado_ee, bm.executado_es, bm.executado_ep, bm.executado_ej, bm.executado_tp, executante.vl_hh_tp, executante.vl_hh_ej, executante.vl_hh_ep, executante.vl_hh_es, executante.vl_hh_ee, bm_executante.data_pgt FROM bm JOIN projeto ON bm.projeto_id=projeto.id JOIN projeto_executante ON projeto_executante.projeto_id=projeto.id JOIN executante ON executante.usuario_id=projeto_executante.executante_id JOIN bm_executante ON bm.id=bm_executante.bm_id  WHERE projeto_executante.executante_id='.$executante_id.' AND bm_executante.executante_id='.$executante_id.' '.$exibePagos)->queryAll();

            foreach ($total as $key => $tot) { 


              $horas_exe_ee = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ee) h_ee FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_ee_id='.$executante_id)->queryScalar()) ? '' : number_format(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ee) h_ee FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_ee_id='.$executante_id)->queryScalar(), 1, '.', '.');
              $horas_exe_es =empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_es) h_es FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_es_id='.$executante_id)->queryScalar()) ? '' : number_format(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_es) h_es FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_es_id='.$executante_id)->queryScalar(), 1, '.', '.');
              $horas_exe_ep = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ep) h_ep FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_ep_id='.$executante_id)->queryScalar()) ? '' : number_format(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ep) h_ep FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_ep_id='.$executante_id)->queryScalar(), 1, '.', '.');
              $horas_exe_ej = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ej) h_ej FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_ej_id='.$executante_id)->queryScalar()) ? '' : number_format(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ej) h_ej FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_ej_id='.$executante_id)->queryScalar(), 1, '.', '.');
              $horas_exe_tp = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_tp) h_tp FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_tp_id='.$executante_id)->queryScalar()) ? '' : number_format(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_tp) h_tp FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_tp_id='.$executante_id)->queryScalar(), 1, '.', '.');

              if($horas_exe_tp == 0.00) $horas_exe_tp = '';
              if($horas_exe_ej == 0.00) $horas_exe_ej = '';
              if($horas_exe_ep == 0.00) $horas_exe_ep = '';
              if($horas_exe_es == 0.00) $horas_exe_es = '';
              if($horas_exe_ee == 0.00) $horas_exe_ee = '';

                $hora = ['numero_bm' => $tot['numero_bm'], 'executado_ee' => $horas_exe_ee, 'executado_es' => $horas_exe_es, 'executado_ep' => $horas_exe_ep, 'executado_ej' => $horas_exe_ej, 'executado_tp' => $horas_exe_tp];
                array_push($total, $hora);
            }


            return json_encode($total);

        }        
    }

    public function actionRelatorioextrato(){  
        
        $bms = explode('-', $_GET['bms']);
        $executante_id = $_GET['executante_id'];

        $executante = Yii::$app->db->createCommand('SELECT * FROM executante JOIN user ON executante.usuario_id=user.id WHERE executante.usuario_id='.$executante_id)->queryOne();

        $valor_pago = Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante')->queryAll();

        $executante_extrato_page = $this->renderPartial('_extrato_executante', [
            'bms' => $bms,    
            'prestador' => $executante,       
            'date' => date('d/m/Y'),
            'valor_pago' => $valor_pago      
        ]);

        if (!file_exists('uploaded-files/outros')) {
            mkdir('uploaded-files/outros', 0777, true);
        }

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($executante_extrato_page);   
        $mpdf->Output('uploaded-files/outros/Fatura_'.$executante['nome_empresa'].'_'.date('Y-m-d').'.pdf', 'F'); 
        $mpdf->Output('uploaded-files/outros/Extrato-temp.pdf', 'I');  

        $existsFile = Yii::$app->db->createCommand('SELECT id FROM documento WHERE nome="Fatura_'.$executante['nome_empresa'].'_'.date('Y-m-d').'.pdf"')->queryScalar();

            if(empty($existsFile)){                          
                //cria o registro do arquivo
                $doc = new Documento();
                $doc->nome = 'Fatura_'.$executante['nome_empresa'].'_'.date('Y-m-d').'.pdf';
                $doc->revisao = 0;
                $doc->path = 'Fatura_'.$executante['nome_empresa'].'_'.date('Y-m-d').'.pdf';
                $doc->is_global = 0;
                $doc->data = date('Y-m-d');
                if(!$doc->save()){
                    print_r($doc->getErrors());
                    die();
                }
            }
    }

    public function actionRelatoriogeral(){  

       
            if(isset($_POST['contato'])){
                $contatos = Yii::$app->db->createCommand('SELECT usuario_id FROM contato WHERE usuario_id IN ('.implode(',', $_POST['contato']).') ORDER BY usuario_id DESC')->queryAll();
            }
            else{
                $contatos = Yii::$app->db->createCommand('SELECT usuario_id FROM contato ORDER BY usuario_id DESC')->queryAll();
            }
            
            $conts = '';            
            foreach ($contatos as $key => $contato) { 
                if(sizeof($contatos)-1 == 0){
                    $conts .= $contato['usuario_id'];
                    continue;
                }     
                if(empty($contato['usuario_id']) || sizeof($contatos)-1 == $key )      {
                    $conts = substr($conts, 0, -1);
                    continue;
                }       
                $conts .= $contato['usuario_id'].','; 
            }

            /*if(isset($_POST['executante'])){
                $executantes = Yii::$app->db->createCommand('SELECT DISTINCT executante_id FROM projeto_executante WHERE executante_id IN ('.implode(',', $_POST['executante']).') ORDER BY executante_id DESC')->queryAll();
            }
            else{
                $executantes = Yii::$app->db->createCommand('SELECT DISTINCT executante_id FROM projeto_executante ORDER BY executante_id DESC')->queryAll();
            }

            $exec = '';            
            foreach ($executantes as $key2 => $executante) {    
                if(empty($executante['executante_id']) || sizeof($executantes)-1 == $key2){
                    $exec = substr($exec, 0, -1);
                    continue;                    
                }         
                $exec .= $executante['executante_id'].','; 
            }*/
            $bm = ''; 
            if(isset($_POST['bm']))
                $bm = ' AND count(bm.id) > 0';            

            $frs = ''; 
            if(isset($_POST['frs']))
                $frs = $_POST['frs'];

            $mostrar_valor = ''; 
            if(isset($_POST['valor']))
                $mostrar_valor = $_POST['valor'];

            $as_de = ''; 
            if(!empty($_POST['as_de']))
                $as_de = ' AND criado > "'.$_POST['as_de'].'"';

            $as_ate = ''; 
            if(!empty($_POST['as_ate']))
                $as_ate = ' AND criado < "'.$_POST['as_ate'].'"';

            $bm_de = ''; 
            if(!empty($_POST['bm_de']))
                $bm_de = ' AND data > "'.$_POST['bm_de'].'"';

            $bm_ate = ''; 
            if(!empty($_POST['bm_ate']))
                $bm_ate = ' AND data < "'.$_POST['bm_ate'].'"';

           
            if(isset($_POST['projeto'])){  
                $projetos = Yii::$app->db->createCommand('SELECT projeto.id AS projetoID, nome, projeto.descricao, site, projeto.contato, proposta, valor_proposta, projeto.qtd_km, nota_geral FROM projeto JOIN bm ON bm.projeto_id = projeto.id WHERE projeto.id IN ('.implode(',', $_POST['projeto']).') AND contato_id IN ('.$conts.') '.$as_de.' '.$as_ate.' '.$bm_de.' '.$bm_ate.' ORDER BY projeto.id DESC')->queryAll();
            }
            else{
                $projetos = Yii::$app->db->createCommand('SELECT projeto.id AS projetoID, nome, projeto.descricao, site, projeto.contato, proposta, valor_proposta, projeto.qtd_km, nota_geral FROM projeto JOIN bm ON bm.projeto_id = projeto.id WHERE contato_id IN ('.$conts.') '.$as_de.' '.$as_ate.' '.$bm_de.' '.$bm_ate.' ORDER BY projeto.id DESC')->queryAll();
            }
            $listProjetos = ArrayHelper::map($projetos,'id','nome');
            
            //$this->layout = 'blank';
            return $this->render('_relatoriogeral', [
                'projetos' => $projetos,
                'listProjetos' => $listProjetos,
                'com_bm' => $bm,
                'com_frs' => $frs,
                'mostrar_valor' => $mostrar_valor
            ]);
        

    }
    
}

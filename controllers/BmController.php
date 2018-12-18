<?php

namespace app\controllers;

use Yii;
use app\models\Bm;
use app\models\search\BmSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use \Datetime;
use app\models\Projeto;
use app\models\BmExecutante;
use yii\filters\AccessControl;
use app\models\Documento;
use app\models\Escopo;
/**
 * BmController implements the CRUD actions for Bm model.
 */
class BmController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        'access' => [
                'class' => AccessControl::className(),
                'only' => ['*'],
                'rules' => [
                    [
                        // 'actions' => ['index', 'view', 'create', 'update'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,'roles' => ['executante'],
                        'matchCallback' => function ($rule, $action) {
                            $cargo = Yii::$app->db->createCommand('SELECT cargo FROM executante WHERE usuario_id='.Yii::$app->user->id)->queryScalar();
                            if($cargo==2){
                                return true;
                            }
                            else{
                                return false;
                            }
                        }
                    ],  
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Bm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BmSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bm model.
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
     * Creates a new Bm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bm();
         $searchModel = new BmSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $projetos = Yii::$app->db->createCommand('SELECT id, nome FROM projeto')->queryAll();
        $listProjetos = ArrayHelper::map($projetos,'id','nome');

        if(isset($_GET['pagination'])) $dataProvider->pagination = false;  

        if ($model->load(Yii::$app->request->post())) {
            $ultBM = $model->numero_bm+1;
            if(!empty($_POST['Bm']['data'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Bm']['data']);          
                $model->data = date_format($dat, 'Y-m-d');
            }
            if(!empty($_POST['Bm']['de'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Bm']['de']);          
                $model->de = date_format($dat, 'Y-m-d');
            }
            if(!empty($_POST['Bm']['para'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Bm']['para']);          
                $model->para = date_format($dat, 'Y-m-d');
            }

            $model->save();
            Yii::$app->db->createCommand('UPDATE config SET ultimo_bm ='.$ultBM)->execute();
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'listProjetos' => $listProjetos,
        ]);
    }

    /**
     * Updates an existing Bm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new BmSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $projetos = Yii::$app->db->createCommand('SELECT id, nome FROM projeto')->queryAll();
        $listProjetos = ArrayHelper::map($projetos,'id','nome');

        $bm_executantes = Yii::$app->db->createCommand('SELECT * FROM bm_executante WHERE bm_id='.$model->id)->queryAll();
        

        if(!empty($model->data))
            $model->data = date_format(DateTime::createFromFormat('Y-m-d', $model->data), 'd/m/Y');
        if(!empty($model->de))
            $model->de = date_format(DateTime::createFromFormat('Y-m-d', $model->de), 'd/m/Y');
        if(!empty($model->para))
            $model->para = date_format(DateTime::createFromFormat('Y-m-d', $model->para), 'd/m/Y');
        if(!empty($model->frs_data_aprovacao))
            $model->frs_data_aprovacao = date_format(DateTime::createFromFormat('Y-m-d', $model->frs_data_aprovacao), 'd/m/Y');
        if(!empty($model->frs_data_faturamento))
            $model->frs_data_faturamento = date_format(DateTime::createFromFormat('Y-m-d', $model->frs_data_faturamento), 'd/m/Y');

        if ($model->load(Yii::$app->request->post())) {            
            if(!empty($_POST['Bm']['data'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Bm']['data']);          
                $model->data = date_format($dat, 'Y-m-d');
            }
            if(!empty($_POST['Bm']['de'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Bm']['de']);          
                $model->de = date_format($dat, 'Y-m-d');
            }
            if(!empty($_POST['Bm']['para'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Bm']['para']);          
                $model->para = date_format($dat, 'Y-m-d');
            }
            if(!empty($_POST['Bm']['frs_data_aprovacao'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Bm']['frs_data_aprovacao']);          
                $model->frs_data_aprovacao = date_format($dat, 'Y-m-d');
            }
            if(!empty($_POST['Bm']['frs_data_faturamento'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Bm']['frs_data_faturamento']);          
                $model->frs_data_faturamento = date_format($dat, 'Y-m-d');
            }

            if(isset($_POST['bm_executante'])){
                foreach ($_POST['bm_executante'] as $key => $bm_exe) {
                $bm_executante = Yii::$app->db->createCommand('SELECT * FROM bm_executante WHERE bm_id='.$model->id.' AND executante_id='.$key)->queryOne();
               
                if(!empty($bm_executante)){
                    $bm_exe_model = BmExecutante::findOne($bm_executante['id']);
                }
                else{
                    $bm_exe_model = new BmExecutante();
                    $bm_exe_model->bm_id = $model->id;
                    $bm_exe_model->executante_id = $key;
                }   
                
                $bm_exe_model->previsao_pgt = !empty($bm_exe['previsao_pgt']) ? $bm_exe['previsao_pgt'] : NULL;
                $bm_exe_model->data_pgt = !empty($bm_exe['data_pgt']) ? $bm_exe['data_pgt'] : NULL;
                $bm_exe_model->pago = !empty($bm_exe['pago']) ? 1 : 0;
                if(!$bm_exe_model->save()){
                    print_r($bm_exe_model->getErrors());
                    die();
                }
            }    
            }
                   


            $model->save();            
            return $this->redirect(['update', 'id' => $model->id, 'bm_executantes' => $bm_executantes]);
        }

        //horas de cada executante
        $bmescopos = Yii::$app->db->createCommand('SELECT escopo_id, exe_tp_id, exe_ej_id, exe_ep_id, exe_es_id, exe_ee_id, SUM(bm_escopo.horas_tp) h_tp, SUM(bm_escopo.horas_ej) h_ej, SUM(bm_escopo.horas_ep) h_ep, SUM(bm_escopo.horas_es) h_es, SUM(bm_escopo.horas_ee) h_ee FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE (bm_escopo.horas_tp!=0 || bm_escopo.horas_ej!=0 || bm_escopo.horas_ep!=0 || bm_escopo.horas_es!=0 || bm_escopo.horas_ee!=0) AND bm_id = '.$model->id.' GROUP BY escopo_id')->queryAll();
        
        $tipo_exec = Yii::$app->db->createCommand('SELECT * FROM tipo_executante')->queryAll();
        $valor_total = number_format($model->executado_ee * $tipo_exec[4]['valor_hora']+
                        $model->executado_es * $tipo_exec[3]['valor_hora'] +
                        $model->executado_ep * $tipo_exec[2]['valor_hora']+
                        $model->executado_ej * $tipo_exec[1]['valor_hora']+
                        $model->executado_tp * $tipo_exec[0]['valor_hora']+
                        $model['km'] * Yii::$app->db->createCommand('SELECT vl_km FROM executante WHERE usuario_id=61')->queryScalar(), 2, ',', '.');
       

        return $this->render('update', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'listProjetos' => $listProjetos,
            'bmescopos' => $bmescopos,
            'valor_total' => $valor_total,
            'bm_executantes' => $bm_executantes,
        ]);
    }

    /**
     * Deletes an existing Bm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['create']);
    }

      //habilitar ajax
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionPreencheform(){
        if (Yii::$app->request->isAjax) {    
            $data = Yii::$app->db->createCommand('SELECT * FROM projeto WHERE id='.Yii::$app->request->post()['id'])->queryOne(); 

            $horas = Yii::$app->db->createCommand('SELECT SUM(executado_ee) executado_ee,SUM(executado_es) executado_es,SUM(executado_ep) executado_ep,SUM(executado_ej) executado_ej,SUM(executado_tp) executado_tp, SUM(horas_ee) horas_ee,SUM(horas_es) horas_es,SUM(horas_ep) horas_ep,SUM(horas_ej) horas_ej,SUM(horas_tp) horas_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.Yii::$app->request->post()['id'])->queryOne();

            $tot_horas = $horas['horas_ee'] + $horas['horas_es'] + $horas['horas_ep'] + $horas['horas_ej'] + $horas['horas_tp'];

            $exec = $horas['executado_ee']+$horas['executado_es']+$horas['executado_ep']+$horas['executado_ej']+$horas['executado_tp'];

            $saldo = $tot_horas - $exec;
            
            array_push($data, $saldo, $exec,$horas['executado_ee'],$horas['executado_es'],$horas['executado_ep'],$horas['executado_ej'],$horas['executado_tp']);

            echo json_encode($data);  
        }
        
    }

    /**
     * Finds the Bm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGerarbm()
    {
        if($_GET['id']){
            $bm = Yii::$app->db->createCommand('SELECT * FROM bm WHERE id='.$_GET['id'])->queryOne();
            $projeto = Projeto::findOne($bm['projeto_id']);
            $escopos = Yii::$app->db->createCommand('SELECT SUM(horas_tp) h_tp,SUM(horas_ej) h_ej,SUM(horas_ep) h_ep,SUM(horas_es) h_es,SUM(horas_ee) h_ee,SUM(executado_tp) executado_tp,SUM(executado_ej) executado_ej,SUM(executado_ep) executado_ep,SUM(executado_es) executado_es,SUM(executado_ee) executado_ee, escopo.nome as nomeE FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$projeto->id)->queryOne();

            $tipo_exec = Yii::$app->db->createCommand('SELECT * FROM tipo_executante')->queryAll();


            $bm_page = $this->renderPartial('relatorio/_bm', [
                'bm' => $bm,
                'projeto' => $projeto,
                'escopos' => $escopos,
                'tipo_exec' => $tipo_exec]);

            if (!file_exists('uploaded-files/'.$projeto['id'])) {
                mkdir('uploaded-files/'.$projeto['id'], 0777, true);
            }

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($bm_page);   
            $mpdf->Output('uploaded-files/'.$projeto['id'].'/BM-'.explode('AS-', explode('_', $projeto['proposta'])[0])[1].'_'.$bm['numero_bm'].'.pdf', 'F');     
            $mpdf->Output('uploaded-files/'.$projeto['id'].'/BM-'.explode('AS-', explode('_', $projeto['proposta'])[0])[1].'_'.$bm['numero_bm'].'.pdf', 'I');         

            $existsFile = Yii::$app->db->createCommand('SELECT id FROM documento WHERE nome="BM-'.$projeto['proposta'].'_'.$bm['numero_bm'].'.pdf"')->queryScalar();

            if(empty($existsFile)){                          
                //cria o registro do arquivo
                $doc = new Documento();
                $doc->projeto_id = $bm['projeto_id'];
                $doc->nome = 'BM-'.explode('AS-', explode('_', $projeto['proposta'])[0])[1].'_'.$bm['numero_bm'].'.pdf';
                $doc->revisao = 0;
                $doc->path = 'BM-'.explode('AS-', explode('_', $projeto['proposta'])[0])[1].'_'.$bm['numero_bm'].'.pdf';
                $doc->is_global = 0;
                $doc->save();
            }

        }
    }

    public function actionGerarextratos()
    {
        if($_GET['id']){           
            
            $bm = Yii::$app->db->createCommand('SELECT * FROM bm WHERE id='.$_GET['id'])->queryOne();
            $projeto = Projeto::findOne($bm['projeto_id']);

            //executantes PJ
            $exe_pre = Yii::$app->db->createCommand('SELECT * FROM executante JOIN user ON user.id=executante.usuario_id JOIN projeto_executante ON projeto_executante.executante_id = executante.usuario_id WHERE projeto_id='.$projeto->id.' AND is_prestador=1 AND user.id='.$_GET['executante_id'])->queryOne();

            $tipo_exec = Yii::$app->db->createCommand('SELECT * FROM tipo_executante')->queryAll();


            
            $horas_exe_ee = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ee) h_ee FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$_GET['id'].' AND exe_ee_id='.$exe_pre['usuario_id'])->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ee) h_ee FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$_GET['id'].' AND exe_ee_id='.$exe_pre['usuario_id'])->queryScalar();

            $horas_exe_es = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_es) h_es FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$_GET['id'].' AND exe_es_id='.$exe_pre['usuario_id'])->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_es) h_es FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$_GET['id'].' AND exe_es_id='.$exe_pre['usuario_id'])->queryScalar();

            $horas_exe_ep = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ep) h_ep FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$_GET['id'].' AND exe_ep_id='.$exe_pre['usuario_id'])->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ep) h_ep FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$_GET['id'].' AND exe_ep_id='.$exe_pre['usuario_id'])->queryScalar();

            $horas_exe_ej = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ej) h_ej FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$_GET['id'].' AND exe_ej_id='.$exe_pre['usuario_id'])->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ej) h_ej FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$_GET['id'].' AND exe_ej_id='.$exe_pre['usuario_id'])->queryScalar();

            $horas_exe_tp = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_tp) h_tp FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$_GET['id'].' AND exe_tp_id='.$exe_pre['usuario_id'])->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_tp) h_tp FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$_GET['id'].' AND exe_tp_id='.$exe_pre['usuario_id'])->queryScalar();

            $valor_ee = $exe_pre['vl_hh_ee'] * $horas_exe_ee;
            $valor_es = $exe_pre['vl_hh_es'] * $horas_exe_es;
            $valor_ep = $exe_pre['vl_hh_ep'] * $horas_exe_ep;
            $valor_ej = $exe_pre['vl_hh_ej'] * $horas_exe_ej;
            $valor_tp = $exe_pre['vl_hh_tp'] * $horas_exe_tp;               
            $valor_total = $valor_ee + $valor_es + $valor_ep + $valor_ej + $valor_tp;
            


            $executante_extrato_page = $this->renderPartial('relatorio/_extrato_executante', [
                'bm' => $bm,
                'projeto' => $projeto,
                'horas_exe_ee' => $horas_exe_ee,                    
                'horas_exe_es' => $horas_exe_es,
                'horas_exe_ep' => $horas_exe_ep,
                'horas_exe_ej' => $horas_exe_ej,
                'horas_exe_tp' => $horas_exe_tp,
                'valor_ee' => $valor_ee, 
                'valor_es' => $valor_es,
                'valor_ep' => $valor_ep,
                'valor_ej' => $valor_ej,
                'valor_tp' => $valor_tp,   
                'valor_total' => $valor_total, 
                'prestador' =>  $exe_pre         
            ]);

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($executante_extrato_page);   
            $mpdf->Output('uploaded-files/'.$projeto['id'].'/BM-'.$projeto['proposta'].'_'.'/Extrato-'.$exe_pre['usuario_id'].'.pdf', 'I');
                    

        }
    }

    public function actionEditahoras(){
        if (Yii::$app->request->isAjax) {
            try{   
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                $horas_escopo = explode(";", Yii::$app->request->post()['horas_escopo']);

                $tot_ee=0;
                $tot_es=0;
                $tot_ep=0;
                $tot_ej=0;
                $tot_tp=0;
                
                foreach ($horas_escopo as $key => $esc_h) {
                    if(!isset(explode("-", $esc_h)[1])){
                        continue;
                    }
                    $horas_antes = Yii::$app->db->createCommand('SELECT horas_ee, horas_es, horas_ep, horas_ej, horas_tp FROM bm_escopo WHERE escopo_id='.explode("-", $esc_h)[2])->queryOne(); 

                    $sql = 'UPDATE bm_escopo SET '.explode("-", $esc_h)[1].' = '.explode("-", $esc_h)[3].' WHERE escopo_id='.explode("-", $esc_h)[2];
                    Yii::$app->db->createCommand($sql)->execute();  


                    $bm_id = Yii::$app->request->post()['id'];  

                    $executado_ee = 0;
                    $executado_es = 0;
                    $executado_ep = 0;
                    $executado_ej = 0;
                    $executado_tp = 0;

                    $escopo_model = Escopo::findOne(explode("-", $esc_h)[2]);
                    
                    if(explode("_", explode("-", $esc_h)[1])[1]=='ee'){
                        $tot_ee= $tot_ee + explode("-", $esc_h)[3];
                        $executado_ee = explode("-", $esc_h)[3];
                        $escopo_model->executado_ee = isset($escopo_model->executado_ee) ? abs($escopo_model->executado_ee+$executado_ee-$horas_antes['horas_ee']) : $executado_ee;
                    }
                    if(explode("_", explode("-", $esc_h)[1])[1]=='es'){
                        $tot_es= $tot_es + explode("-", $esc_h)[3];
                        $executado_es = explode("-", $esc_h)[3];
                        $escopo_model->executado_es = isset($escopo_model->executado_es) ? abs($escopo_model->executado_es+$executado_es-$horas_antes['horas_es']) : $executado_es;
                    }
                    if(explode("_", explode("-", $esc_h)[1])[1]=='ep'){
                        $tot_ep= $tot_ep + explode("-", $esc_h)[3];
                        $executado_ep = explode("-", $esc_h)[3];
                        $escopo_model->executado_ep = isset($escopo_model->executado_ep) ? abs($escopo_model->executado_ep+$executado_ep-$horas_antes['horas_ep']) : $executado_ep;
                    }
                    if(explode("_", explode("-", $esc_h)[1])[1]=='ej'){
                        $tot_ej= $tot_ej + explode("-", $esc_h)[3];
                        $executado_ej = explode("-", $esc_h)[3];
                        $escopo_model->executado_ej = isset($escopo_model->executado_ej) ? abs($escopo_model->executado_ej+$executado_ej-$horas_antes['horas_ej']) : $executado_ej;
                    }
                    if(explode("_", explode("-", $esc_h)[1])[1]=='tp'){
                        $tot_tp= $tot_tp + explode("-", $esc_h)[3];
                        $executado_tp = explode("-", $esc_h)[3];
                        $escopo_model->executado_tp = isset($escopo_model->executado_tp) ? abs($escopo_model->executado_tp+$executado_tp-$horas_antes['horas_tp']) : $executado_tp;
                    }
                    $escopo_model->horas_saldo = abs($escopo_model->horas_saldo - (($horas_antes['horas_ee']+$horas_antes['horas_es']+$horas_antes['horas_ep']+$horas_antes['horas_ej']+$horas_antes['horas_tp']) - ($executado_ee+$executado_es+$executado_ep+$executado_ej+$executado_tp)));

                    $escopo_model->horas_acumulada = $escopo_model->horas_acumulada + (($horas_antes['horas_ee']+$horas_antes['horas_es']+$horas_antes['horas_ep']+$horas_antes['horas_ej']+$horas_antes['horas_tp']) - ($executado_ee+$executado_es+$executado_ep+$executado_ej+$executado_tp));
                    $escopo_model->save();
                    
                    

                    Yii::$app->db->createCommand('UPDATE escopo SET horas_acumulada=(executado_ee+executado_es+executado_ep+executado_ej+executado_tp) WHERE id='.explode("-", $esc_h)[2])->execute();


                    //falta recalcular o saldo e acumulado da tabela escopo
       
                }

                $bmArr = Yii::$app->db->createCommand('SELECT * FROM bm WHERE id='.$bm_id)->queryOne(); 

                $projetoArr = Yii::$app->db->createCommand('SELECT * FROM projeto WHERE id='.$bmArr['projeto_id'])->queryOne(); 

                $horasAS = Yii::$app->db->createCommand('SELECT SUM(horas_ee) h_ee, SUM(horas_es) h_es, SUM(horas_ep) h_ep, SUM(horas_ej) h_ej, SUM(horas_tp) h_tp FROM escopo WHERE projeto_id='.$projetoArr['id'])->queryOne();

                $totalHoras = $horasAS['h_ee']+$horasAS['h_es']+$horasAS['h_ep']+$horasAS['h_ej']+$horasAS['h_tp'];
                
                //quantidade de bms do projeto
                $numbm = count(Yii::$app->db->createCommand('SELECT id FROM bm WHERE projeto_id='.$projetoArr['id'])->queryAll()) + 1;                

                $saldo = $totalHoras - ($tot_ee + $tot_es + $tot_ep + $tot_ej + $tot_tp);

                $acumulada = $tot_ee + $tot_es + $tot_ep + $tot_ej + $tot_tp;

                $percAcumulada = sprintf("%.2f",($acumulada * 100) / $totalHoras);

                $tipo_exec = Yii::$app->db->createCommand('SELECT * FROM tipo_executante')->queryAll();
                $valorTotalBm = number_format($tot_ee * $tipo_exec[4]['valor_hora']+
                        $tot_es * $tipo_exec[3]['valor_hora'] +
                        $tot_ep * $tipo_exec[2]['valor_hora']+
                        $tot_ej * $tipo_exec[1]['valor_hora']+
                        $tot_tp * $tipo_exec[0]['valor_hora']+
                        $bmArr['km'] * Yii::$app->db->createCommand('SELECT vl_km FROM executante WHERE usuario_id=61')->queryScalar(), 2, ',', '.');  
                
                $percBm = number_format(($valorTotalBm * 100) / number_format($projetoArr['valor_proposta'], 2, ',', '.'), 2, ',', '.');

                $descricao = $projetoArr['desc_resumida'].'.'.PHP_EOL.'Esse '.$numbm.'º Boletim de Medição corresponde a '.$percBm.'% das atividades citadas na '.$projetoArr['proposta'].''.PHP_EOL.'A medição total acumulada incluindo este BM corresponde a '.$percAcumulada.'% das atividades realizadas.';

                Yii::$app->db->createCommand('UPDATE bm SET executado_ee= '.$tot_ee.', executado_es= '.$tot_es.', executado_ep= '.$tot_ep.', executado_ej= '.$tot_ej.', executado_tp= '.$tot_tp.', descricao="'.$descricao.'", saldo ='.$saldo.', acumulado= '.$acumulada.' WHERE id='.$bm_id)->execute();


                

                $transaction->commit();
                return 'success';
            }
            catch(Exception $e){
                $transaction->rollBack();
                return $e;
            }
        }
    }

    public function actionEnviaremail(){
        if (Yii::$app->request->isAjax) {   
            $remetentes = Yii::$app->request->post()['remetentes'];
            $assunto = Yii::$app->request->post()['assunto'];
            $corpoEmail = Yii::$app->request->post()['corpoEmail'];
            $nomeArquivo =  Yii::$app->request->post()['nomeArquivo'];

            $remetentesArr = explode(",", $remetentes);
            

            foreach ($remetentesArr as $key => $rem) {
                try{
                Yii::$app->mailer->compose()
                ->setFrom('hcnautomacaoweb@gmail.com')
                ->setTo(trim($rem))
                ->setSubject($assunto)
                ->setTextBody($corpoEmail)
                ->attach(Yii::$app->basePath .''. $nomeArquivo.'.pdf')
                ->send();
                }
                catch(Exception $e){
                    return $e;
                }
            }
            return Yii::$app->basePath .''. $nomeArquivo.'.pdf';
        }
    }
}

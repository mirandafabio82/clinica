<?php

namespace app\controllers;

use Yii;
use app\models\Projeto;
use app\models\search\LdpreliminarSearch;
use app\models\Cliente;
use app\models\Contato;
use app\models\ProjetoExecutante;
use app\models\Escopo;
use app\models\search\EscopoSearch;
use app\models\search\ProjetoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use \Datetime;
use yii\helpers\Url;
// use kartik\mpdf\Pdf;

//require_once  'C:\xampp\htdocs\hcn\vendor\autoload.php';
/**
 * ProjetoController implements the CRUD actions for Projeto model.
 */
class ProjetoController extends Controller
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
     * Lists all Projeto models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjetoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->post('Projeto')){
            $projeto_id = Yii::$app->request->post('editableKey');
            $projeto = Projeto::findOne($projeto_id);

            $out = Json::encode(['output'=>'', 'message'=>'']);
            $post =[];
            $posted = current($_POST['Projeto']);
            $post['Projeto'] = $posted;

            if($projeto->load($post)){
                $projeto->save();
                // $output = 'teste';
                $out = Json::encode(['output'=>'', 'message'=>'']);
            }
            echo $out;
            // return;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Projeto model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Projeto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Projeto();
        $model->tipo = 'A';
        $model->data_proposta =  date('d/m/Y');
        $searchModel = new ProjetoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->post('editableKey')){
            try{
                $projeto_id = Yii::$app->request->post('editableKey');
                $projeto = Projeto::findOne($projeto_id);

                $out = Json::encode(['output'=>'', 'message'=>'']);
                $post =[];
                $posted = current($_POST['Projeto']);
                $post['Projeto'] = $posted;                
                $projeto->save();
               
                // $output = 'teste';
                $out = Json::encode(['output'=>'', 'message'=>'']);
                
                echo $out;
                return $this->redirect(['create']);
            }
            catch(Exception $e){
                throw $e;
            }
        }

        $clientes = Yii::$app->db->createCommand('SELECT id, CONCAT(nome," - " ,site) as nome FROM cliente')->queryAll();
        $listClientes = ArrayHelper::map($clientes,'id','nome');

        $sites = Yii::$app->db->createCommand('SELECT id, nome FROM site')->queryAll();
        $listSites = ArrayHelper::map($sites,'id','nome');

        $nomes = Yii::$app->db->createCommand('SELECT id, nome FROM projeto_nome')->queryAll();
        $listNomes = ArrayHelper::map($nomes,'id','nome');

        $contatos = Yii::$app->db->createCommand('SELECT id, nome FROM contato JOIN user ON contato.usuario_id = user.id')->queryAll();
        $listContatos = ArrayHelper::map($contatos,'id','nome');

        $escopo = Yii::$app->db->createCommand('SELECT id, nome FROM escopopadrao')->queryAll();
        $listEscopo = ArrayHelper::map($escopo,'id','nome');

        $disciplina = Yii::$app->db->createCommand('SELECT id, nome FROM disciplina')->queryAll();
        $listDisciplina = ArrayHelper::map($disciplina,'id','nome');

        $status = Yii::$app->db->createCommand('SELECT id, status FROM projeto_status')->queryAll();
        $listStatus = ArrayHelper::map($status,'id','status');

        $executantes = Yii::$app->db->createCommand('SELECT usuario_id, nome FROM executante JOIN user ON executante.usuario_id = user.id')->queryAll();
        $listExecutantes = ArrayHelper::map($executantes,'usuario_id','nome');

        $searchEscopo = new EscopoSearch();
        $escopoDataProvider = $searchEscopo->search(Yii::$app->request->queryParams);
        $escopoDataProvider->query->join('join','atividademodelo', 'atividademodelo.id=escopo.atividademodelo_id')->where('1=2');


        if ($model->load(Yii::$app->request->post())) {
            try{
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                $model->setAttributes($_POST['Projeto']); 
                if($model->tipo == "P"){
                    $model->proposta = 'PTC'.'-'.$model->codigo.'-'.$model->site.'-'.$model->rev_proposta;
                }
                else{
                    $model->proposta = 'AS'.'-'.$model->codigo.'-'.$model->site.'-'.preg_replace('/[^0-9]/', '', $model->nome);
                }
                
                if(!empty($_POST['Projeto']['data_proposta'])){
                    $dat = DateTime::createFromFormat('d/m/Y', $_POST['Projeto']['data_proposta']);          
                    $model->data_proposta = date_format($dat, 'Y-m-d');
                }
                if(!empty($_POST['Projeto']['data_pendencia'])){
                    $dat = DateTime::createFromFormat('d/m/Y', $_POST['Projeto']['data_pendencia']);          
                    $model->data_pendencia = date_format($dat, 'Y-m-d');
                }
                if(!empty($_POST['Projeto']['data_entrega'])){
                    $dat = DateTime::createFromFormat('d/m/Y', $_POST['Projeto']['data_entrega']);          
                    $model->data_entrega = date_format($dat, 'Y-m-d');
                }

                if($model->save()){       

                    if(isset($_POST['Escopos'])){

                        $escopos = $_POST['Escopos'];
                        $automacoes = isset($escopos['Automação']) ? $escopos['Automação'] : array();
                        $processos = isset($escopos['Processo']) ? $escopos['Processo'] : array();
                        $instrumentacoes = isset($escopos['Instrumentação']) ? $escopos['Instrumentação'] : array();

                        foreach ($automacoes as $key => $automacao) {
                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE (escopopadrao_id='.$automacao.' OR escopopadrao_id=0) AND disciplina_id = 1 AND isPrioritaria=1')->queryAll();
                            
                            foreach ($atvmodelos as $key => $atv) {
                                $escopo_model = new Escopo();
                                $escopo_model->projeto_id = $model->id;
                                $escopo_model->atividademodelo_id = $atv['id'];
                                $escopo_model->nome = $atv['nome'];
                                $escopo_model->descricao = $atv['nome'];
                                $escopo_model->save();
                            }
                        }
                        foreach ($processos as $key => $processo) {

                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE (escopopadrao_id='.$processo.' OR escopopadrao_id=0) AND disciplina_id = 2 AND isPrioritaria=1')->queryAll();

                            foreach ($atvmodelos as $key => $atv) {
                                $escopo_model = new Escopo();
                                $escopo_model->projeto_id = $model->id;
                                $escopo_model->atividademodelo_id = $atv['id'];
                                $escopo_model->nome = $atv['nome'];
                                $escopo_model->descricao = $atv['nome'];
                                $escopo_model->save();
                            }
                        }
                        foreach ($instrumentacoes as $key => $instrumentacao) {

                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE (escopopadrao_id='.$instrumentacao.' OR escopopadrao_id=0) AND disciplina_id = 3 AND isPrioritaria=1')->queryAll();

                            foreach ($atvmodelos as $key => $atv) {
                                $escopo_model = new Escopo();
                                $escopo_model->projeto_id = $model->id;
                                $escopo_model->atividademodelo_id = $atv['id'];
                                $escopo_model->nome = $atv['nome'];
                                $escopo_model->descricao = $atv['nome'];
                                $escopo_model->save();
                            }
                        }

                        if(isset($_POST['np'])){

                            foreach ($_POST['np'] as $key => $np) {

                               $ativi = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE id='.$np)->queryOne();

                                $escopo_model = new Escopo();
                                $escopo_model->projeto_id = $model->id;
                                $escopo_model->atividademodelo_id = $ativi['id'];
                                $escopo_model->nome = $ativi['nome'];
                                $escopo_model->descricao = $ativi['nome'];
                                $escopo_model->save();
                            }
                        }
                     
                    }                    
                }

                if(isset($_POST['ProjetoExecutante'])){
                   foreach ($_POST['ProjetoExecutante'] as $key => $proExe) {
                       $proExeModel = new ProjetoExecutante();
                       $proExeModel->projeto_id = $model->id;
                       $proExeModel->executante_id = $proExe;

                       $proExeModel->save();
                   }
                }

                $transaction->commit();
                return $this->redirect(['update', 'id' => $model->id]);
            }
            catch(Exception $e){
                $transaction->rollBack();
                throw $e;
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'listClientes' => $listClientes,
                'listContatos' => $listContatos,
                'listEscopo' => $listEscopo,
                'listSites' => $listSites,
                'listNomes' => $listNomes,
                'listStatus' => $listStatus,
                'listDisciplina' => $listDisciplina,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'escopoDataProvider' => $escopoDataProvider,
                'searchEscopo' => $searchEscopo,
                'executantes' => $executantes,
                'listExecutantes' =>   $listExecutantes

            ]);
        }
    }

    /**
     * Updates an existing Projeto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id); 
        
        if(!empty($model->data_pendencia))
            $model->data_pendencia = date_format(DateTime::createFromFormat('Y-m-d', $model->data_pendencia), 'd/m/Y');
        if(!empty($model->data_entrega))
            $model->data_entrega = date_format(DateTime::createFromFormat('Y-m-d', $model->data_entrega), 'd/m/Y');
        if(!empty($model->data_proposta))
            $model->data_proposta = date_format(DateTime::createFromFormat('Y-m-d', $model->data_proposta), 'd/m/Y');        

        $searchModel = new ProjetoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $ldPreliminarSearchModel = new LdpreliminarSearch();
        $ldPreliminarDataProvider = $ldPreliminarSearchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->post('editableKey')){
             
            /*if(isset($_POST['Escopo'])){

                $escopo_id = Yii::$app->request->post('editableKey');
                $escopo = Escopo::findOne($escopo_id);

                $out = Json::encode(['output'=>'', 'message'=>'']);
                $post =[];
                $posted = current($_POST['Escopo']);
                $post['Escopo'] = $posted;

                if($escopo->load($post)){
                    $escopo->save();
                    // $output = 'teste';
                    $out = Json::encode(['output'=>'', 'message'=>'']);
                }
                echo $out;
                return $this->redirect(['update', 'id' => $model->id]);
            }*/
               
            if(isset($_POST['Projeto'])){
                $projeto_id = Yii::$app->request->post('editableKey');
                $projeto = Projeto::findOne($projeto_id);

                $out = Json::encode(['output'=>'', 'message'=>'']);
                $post =[];
                $posted = current($_POST['Projeto']);
                $post['Projeto'] = $posted;

                if($projeto->load($post)){
                    $projeto->save();
                    // $output = 'teste';
                    $out = Json::encode(['output'=>'', 'message'=>'']);
                }
                echo $out;
                return $this->redirect(['update', 'id' => $model->id]);
            }

        }

 
        $clientes = Yii::$app->db->createCommand('SELECT id, CONCAT(nome," - " ,site) as nome FROM cliente')->queryAll();
        $listClientes = ArrayHelper::map($clientes,'id','nome');

        $sites = Yii::$app->db->createCommand('SELECT id, nome FROM site')->queryAll();
        $listSites = ArrayHelper::map($sites,'id','nome');

        $nomes = Yii::$app->db->createCommand('SELECT id, nome FROM projeto_nome')->queryAll();
        $listNomes = ArrayHelper::map($nomes,'id','nome');

        $contatos = Yii::$app->db->createCommand('SELECT id, nome FROM contato JOIN user ON contato.usuario_id = user.id WHERE cliente_id='.$model->cliente_id)->queryAll();
        $listContatos = ArrayHelper::map($contatos,'id','nome');

        $escopo = Yii::$app->db->createCommand('SELECT id, nome FROM escopopadrao')->queryAll();
        $listEscopo = ArrayHelper::map($escopo,'id','nome');

        $disciplina = Yii::$app->db->createCommand('SELECT id, nome FROM disciplina')->queryAll();
        $listDisciplina = ArrayHelper::map($disciplina,'id','nome');

        $status = Yii::$app->db->createCommand('SELECT id, status FROM projeto_status')->queryAll();
        $listStatus = ArrayHelper::map($status,'id','status');

        $escopoArray = Yii::$app->db->createCommand('SELECT * FROM atividademodelo JOIN escopo  ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$model->id.' ORDER BY isEntregavel ASC')->queryAll();

        $executantes_tp = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id JOIN projeto_executante ON projeto_executante.executante_id=executante.usuario_id WHERE tipo_executante.id =1 AND projeto_executante.projeto_id='.$model->id)->queryAll();
        $listExecutantes_tp = ArrayHelper::map($executantes_tp,'exec_id','nome'); 

        $executantes_ej = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id JOIN projeto_executante ON projeto_executante.executante_id=executante.usuario_id  WHERE tipo_executante.id =2 AND projeto_executante.projeto_id='.$model->id)->queryAll();
        $listExecutantes_ej = ArrayHelper::map($executantes_ej,'exec_id','nome'); 

        $executantes_ep = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id JOIN projeto_executante ON projeto_executante.executante_id=executante.usuario_id WHERE tipo_executante.id =3 AND projeto_executante.projeto_id='.$model->id)->queryAll();
        $listExecutantes_ep = ArrayHelper::map($executantes_ep,'exec_id','nome');

        $executantes_es = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id JOIN projeto_executante ON projeto_executante.executante_id=executante.usuario_id WHERE tipo_executante.id =4 AND projeto_executante.projeto_id='.$model->id)->queryAll();
        $listExecutantes_es = ArrayHelper::map($executantes_es,'exec_id','nome'); 

        $executantes_ee = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id JOIN projeto_executante ON projeto_executante.executante_id=executante.usuario_id WHERE tipo_executante.id =5 AND projeto_executante.projeto_id='.$model->id)->queryAll();
        $listExecutantes_ee = ArrayHelper::map($executantes_ee,'exec_id','nome'); 

        $executantes = Yii::$app->db->createCommand('SELECT usuario_id, nome FROM executante JOIN user ON executante.usuario_id = user.id')->queryAll();
        $listExecutantes = ArrayHelper::map($executantes,'usuario_id','nome');

        
        $searchEscopo = new EscopoSearch();
        $escopoDataProvider = $searchEscopo->search(Yii::$app->request->queryParams);
        $escopoDataProvider->query->join('join','atividademodelo', 'atividademodelo.id=escopo.atividademodelo_id')->where('projeto_id='.$model->id);

         //atualizando os valores de hora e executante do escopo
        if(isset($_POST['Escopo'])){

            $totalHoras = 0;
            $valorProposta = 0;

            $valor_tp = Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=1')->queryScalar();
            $valor_ej = Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=2')->queryScalar();
            $valor_ep = Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=3')->queryScalar();
            $valor_es = Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=4')->queryScalar();
            $valor_ee = Yii::$app->db->createCommand('SELECT valor_pago FROM tipo_executante WHERE id=5')->queryScalar();

            foreach ($_POST['Escopo'] as $key => $esc) {
                $totalHoras = $totalHoras + $esc['horas_tp'] 
                                            + $esc['horas_ej'] 
                                            + $esc['horas_ep']
                                            + $esc['horas_es']
                                            + $esc['horas_ee'];

                $valorProposta = $valorProposta + $esc['horas_tp'] *  $valor_tp
                                            + $esc['horas_ej'] * $valor_ej
                                            + $esc['horas_ep'] * $valor_ep
                                            + $esc['horas_es'] * $valor_es
                                            + $esc['horas_ee'] * $valor_ee;                                            
            }
            
            
           Yii::$app->db->createCommand('UPDATE projeto SET total_horas='.$totalHoras.', valor_proposta='.$valorProposta.' WHERE id='.$model->id)->execute();
           $model->total_horas = $totalHoras;
            
            foreach ($_POST['Escopo'] as $key => $esc) {

                $escopo = Escopo::findIdentity($key);
                if(!empty($escopo)){                   

                    $escopo->setAttributes($_POST['Escopo'][$key]);
                    $escopo->save();
                }
            }
            //salva resumo e outras abas
            if(isset($_POST['Projeto'])){
                $model->setAttributes($_POST['Projeto']);

                if(!empty($model->data_pendencia)){
                    $dat = DateTime::createFromFormat('d/m/Y', $model->data_pendencia);          
                    $model->data_pendencia = date_format($dat, 'Y-m-d');
                }
                if(!empty($model->data_entrega)){
                    $dat = DateTime::createFromFormat('d/m/Y', $model->data_entrega);          
                    $model->data_entrega = date_format($dat, 'Y-m-d');
                }
                if(!empty($model->data_proposta)){
                    $dat = DateTime::createFromFormat('d/m/Y', $model->data_proposta);          
                    $model->data_proposta = date_format($dat, 'Y-m-d');
                }

                // $model->nota_geral = $_POST['Projeto']['nota_geral'];
                $model->resumo_escopo = $_POST['Projeto']['resumo_escopo'];
                $model->resumo_exclusoes = $_POST['Projeto']['resumo_exclusoes'];
                $model->resumo_premissas = $_POST['Projeto']['resumo_premissas'];
                $model->resumo_restricoes = $_POST['Projeto']['resumo_restricoes'];
                $model->resumo_normas = $_POST['Projeto']['resumo_normas'];
                $model->resumo_documentos = $_POST['Projeto']['resumo_documentos'];
                $model->resumo_itens = $_POST['Projeto']['resumo_itens'];
                $model->resumo_prazo = $_POST['Projeto']['resumo_prazo'];
                $model->resumo_observacoes = $_POST['Projeto']['resumo_observacoes'];
                $model->save();
            }
           
        }

          if ($model->load(Yii::$app->request->post())) {
            try{
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                $model->setAttributes($_POST['Projeto']);      
                if($model->tipo == "P"){
                    $model->proposta = 'PTC'.'-'.$model->codigo.'-'.$model->site.'-'.$model->rev_proposta;
                }
                else{
                    $model->proposta = 'AS'.'-'.$model->codigo.'-'.$model->site.'-'.preg_replace('/[^0-9]/', '', $model->nome);
                } 
                if(!empty($_POST['Projeto']['data_proposta'])){
                    $dat = DateTime::createFromFormat('d/m/Y', $_POST['Projeto']['data_proposta']);          
                    $model->data_proposta = date_format($dat, 'Y-m-d');
                }
                if(!empty($_POST['Projeto']['data_pendencia'])){
                    $dat = DateTime::createFromFormat('d/m/Y', $_POST['Projeto']['data_pendencia']);          
                    $model->data_pendencia = date_format($dat, 'Y-m-d');
                }
                if(!empty($_POST['Projeto']['data_entrega'])){
                    $dat = DateTime::createFromFormat('d/m/Y', $_POST['Projeto']['data_entrega']);          
                    $model->data_entrega = date_format($dat, 'Y-m-d');
                }

                if($model->save()){                        
                    if(isset($_POST['Escopos'])){
                        $escopos = $_POST['Escopos'];
                        $automacoes = isset($escopos['Automação']) ? $escopos['Automação'] : array();
                        $processos = isset($escopos['Processo']) ? $escopos['Processo'] : array();
                        $instrumentacoes = isset($escopos['Instrumentação']) ? $escopos['Instrumentação'] : array();

                        
                        if(!isset($automacoes[1]))
                         Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id NOT IN(SELECT id FROM atividademodelo WHERE (escopopadrao_id=1  OR escopopadrao_id=0) AND disciplina_id=1) AND (horas_tp=null AND horas_ej=null AND horas_ep=null AND horas_es=null AND horas_ee=null) AND projeto_id='.$model->id)->execute();

                        if(!isset($automacoes[2]))
                         Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id NOT IN(SELECT id FROM atividademodelo WHERE (escopopadrao_id=2 OR escopopadrao_id=0) AND disciplina_id=1) AND (horas_tp=null AND horas_ej=null AND horas_ep=null AND horas_es=null AND horas_ee=null) AND projeto_id='.$model->id )->execute();

                        if(!isset($automacoes[3]))
                         Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id NOT IN(SELECT id FROM atividademodelo WHERE (escopopadrao_id=3 OR escopopadrao_id=0) AND disciplina_id=1) AND (horas_tp=null AND horas_ej=null AND horas_ep=null AND horas_es=null AND horas_ee=null) AND projeto_id='.$model->id)->execute();

                        foreach ($automacoes as $key => $automacao) {
                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE (escopopadrao_id='.$automacao.' OR escopopadrao_id=0) AND disciplina_id = 1 AND isPrioritaria=1')->queryAll();

                            foreach ($atvmodelos as $key => $atv) {
                                $existeEscopo = Yii::$app->db->createCommand('SELECT id FROM escopo WHERE projeto_id='.$model->id.' AND atividademodelo_id='.$atv['id'])->queryScalar();
                                
                                if(!$existeEscopo){
                                    $escopo_model = new Escopo();
                                    $escopo_model->projeto_id = $model->id;
                                    $escopo_model->atividademodelo_id = $atv['id'];
                                    $escopo_model->nome = $atv['nome'];
                                    $escopo_model->descricao = $atv['nome'];
                                    $escopo_model->save();
                                }
                            }
                        }
                        if(!isset($processos[1]))
                         Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id NOT IN(SELECT id FROM atividademodelo WHERE (escopopadrao_id=1  OR escopopadrao_id=0) AND disciplina_id=2) AND (horas_tp=null AND horas_ej=null AND horas_ep=null AND horas_es=null AND horas_ee=null) AND projeto_id='.$model->id)->execute();

                        if(!isset($processos[2]))
                         Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id NOT IN(SELECT id FROM atividademodelo WHERE (escopopadrao_id=2 OR escopopadrao_id=0) AND disciplina_id=2) AND (horas_tp=null AND horas_ej=null AND horas_ep=null AND horas_es=null AND horas_ee=null) AND projeto_id='.$model->id )->execute();

                        if(!isset($processos[3]))
                         Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id NOT IN(SELECT id FROM atividademodelo WHERE (escopopadrao_id=3 OR escopopadrao_id=0) AND disciplina_id=2) AND (horas_tp=null AND horas_ej=null AND horas_ep=null AND horas_es=null AND horas_ee=null) AND projeto_id='.$model->id)->execute();
                        foreach ($processos as $key => $processo) {
                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE (escopopadrao_id='.$processo.' OR escopopadrao_id=0) AND disciplina_id = 2 AND isPrioritaria=1')->queryAll();

                            foreach ($atvmodelos as $key => $atv) {
                                $existeEscopo = Yii::$app->db->createCommand('SELECT id FROM escopo WHERE projeto_id='.$model->id.' AND atividademodelo_id='.$atv['id'])->queryScalar();
                                if(!$existeEscopo){
                                    $escopo_model = new Escopo();
                                    $escopo_model->projeto_id = $model->id;
                                    $escopo_model->atividademodelo_id = $atv['id'];
                                    $escopo_model->nome = $atv['nome'];
                                    $escopo_model->descricao = $atv['nome'];
                                    $escopo_model->save();
                                }
                            }
                        }
                        if(!isset($instrumentacoes[1]))
                         Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id NOT IN(SELECT id FROM atividademodelo WHERE (escopopadrao_id=1  OR escopopadrao_id=0) AND disciplina_id=3) AND (horas_tp=null AND horas_ej=null AND horas_ep=null AND horas_es=null AND horas_ee=null) AND projeto_id='.$model->id)->execute();

                        if(!isset($instrumentacoes[2]))
                         Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id NOT IN(SELECT id FROM atividademodelo WHERE (escopopadrao_id=2 OR escopopadrao_id=0) AND disciplina_id=3) AND (horas_tp=null AND horas_ej=null AND horas_ep=null AND horas_es=null AND horas_ee=null) AND projeto_id='.$model->id )->execute();

                        if(!isset($instrumentacoes[3]))
                         Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id NOT IN(SELECT id FROM atividademodelo WHERE (escopopadrao_id=3 OR escopopadrao_id=0) AND disciplina_id=3) AND (horas_tp=null AND horas_ej=null AND horas_ep=null AND horas_es=null AND horas_ee=null) AND projeto_id='.$model->id)->execute();

                        foreach ($instrumentacoes as $key => $instrumentacao) {
                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE (escopopadrao_id='.$instrumentacao.' OR escopopadrao_id=0) AND disciplina_id = 3 AND isPrioritaria=1')->queryAll();

                            foreach ($atvmodelos as $key => $atv) {
                                $existeEscopo = Yii::$app->db->createCommand('SELECT id FROM escopo WHERE projeto_id='.$model->id.' AND atividademodelo_id='.$atv['id'])->queryScalar();
                                if(!$existeEscopo){
                                    $escopo_model = new Escopo();
                                    $escopo_model->projeto_id = $model->id;
                                    $escopo_model->atividademodelo_id = $atv['id'];
                                    $escopo_model->nome = $atv['nome'];
                                    $escopo_model->descricao = $atv['nome'];
                                    $escopo_model->save();
                                }
                            }
                        }
                        if(isset($_POST['np'])){

                            foreach ($_POST['np'] as $key => $np) {

                               $ativi = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE id='.$np)->queryOne();

                                $escopo_model = new Escopo();
                                $escopo_model->projeto_id = $model->id;
                                $escopo_model->atividademodelo_id = $ativi['id'];
                                $escopo_model->nome = $ativi['nome'];
                                $escopo_model->descricao = $ativi['nome'];
                                $escopo_model->save();
                            }
                        }
                    }                    
                }
                Yii::$app->db->createCommand('DELETE FROM projeto_executante WHERE projeto_id='.$model->id)->execute();

                if(isset($_POST['ProjetoExecutante'])){
                   foreach ($_POST['ProjetoExecutante'] as $key => $proExe) {

                       $proExeModel = new ProjetoExecutante();
                       $proExeModel->projeto_id = $model->id;
                       $proExeModel->executante_id = $proExe;

                       $proExeModel->save();
                   }
                }

                $transaction->commit();
                return $this->redirect(['update', 'id' => $model->id]);
            }
            catch(Exception $e){
                $transaction->rollBack();
                throw $e;
            }
            catch(Exception $e){
                $transaction->rollBack();
                throw $e;
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'listClientes' => $listClientes,
                'listContatos' => $listContatos,
                'listEscopo' => $listEscopo,
                'listSites' => $listSites,
                'listNomes' => $listNomes,
                'listStatus' => $listStatus,
                'listDisciplina' => $listDisciplina,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'escopoDataProvider' => $escopoDataProvider,
                'searchEscopo' => $searchEscopo,
                'escopoArray' => $escopoArray,
                'listExecutantes_tp' => $listExecutantes_tp,
                'listExecutantes_ej' => $listExecutantes_ej,
                'listExecutantes_ep' => $listExecutantes_ep,
                'listExecutantes_es' => $listExecutantes_es,
                'listExecutantes_ee' => $listExecutantes_ee,
                'ldPreliminarSearchModel' => $ldPreliminarSearchModel, 
                'ldPreliminarDataProvider' => $ldPreliminarDataProvider,
                'executantes' => $executantes,
                'listExecutantes' =>   $listExecutantes
            ]);
        }
    }

    /**
     * Deletes an existing Projeto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->db->createCommand('DELETE FROM documento WHERE projeto_id='.$id)->execute();
        Yii::$app->db->createCommand('DELETE FROM escopo WHERE projeto_id='.$id)->execute();
        $projeto = Yii::$app->db->createCommand('SELECT id FROM projeto WHERE id='.$id)->queryScalar();
        if(empty($projeto)){
        }
        $this->findModel($id)->delete();

        return $this->redirect(['create']);
    }

    /**
     * Finds the Projeto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Projeto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Projeto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

     //habilitar ajax
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionPreencheformcliente(){
        if (Yii::$app->request->isAjax) {                 
            echo json_encode(Yii::$app->db->createCommand('SELECT * FROM cliente WHERE id='.Yii::$app->request->post()['id'])->queryOne());  
        }
        
    }

    public function actionPreenchepreenchecontatos(){
        if (Yii::$app->request->isAjax) {                 
            echo json_encode(Yii::$app->db->createCommand('SELECT * FROM contato JOIN user ON contato.usuario_id = user.id WHERE cliente_id='.Yii::$app->request->post()['id'])->queryAll());  
        }
        
    }

    public function actionPreencheformcontato(){
        if (Yii::$app->request->isAjax) {                 
            echo json_encode(Yii::$app->db->createCommand('SELECT * FROM contato JOIN user ON user.id = contato.usuario_id WHERE usuario_id='.Yii::$app->request->post()['id'])->queryOne());  
        }
        
    }

    public function actionPreencheformsite(){
        if (Yii::$app->request->isAjax) {                 
            echo json_encode(Yii::$app->db->createCommand('SELECT * FROM planta WHERE site_id='.Yii::$app->request->post()['id'])->queryAll());  
        }
        
    }

    public function actionPreenchekm(){
        if (Yii::$app->request->isAjax) {                 
            echo json_encode(Yii::$app->db->createCommand('SELECT qtd_km_dia, vl_km FROM executante WHERE usuario_id=61')->queryOne());  
        }
        
    }

    public function actionGerarrelatorio()
    {
        if($_GET['id']){
            $projeto = Projeto::findOne($_GET['id']);
          
            
            $processoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=2 AND projeto_id='.$projeto->id)->queryAll();
            $automacaoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=1 AND projeto_id='.$projeto->id)->queryAll();
            $instrumentacaoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=3 AND projeto_id='.$projeto->id)->queryAll();

            $escopos = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$projeto->id)->queryAll();

            $ldpreliminarArray = Yii::$app->db->createCommand('SELECT * FROM ld_preliminar')->queryAll();
            
            $basico = '';
            $detalhamento = '';
            $config = '';

            foreach ($escopos as $key => $escopo) {
                if($escopo['escopopadrao_id']==1)
                    $basico = 'checked="checked"';
                if($escopo['escopopadrao_id']==2)
                    $detalhamento = 'checked="checked"';
                if($escopo['escopopadrao_id']==3)
                    $config = 'checked="checked"';
            }

            $tipoExecutanteArray = Yii::$app->db->createCommand('SELECT * FROM tipo_executante')->queryAll();

            $capa = $this->renderPartial('relatorio/_capa', [
                'projeto' => $projeto]);

            $as = $this->renderPartial('relatorio/_as', [
                'projeto' => $projeto,
                'tipo_executante' => $tipoExecutanteArray,
                'processo' => $processoArray,
                'automacao' => $automacaoArray,
                'instrumentacao' => $instrumentacaoArray,
                'basico' => $basico,
                'detalhamento' => $detalhamento,
                'config' => $config]);

            $processo = $this->renderPartial('relatorio/_processo', [
                'projeto' => $projeto,
                'escopos' => $processoArray]);

            $automacao = $this->renderPartial('relatorio/_automacao', [
                'projeto' => $projeto,
                'escopos' => $automacaoArray]);

            $instrumentacao = $this->renderPartial('relatorio/_instrumentacao', [
                'projeto' => $projeto,
                'escopos' => $instrumentacaoArray]);

            $resumo = $this->renderPartial('relatorio/_resumo', [
                'projeto' => $projeto]);

            $ld_preliminar = $this->renderPartial('relatorio/_ldpreliminar', [
                'projeto' => $projeto,
                'ldpreliminarArray' => $ldpreliminarArray]);


            
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($capa);
            $mpdf->AddPage();
            $mpdf->WriteHTML($as);
            if(!empty($processoArray)){
                $mpdf->AddPage();
                $mpdf->WriteHTML($processo);
            }
            if(!empty($automacaoArray)){
                $mpdf->AddPage();
                $mpdf->WriteHTML($automacao);
            }
            if(!empty($instrumentacaoArray)){
                $mpdf->AddPage();
                $mpdf->WriteHTML($instrumentacao);
            }
            $mpdf->AddPage();
            $mpdf->WriteHTML($resumo);
            $mpdf->AddPage();
            $mpdf->WriteHTML($ld_preliminar);

            $mpdf->Output();
        }

    }
}

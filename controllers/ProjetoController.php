<?php

namespace app\controllers;

use Yii;
use app\models\Projeto;
use app\models\search\LdpreliminarSearch;
use app\models\Cliente;
use app\models\Atividademodelo;
use app\models\Contato;
use app\models\ProjetoExecutante;
use app\models\Escopo;
use app\models\Log;
use app\models\search\EscopoSearch; 
use app\models\search\ProjetoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use \Datetime;
use yii\helpers\Url;
use app\models\Documento;
use app\models\RevisaoProjeto;
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
        $model->rev_proposta =  0;
        $model->contrato='CT 4600015210';
        $searchModel = new ProjetoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model->criador_projeto_id = Yii::$app->user->getId();

        if(isset($_GET['pagination'])) $dataProvider->pagination = false;

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

        $status_geral = Yii::$app->db->createCommand('SELECT id, status FROM status_geral')->queryAll();
        $listStatusGeral = ArrayHelper::map($status_geral,'id','status');

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
                
                $identificador = "";
                if(isset($projeto->is_conceitual)){ $identificador = "PCO"; }
                if(isset($projeto->is_basico)){ $identificador = "PBA"; }
                if(isset($projeto->is_detalhamento)){ $identificador = "PDE"; }
                if(isset($projeto->is_configuracao)){ $identificador = "CFG"; }
                if(isset($projeto->is_detalhamento) && isset($projeto->is_configuracao)){$identificador = "PDC"; }
                if(isset($projeto->is_servico)){ $identificador = "SRV"; }                

                if($model->tipo == "P"){
                    $model->proposta = 'PTC'.'-'.$model->codigo.'-SRV-'.$identificador.'-'.$model->site.'-'.$model->rev_proposta;
                }
                else{
                    $model->proposta = 'AS'.'-'.$model->codigo.'-'.$identificador.'-'.$model->site.'-'.explode("-",$model->nome)[1].'_'.$model->rev_proposta;
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

                
                $model->resumo_observacoes ='1- O valor desta proposta refere-se ao número de horas previstas na tabela do ANEXO I;                                                     
2 - As condições e valores dessa proposta estão de acordo com o contrato N° 4600015210 firmado entre a BRASKEM e a HCN Automação;                                                      
3 - Esta AS é válida por 30 dias, contados da data da sua emissão;                                                      
4 - Em caso de aprovação desta proposta, favor enviar e-mail para helder@hcnautomacao.com.br ou contato telefônico no número 71-98867-3010                                                      
     para esclarecimentos da emissão do pedido de compra, e posteriormente para andamento dos serviços.                                                     
';

                if($model->save()){     
                    if($_POST['Projeto']['tipo'] == "A"){

                            $countCoordAdm = 0;
                                                        
                            Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id IN(SELECT atividademodelo.id FROM atividademodelo WHERE disciplina_id=1) AND projeto_id='.$model->id)->execute();
                            
                            if($model->is_basico){
                             Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id  IN(SELECT atividademodelo.id FROM atividademodelo WHERE (is_basico=1) AND disciplina_id=1) AND ((horas_tp=null OR horas_tp="") AND (horas_ej=null OR horas_ej="") AND (horas_ep=null OR horas_ep="") AND (horas_es=null OR horas_es="" OR horas_es=0) AND (horas_ee=null OR horas_ee="")) AND projeto_id='.$model->id)->execute();

                            }

                            if($model->is_detalhamento)
                             Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id  IN(SELECT atividademodelo.id FROM atividademodelo WHERE (is_detalhamento=1) AND disciplina_id=1) AND ((horas_tp=null OR horas_tp="") AND (horas_ej=null OR horas_ej="") AND (horas_ep=null OR horas_ep="") AND (horas_es=null OR horas_es="" OR horas_es=0) AND (horas_ee=null OR horas_ee="")) AND projeto_id='.$model->id )->execute();

                            if($model->is_configuracao)
                             Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id  IN(SELECT atividademodelo.id FROM atividademodelo WHERE (is_configuracao=1) AND disciplina_id=1) AND ((horas_tp=null OR horas_tp="") AND (horas_ej=null OR horas_ej="") AND (horas_ep=null OR horas_ep="") AND (horas_es=null OR horas_es="" OR horas_es=0) AND (horas_ee=null OR horas_ee="")) AND projeto_id='.$model->id)->execute();

                            $condition_query = '';
                            $first = 0;
                            if($model->is_conceitual){
                                if($first == 0){
                                    $condition_query .= 'is_conceitual=1';
                                    $first = 1;
                                }
                                else{
                                    $condition_query .= ' OR is_conceitual=1';                                    
                                }
                            }
                            else if($model->is_basico){
                                if($first == 0){
                                    $condition_query .= 'is_basico=1';
                                    $first = 1;
                                }
                                else{
                                    $condition_query .= ' OR is_basico=1';                                    
                                }
                            }
                            else if($model->is_detalhamento){
                                if($first == 0){
                                    $condition_query .= 'is_detalhamento=1';
                                    $first = 1;
                                }
                                else{
                                    $condition_query .= ' OR is_detalhamento=1';                                    
                                }
                            }
                            else if($model->is_configuracao){
                                if($first == 0){
                                    $condition_query .= 'is_configuracao=1';
                                    $first = 1;
                                }
                                else{
                                    $condition_query .= ' OR is_configuracao=1';                                    
                                }
                            }
                            else if($model->is_servico){
                                if($first == 0){
                                    $condition_query .= 'is_servico=1';
                                    $first = 1;
                                }
                                else{
                                    $condition_query .= ' OR is_servico=1';                                    
                                }
                            }
                            else{

                            }                            

                            $codigos = '';
                            $condition_code = '';
                            if (!empty($_POST['Codigos'])) {
                                $codigos = '(';
                            }
                            if(isset($_POST['Codigos'])){
                                foreach ($_POST['Codigos'] as $key => $cod) {
                                    $codigos .= '"'.$cod.'",';
                                }    
                            }
                            

                            if (!empty($_POST['Codigos'])) {
                                $codigos = rtrim($codigos,',');
                                $codigos .= ')';
                                $condition_code = ' AND codigo IN '.$codigos.' OR ((codigo IS NULL OR codigo="") AND '.$condition_query.')';
                            }

                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE disciplina_id = 1 AND isPrioritaria=1 AND '.$condition_query.$condition_code)->queryAll();
                            
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
                                                                        
                    }
                

                
                $helder_exists = 0;
                if(isset($_POST['ProjetoExecutante'])){
                   foreach ($_POST['ProjetoExecutante'] as $key => $proExe) {
                       $proExeModel = new ProjetoExecutante();
                       $proExeModel->projeto_id = $model->id;
                       $proExeModel->executante_id = $proExe;

                       $proExeModel->save();
                       if($proExe==61){
                            $helder_exists = 1;
                            Yii::$app->db->createCommand('UPDATE escopo SET exe_es_id=61 WHERE projeto_id='.$model->id.' AND nome="Coordenação e Administração"')->execute();

                            Yii::$app->db->createCommand('UPDATE escopo SET exe_es_id=61 WHERE projeto_id='.$model->id.' AND nome="Supervisão"')->execute();
                       }
                   }
                }

                //se Helder nao está no projeto, colocar o HCN e setar Coord e Admin para ele
                if($helder_exists==0){
                    $proExeModel = new ProjetoExecutante();
                    $proExeModel->projeto_id = $model->id;
                    $proExeModel->executante_id = Yii::$app->db->createCommand('SELECT id FROM user WHERE nome="HCN"')->queryScalar();

                    $proExeModel->save();

                    Yii::$app->db->createCommand('UPDATE escopo SET exe_es_id='.$proExeModel->executante_id.' WHERE projeto_id='.$model->id.' AND nome="Coordenação e Administração"')->execute();

                    Yii::$app->db->createCommand('UPDATE escopo SET exe_es_id='.$proExeModel->executante_id.' WHERE projeto_id='.$model->id.' AND nome="Supervisão"')->execute();
                }

                if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante'])){
                    $user_nome = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.Yii::$app->user->getId())->queryScalar();
                    $logModel = new Log();
                    $logModel->user_id = Yii::$app->user->getId();
                    $logModel->descricao = $user_nome.' criou o projeto '.$model->nome;
                    $logModel->data = Date('Y-m-d H:i:s');
                    if(!$logModel->save()){
                        print_r($logModel->getErrors());
                        die();
                    }
                }

                $revisaoModel = new RevisaoProjeto;
                $revisaoModel->projeto_id = $model->id;
                $revisaoModel->data = date('Y-m-d');
                $revisaoModel->descricao = 'Emissão Inicial';
                $revisaoModel->por = 'HCN';
                $revisaoModel->save(); 

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
                'listStatusGeral' => $listStatusGeral,
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

        $status_geral = Yii::$app->db->createCommand('SELECT id, status FROM status_geral')->queryAll();
        $listStatusGeral = ArrayHelper::map($status_geral,'id','status');


        $atividades_projeto = Yii::$app->db->createCommand('SELECT DISTINCT disciplina_id, is_conceitual, is_basico, is_detalhamento, is_configuracao, is_servico FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id = atividademodelo.id WHERE projeto_id='.$model->id)->queryAll();


          $condition_query = "";
          if(!empty($atividades_projeto)){
            $condition_query = "WHERE ";
          }

          foreach ($atividades_projeto as $key => $atv_proj) {            
             $condition_query .= '(disciplina_id = '.$atv_proj['disciplina_id'].' AND (is_conceitual=1 OR is_basico=1 OR is_detalhamento=1 OR is_configuracao=1 OR is_servico=1))';

             if($key != count($atividades_projeto) - 1)
               $condition_query .= ' OR ';
          }
     
        $atividadesProjeto = Yii::$app->db->createCommand('SELECT * FROM atividademodelo '.$condition_query)->queryAll();
        $listAtividadesProjeto = ArrayHelper::map($atividadesProjeto,'id','nome');

        $escopoArray = Yii::$app->db->createCommand('SELECT * FROM atividademodelo JOIN escopo  ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$model->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();


        $executantes = Yii::$app->db->createCommand('SELECT usuario_id, nome FROM executante JOIN user ON executante.usuario_id = user.id')->queryAll();
        $listExecutantes = ArrayHelper::map($executantes,'usuario_id','nome');

        
        $searchEscopo = new EscopoSearch();
        $escopoDataProvider = $searchEscopo->search(Yii::$app->request->queryParams);
        $escopoDataProvider->query->join('join','atividademodelo', 'atividademodelo.id=escopo.atividademodelo_id')->where('projeto_id='.$model->id);

        $caId_1=0;$caId_2=0;$caId_3=0;
        $tot_1=0;$tot_2=0;$tot_3=0;
        $totSuper_1=0;
         //atualizando os valores de hora e executante do escopo
        if(isset($_POST['Escopo'])){            
            //se nao_editavel nao for 1, nao permitir edição
            if($model->nao_editavel==1 && $_POST['Projeto']['nao_editavel']==1){
               return $this->redirect(['update', 'id' => $model->id]); 
            }

            $totalHoras = 0;
            $valorProposta = 0;
            

            $valor_tp = Yii::$app->db->createCommand('SELECT valor_hora FROM tipo_executante WHERE id=1')->queryScalar();
            $valor_ej = Yii::$app->db->createCommand('SELECT valor_hora FROM tipo_executante WHERE id=2')->queryScalar();
            $valor_ep = Yii::$app->db->createCommand('SELECT valor_hora FROM tipo_executante WHERE id=3')->queryScalar();
            $valor_es = Yii::$app->db->createCommand('SELECT valor_hora FROM tipo_executante WHERE id=4')->queryScalar();
            $valor_ee = Yii::$app->db->createCommand('SELECT valor_hora FROM tipo_executante WHERE id=5')->queryScalar();

            foreach ($_POST['Escopo'] as $key => $esc) {

               
                if(empty($esc['horas_tp'] )) $esc['horas_tp'] = 0;
                if(empty($esc['horas_ej'] )) $esc['horas_ej'] = 0;
                if(empty($esc['horas_ep'] )) $esc['horas_ep'] = 0;
                if(empty($esc['horas_es'] )) $esc['horas_es'] = 0;
                if(empty($esc['horas_ee'] )) $esc['horas_ee'] = 0;

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

                //busca coordenação e adminstração
                $coordEsup_1 = Yii::$app->db->createCommand('SELECT escopo.nome FROM escopo JOIN atividademodelo ON atividademodelo.id=escopo.atividademodelo_id WHERE escopo.id='.$key.' AND disciplina_id=1')->queryScalar();
               /* $coordEAdm_2 = Yii::$app->db->createCommand('SELECT escopo.nome FROM escopo JOIN atividademodelo ON atividademodelo.id=escopo.atividademodelo_id WHERE escopo.id='.$key.' AND disciplina_id=2')->queryScalar();
                $coordEAdm_3 = Yii::$app->db->createCommand('SELECT escopo.nome FROM escopo JOIN atividademodelo ON atividademodelo.id=escopo.atividademodelo_id WHERE escopo.id='.$key.' AND disciplina_id=3')->queryScalar();*/

                if(Yii::$app->db->createCommand('SELECT atividademodelo.disciplina_id FROM escopo JOIN atividademodelo ON atividademodelo.id=escopo.atividademodelo_id WHERE escopo.id='.$key)->queryScalar()==1){
                    $tot_1 += $esc['horas_tp'] + $esc['horas_ej']+ $esc['horas_ep']+ $esc['horas_es']+ $esc['horas_ee'];
                }
                if(Yii::$app->db->createCommand('SELECT atividademodelo.disciplina_id FROM escopo JOIN atividademodelo ON atividademodelo.id=escopo.atividademodelo_id WHERE escopo.id='.$key)->queryScalar()==2){
                    $tot_2 += $esc['horas_tp'] + $esc['horas_ej']+ $esc['horas_ep']+ $esc['horas_es']+ $esc['horas_ee'];
                }
                if(Yii::$app->db->createCommand('SELECT atividademodelo.disciplina_id FROM escopo JOIN atividademodelo ON atividademodelo.id=escopo.atividademodelo_id WHERE escopo.id='.$key)->queryScalar()==3){
                    $tot_3 += $esc['horas_tp'] + $esc['horas_ej']+ $esc['horas_ep']+ $esc['horas_es']+ $esc['horas_ee'];
                }
                if($coordEsup_1=='Coordenação e Administração'){
                   $tot_1 -= $esc['horas_es'];
                   $caId_1 = $key;                   
                }
                /*if($coordEAdm_2=='Coordenação e Administração'){
                    $tot_2 -= $esc['horas_es'];
                    $caId_2 = $key;
                }
                if($coordEAdm_3=='Coordenação e Administração'){
                    $tot_3 -= $esc['horas_es'];
                    $caId_3 = $key;
                }*/
                
                if($coordEsup_1=='Supervisão'){                   
                   $tot_1 -= $esc['horas_es'];
                   // $suId_1 = $key; 
                }
            }            
                    
            $valorProposta = $valorProposta + $model->vl_km + $model->vl_taxi + $model->vl_passagem_aerea + $model->vl_hospedagem;

            
           $model->total_horas = $totalHoras;
            
            foreach ($_POST['Escopo'] as $key => $esc) {

                $escopo = Escopo::findIdentity($key);

                if(!empty($escopo)){                   
                    $escopo->setAttributes($_POST['Escopo'][$key]);
                    
                    $escopo->horas_saldo = $escopo->horas_tp + $escopo->horas_ej + $escopo->horas_ep + $escopo->horas_es + $escopo->horas_ee - ($escopo->executado_tp + $escopo->executado_ej + $escopo->executado_ep + $escopo->executado_es + $escopo->executado_ee);

                    if($escopo->nome=="Coordenação e Administração"){
                        $escopo->horas_saldo = round($escopo->horas_saldo);
                    }

                    if($escopo->nome=="Supervisão"){
                        $escopo->horas_saldo = round($escopo->horas_saldo);
                    }
                    //Coordenação e Administração
                    if($key==$caId_1){
                        $escopo->horas_es = $tot_1;                        
                    }
                    if($key==$caId_2){
                        $escopo->horas_es = $tot_2;
                    }
                    if($key==$caId_3){
                        $escopo->horas_es = $tot_3;
                    }

                    //supervisão
                    /*if($key==$suId_1){
                        $escopo->horas_es = $tot_1;                        
                    }*/


                    if(!$escopo->save()){
                        print_r($escopo->getErrors());
                        print_r($escopo->executado);
                        die();
                    }
                    
                }
               
            }
            //salva resumo e outras abas
            if(isset($_POST['Projeto'])){                
                //se nao_editavel nao for 1, nao permitir edição
                if($model->nao_editavel==1 && $_POST['Projeto']['nao_editavel']==1){
                   return $this->redirect(['update', 'id' => $model->id]); 
                }

                //atualiza status geral
                if($model->as_aprovada==0 && $_POST['Projeto']['as_aprovada']==1){                  
                    Yii::$app->db->createCommand('UPDATE projeto SET status_geral=3 WHERE id='.$model->id)->execute();

                    $emails_executantes = Yii::$app->db->createCommand('SELECT email FROM user JOIN projeto_executante ON user.id=projeto_executante.executante_id WHERE projeto_executante.projeto_id='.$model->id)->queryAll(); 

                    $assunto = $model->nome.": AS Aprovada";
                    $corpoEmail = "A AS referente ao ".$model->nome." foi aprovada.";

                    foreach ($emails_executantes as $key => $email_exe) {
                        try{
                            Yii::$app->mailer->compose()
                            ->setFrom('hcnautomacaoweb@gmail.com')
                            ->setTo(trim($email_exe['email']))
                            ->setSubject($assunto)
                            ->setTextBody($corpoEmail)
                            ->send();
                        }
                        catch(Exception $e){
                            return $e;
                        }
                    }

                }
                                                 

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

                 //$model->nota_geral = $_POST['Projeto']['nota_geral'];
                if(isset($_POST['Projeto']['resumo_escopo']))
                    $model->resumo_escopo = $_POST['Projeto']['resumo_escopo'];
                if(isset($_POST['Projeto']['resumo_exclusoes']))
                    $model->resumo_exclusoes = $_POST['Projeto']['resumo_exclusoes'];
                if(isset($_POST['Projeto']['resumo_premissas']))
                    $model->resumo_premissas = $_POST['Projeto']['resumo_premissas'];
                if(isset($_POST['Projeto']['resumo_restricoes']))
                    $model->resumo_restricoes = $_POST['Projeto']['resumo_restricoes'];
                if(isset($_POST['Projeto']['resumo_normas']))
                    $model->resumo_normas = $_POST['Projeto']['resumo_normas'];
                if(isset($_POST['Projeto']['resumo_documentos']))
                    $model->resumo_documentos = $_POST['Projeto']['resumo_documentos'];
                if(isset($_POST['Projeto']['resumo_itens']))
                    $model->resumo_itens = $_POST['Projeto']['resumo_itens'];
                if(isset($_POST['Projeto']['resumo_prazo']))
                    $model->resumo_prazo = $_POST['Projeto']['resumo_prazo'];
                if(isset($_POST['Projeto']['resumo_observacoes']))
                    $model->resumo_observacoes = $_POST['Projeto']['resumo_observacoes'];
                $model->save();


                if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante']) && Yii::$app->db->createCommand('SELECT cargo FROM executante WHERE usuario_id='.Yii::$app->user->id)->queryScalar() == NULL){
                    //coloca o status do projeto como Emitir AS
                    Yii::$app->db->createCommand('UPDATE projeto SET status=6 WHERE id='.$model->id)->execute();
                }
                Yii::$app->db->createCommand('UPDATE projeto SET total_horas='.$totalHoras.', valor_proposta='.$valorProposta.' WHERE id='.$model->id)->execute();
            }
           
        }

          if ($model->load(Yii::$app->request->post())) {
            try{
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                $model->setAttributes($_POST['Projeto']);      
                /*if($model->tipo == "P"){
                    $model->proposta = 'PTC'.'-'.$model->codigo.'-'.$model->site.'-'.$model->rev_proposta;
                }
                else{
                    $model->proposta = 'AS'.'-'.$model->codigo.'-'.$model->site.'-'.preg_replace('/[^0-9]/', '', $model->nome);
                } */
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
                $perc = $model->perc_coord_adm/100;
                // $percSupervisao = $model->perc_supervisao/100;
                
                 //atualiza valor de coordenação e adminstração
            Yii::$app->db->createCommand('UPDATE escopo SET horas_es='.$tot_1*$perc.', horas_saldo='.round($tot_1*$perc).' WHERE id='.$caId_1)->execute();
            Yii::$app->db->createCommand('UPDATE escopo SET horas_es='.$tot_2*$perc.', horas_saldo='.round($tot_2*$perc).' WHERE id='.$caId_2)->execute();
            Yii::$app->db->createCommand('UPDATE escopo SET horas_es='.$tot_3*$perc.', horas_saldo='.round($tot_3*$perc).' WHERE id='.$caId_3)->execute();

            //atualiza valor de supervisão
            // Yii::$app->db->createCommand('UPDATE escopo SET horas_es='.$tot_1*$percSupervisao.', horas_saldo='.round($tot_1*$percSupervisao).' WHERE id='.$suId_1)->execute();

                if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante'])){
                    $user_nome = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.Yii::$app->user->getId())->queryScalar();
                    $logModel = new Log();
                    $logModel->user_id = Yii::$app->user->getId();
                    $logModel->descricao = $user_nome.' atualizou o projeto '.$model->nome;
                    $logModel->data = Date('Y-m-d H:i:s');
                    if(!$logModel->save()){
                        print_r($logModel->getErrors());
                        die();
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
            return $this->render('update', [
                'model' => $model,
                'listClientes' => $listClientes,
                'listContatos' => $listContatos,
                'listEscopo' => $listEscopo,
                'listSites' => $listSites,
                'listNomes' => $listNomes,
                'listStatus' => $listStatus,
                'listStatusGeral' => $listStatusGeral,
                'listDisciplina' => $listDisciplina,
                'listAtividadesProjeto' => $listAtividadesProjeto,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'escopoDataProvider' => $escopoDataProvider,
                'searchEscopo' => $searchEscopo,
                'escopoArray' => $escopoArray,
                /*'listExecutantes_tp' => $listExecutantes_tp,
                'listExecutantes_ej' => $listExecutantes_ej,
                'listExecutantes_ep' => $listExecutantes_ep,
                'listExecutantes_es' => $listExecutantes_es,
                'listExecutantes_ee' => $listExecutantes_ee,*/
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
        $projeto = Yii::$app->db->createCommand('SELECT id, nome FROM projeto WHERE id='.$id)->queryOne();
        if(empty($projeto['id'])){
        }
        $this->findModel($id)->delete();

        if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante'])){
            $user_nome = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.Yii::$app->user->getId())->queryScalar();
            $logModel = new Log();
            $logModel->user_id = Yii::$app->user->getId();
            $logModel->descricao = $user_nome.' excluiu o projeto '.$projeto['nome'];
            $logModel->data = Date('Y-m-d H:i:s');
            if(!$logModel->save()){
                print_r($logModel->getErrors());
                die();
            }
        }

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
                $idExecutante = Yii::$app->request->post()['idExecutante'];
                if(!empty($idExecutante)){
                    echo json_encode(Yii::$app->db->createCommand('SELECT qtd_km_dia, vl_km FROM executante WHERE usuario_id=61')->queryOne()); 
                }
                else{
                    echo json_encode(Yii::$app->db->createCommand('SELECT qtd_km_dia, vl_km FROM executante WHERE usuario_id=61')->queryOne());  
                }
        }
        
    }

    public function actionGerarbm()
    {
        if($_GET['id']){
            $projeto = Projeto::findOne($_GET['id']);
            $escopos = Yii::$app->db->createCommand('SELECT SUM(horas_tp) h_tp,SUM(horas_ej) h_ej,SUM(horas_ep) h_ep,SUM(horas_es) h_es,SUM(horas_ee) h_ee, escopo.nome as nomeE FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$projeto->id)->queryOne();

            $tipo_exec = Yii::$app->db->createCommand('SELECT * FROM tipo_executante')->queryAll();


            $bm = $this->renderPartial('relatorio/_bm', [
                'projeto' => $projeto,
                'escopos' => $escopos,
                'tipo_exec' => $tipo_exec]);


            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($bm);   
            $mpdf->Output();         


        }
    }
    public function actionGerarrelatorio()
    {

        if($_GET['id']){
            $projeto = Projeto::findOne($_GET['id']);        

            //atualiza status geral
            Yii::$app->db->createCommand('UPDATE projeto SET status_geral=2 WHERE id='.$projeto->id)->execute(); 
            Yii::$app->db->createCommand('UPDATE projeto SET as_aprovada=0 WHERE id='.$projeto->id)->execute();  
            
            $processoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=2 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
            $processoConceitualArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=2 AND is_conceitual=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
            $processoBasicoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=2 AND is_basico=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
            $processoDetalhamentoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=2 AND is_detalhamento=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
            $processoConfiguracaoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=2 AND is_configuracao=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();

            $automacaoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
            $automacaoConceitualArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=1 AND is_conceitual=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
            $automacaoBasicoArray = Yii::$app->db->createCommand('SELECT * FROM escopo LEFT JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=1 AND is_basico=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
            $automacaoDetalhamentoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=1 AND is_detalhamento=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
           $automacaoConfiguracaoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=1 AND is_configuracao=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
           
           if($projeto->is_detalhamento){
                $automacaoConfiguracaoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=1 AND is_configuracao=1 AND is_detalhamento=0 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
           }            

            $automacaoServicoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=1 AND is_servico=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();

            $instrumentacaoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=3 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
            $instrumentacaoConceitualArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=3 AND is_conceitual=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
            $instrumentacaoBasicoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=3 AND is_basico=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
            $instrumentacaoDetalhamentoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=3 AND is_detalhamento=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
            $instrumentacaoConfiguracaoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=3 AND is_configuracao=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
            $instrumentacaoServicoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=3 AND is_servico=1 AND projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
            
            if($projeto->tipo == "P"){            
                $atividadeArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();
            }

            $escopos = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$projeto->id.' ORDER BY isEntregavel ASC, ordem ASC')->queryAll();

            $ldpreliminarArray = Yii::$app->db->createCommand('SELECT * FROM ld_preliminar')->queryAll();
            
            $conceitual = '';
            $basico = '';
            $detalhamento = '';
            $config = '';
            $servico = '';

            $index = 1;
            $arrayIndex = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX'];
 
           
            foreach ($escopos as $key => $escopo) {
                if($projeto->is_conceitual==1)
                    $conceitual = 'checked="checked"';
                if($projeto->is_basico==1)
                    $basico = 'checked="checked"';
                if($projeto->is_detalhamento==1)
                    $detalhamento = 'checked="checked"';
                if($projeto->is_configuracao==1)
                    $config = 'checked="checked"';
                if($projeto->is_servico==1)
                    $servico = 'checked="checked"';
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
                // 'diagrama' => $diagramaArray,
                'conceitual' => $conceitual,
                'basico' => $basico,
                'detalhamento' => $detalhamento,
                'config' => $config,
                'servico' => $servico
            ]);

            if($projeto->tipo == "A"){
                
                $automacaoConceitual = $this->renderPartial('relatorio/_automacao_conceitual', [
                    'projeto' => $projeto,
                    'escopos' => $automacaoConceitualArray,
                    'index' => $arrayIndex[$index]]);
                if($projeto->is_conceitual) $index++;
                $automacaoBasico = $this->renderPartial('relatorio/_automacao_basico', [
                    'projeto' => $projeto,
                    'escopos' => $automacaoBasicoArray,
                    'index' => $arrayIndex[$index]]);
                if($projeto->is_basico) $index++;
                $automacaoDetalhamento = $this->renderPartial('relatorio/_automacao_detalhamento', [
                    'projeto' => $projeto,
                    'escopos' => $automacaoDetalhamentoArray,
                    'index' => $arrayIndex[$index]]);
                if($projeto->is_detalhamento) $index++;
                $automacaoConfiguracao = $this->renderPartial('relatorio/_automacao_configuracao', [
                    'projeto' => $projeto,
                    'escopos' => $automacaoConfiguracaoArray,
                    'index' => $arrayIndex[$index]]);
                if($projeto->is_configuracao) $index++;
                $automacaoServico = $this->renderPartial('relatorio/_automacao_servico', [
                    'projeto' => $projeto,
                    'escopos' => $automacaoServicoArray,
                    'index' => $arrayIndex[$index]]);
                if($projeto->is_servico) $index++;
                
            }
            if($projeto->tipo == "P"){
                $atividade = $this->renderPartial('relatorio/_atividade', [
                    'projeto' => $projeto,
                    'escopos' => $atividadeArray,
                    'index' => $arrayIndex[$index]]);
                if(!empty($atividadeArray)) $index++;
            }
            $resumo = $this->renderPartial('relatorio/_resumo', [
                'projeto' => $projeto,
                'index' => $arrayIndex[$index]]);
            $index++;

            $ld_preliminar = $this->renderPartial('relatorio/_ldpreliminar', [
                'projeto' => $projeto,
                'ldpreliminarArray' => $ldpreliminarArray,
                'index' => $arrayIndex[$index]]);


            if (!file_exists('uploaded-files/'.$projeto['id'])) {
                mkdir('uploaded-files/'.$projeto['id'], 0777, true);
            }

            $mpdf = new \Mpdf\Mpdf();

            $mpdf->WriteHTML($capa);

            $cargo = Yii::$app->db->createCommand('SELECT cargo FROM executante WHERE usuario_id='.Yii::$app->user->id)->queryScalar();
            
            if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin']) || (isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante']) && $cargo==2)){
                $mpdf->AddPage();
                $mpdf->WriteHTML($as);
            }
            if($projeto->tipo == "A"){
                
                if($projeto->is_conceitual){
                    $mpdf->AddPage();
                    $mpdf->WriteHTML($automacaoConceitual);
                }
                if($projeto->is_basico){
                    $mpdf->AddPage();
                    $mpdf->WriteHTML($automacaoBasico);
                }
                if($projeto->is_detalhamento){
                    $mpdf->AddPage();
                    $mpdf->WriteHTML($automacaoDetalhamento);
                }
                if($projeto->is_configuracao){
                    $mpdf->AddPage();
                    $mpdf->WriteHTML($automacaoConfiguracao);
                }
                if($projeto->is_servico){
                    $mpdf->AddPage();
                    $mpdf->WriteHTML($automacaoServico);
                }

                
            }
            if($projeto->tipo == "P"){
                if(!empty($atividadeArray)){
                    $mpdf->AddPage();
                    $mpdf->WriteHTML($atividade);
                }
            }
            if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin']) || (isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante']) && $cargo==2)){
                $mpdf->AddPage();
                $mpdf->WriteHTML($resumo);
            }
            if(!empty($ldpreliminarArray) && isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin'])){
                $mpdf->AddPage();
                $mpdf->WriteHTML($ld_preliminar);
            }
            
            if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin']) || (isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante']) && $cargo==2)){
                $mpdf->Output('uploaded-files/'.$projeto['id'].'/'.$projeto['proposta'].'.pdf', 'F');   
            }  
            $mpdf->Output('uploaded-files/'.$projeto['id'].'/'.$projeto['proposta'].'.pdf', 'I');    

            $existsFile = Yii::$app->db->createCommand('SELECT id FROM documento WHERE nome="'.$projeto['proposta'].'.pdf"')->queryScalar();

            if(empty($existsFile)){
                //cria o registro do arquivo
                $doc = new Documento();
                $doc->projeto_id = $projeto['id'];
                $doc->nome = $projeto['proposta'].'.pdf';
                $doc->revisao = 0;
                $doc->path = $projeto['proposta'].'.pdf';
                $doc->is_global = 0;
                $doc->save();
            }
        }

    }

    public function actionDeleteatividade(){
        if (Yii::$app->request->isAjax) {   
            $id = Yii::$app->request->post()['id'];

            try{
                Yii::$app->db->createCommand('DELETE FROM escopo WHERE id='.$id)->execute();
            }
            catch(Exception $e){
                return 'failed';
            }
            return 'success';
        }
    }

     public function actionAddatividadeavulsa(){
        if (Yii::$app->request->isAjax) {   
            $atvmodelo_ids = json_decode(Yii::$app->request->post()['atvmodelo_ids'], true);
            $projeto_id = Yii::$app->request->post()['projeto_id'];
           
            try{
                foreach ($atvmodelo_ids as $key => $atvid) {
                    $atv_modelo = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE id='.$atvid)->queryOne();
                
                    $escopo_model = new Escopo();
                    $escopo_model->projeto_id = $projeto_id;
                    $escopo_model->atividademodelo_id = $atv_modelo['id'];
                    $escopo_model->nome = $atv_modelo['nome'];
                    $escopo_model->descricao = $atv_modelo['nome'];
                    $escopo_model->save();
                }
                
            }
            catch(Exception $e){
                return 'failed';
            }
            return 'success';
        }
    }
    
    public function actionEnviaremail(){
        if (Yii::$app->request->isAjax) {   
            $remetentes = Yii::$app->request->post()['remetentes'];
            $assunto = Yii::$app->request->post()['assunto'];
            $corpoEmail = Yii::$app->request->post()['corpoEmail'];
            $nomeArquivo =  Yii::$app->request->post()['nomeArquivo'];
            $projeto_id = Yii::$app->request->post()['projeto_id'];

            Yii::$app->db->createCommand('UPDATE projeto SET status=5, nao_editavel=1 WHERE id = '.$projeto_id)->execute();

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

    public function actionSalvarnotasgerais(){
        if (Yii::$app->request->isAjax) {               
            $projeto_id = Yii::$app->request->post()['projeto_id'];
            $nota_geral = Yii::$app->request->post()['nota_geral'];

            try{
                $model = $this->findModel($projeto_id);
                $model->nota_geral = $nota_geral;
                $model->save(); 
                return 'success';
            }
            catch(Exception $e){
                return $e;
            } 
        }       
    }

    public function actionSalvarrevisao(){
        if (Yii::$app->request->isAjax) {               
            $projeto_id = Yii::$app->request->post()['projeto_id'];
            $data = Yii::$app->request->post()['data'];
            $descricao = Yii::$app->request->post()['descricao'];
            $por = Yii::$app->request->post()['por'];

            try{
                $model = new RevisaoProjeto;
                $model->projeto_id = $projeto_id;
                $model->data = $data;
                $model->descricao = $descricao;
                $model->por = $por;
                $model->save(); 
                return 'success';
            }
            catch(Exception $e){
                return $e;
            } 
        }       
    }

    public function actionDeleterevisao(){
        if (Yii::$app->request->isAjax) {   
            $id = Yii::$app->request->post()['id'];

            try{
                Yii::$app->db->createCommand('DELETE FROM revisao_projeto WHERE id='.$id)->execute();
            }
            catch(Exception $e){
                return 'failed';
            }
            return 'success';
        }
    }

    public function actionExtrairinformacoes(){
        if (Yii::$app->request->isAjax) {
            if($_FILES['file']['name'] != '')  
             { 
                $temp = explode(".", $_FILES['file']['name']);
                $extension = end($temp);  
                  $allowed_type = array("pdf", "PDF");  
                  if(in_array($extension, $allowed_type))  
                   {                           
                        if (!is_dir(Yii::$app->basePath . '/web/uploaded-files/temp_files')) {
                            mkdir(Yii::$app->basePath . '/web/uploaded-files/temp_files');
                            FileHelper::createDirectory(Yii::$app->basePath . '/web/uploaded-files/temp_files', $mode = 0775, $recursive = true);
                        }

                       $path = Yii::$app->basePath . '/web/uploaded-files/temp_files/temp_frs.pdf'; 
                       if(move_uploaded_file($_FILES['file']['tmp_name'], $path))  
                       {  
                            //ler PDF
                            chmod($path, 0775);
                            $parser = new \Smalot\PdfParser\Parser();
                            $pdf = $parser->parseFile($path);
                             
                            $text = $pdf->getText();
                            $text = preg_replace('/\t+/', '', preg_replace('/\s\s+/', ' ', $text));
                            return $text;
                        }
                    }
            }
            
        }
    }

    public function actionGetidfromextrairinformacoes(){
        if (Yii::$app->request->isAjax) {
            $planta = Yii::$app->request->post()['planta'];

            return Yii::$app->db->createCommand('SELECT id FROM cliente WHERE site LIKE "%'.$planta.'%" ')->queryScalar();

            
        }
    }

    public function actionPreencheconjunto(){
        if (Yii::$app->request->isAjax) {
            $escopos = Yii::$app->request->post()['escopos'];

            $escopos = (json_decode($escopos));
            $condition_query = 'WHERE ';

            if(empty($escopos)){
                return "";
            }

            $first = 0;
            if(in_array(1, $escopos)){
                if($first == 0){
                    $condition_query .= 'is_conceitual=1';
                        $first = 1;
                }
                else{
                    $condition_query .= ' OR is_conceitual=1';                                    
                }
            }
            if(in_array(2, $escopos)){
                if($first == 0){
                    $condition_query .= 'is_basico=1';
                        $first = 1;
                }
                else{
                    $condition_query .= ' OR is_basico=1';                                    
                }
            }
            if(in_array(3, $escopos)){
                if($first == 0){
                    $condition_query .= 'is_detalhamento=1';
                        $first = 1;
                }
                else{
                    $condition_query .= ' OR is_detalhamento=1';                                    
                }
            }
            if(in_array(4, $escopos)){
                if($first == 0){
                    $condition_query .= 'is_configuracao=1';
                        $first = 1;
                }
                else{
                    $condition_query .= ' OR is_configuracao=1';                                    
                }
            }
            if(in_array(5, $escopos)){
                if($first == 0){
                    $condition_query .= 'is_servico=1';
                        $first = 1;
                }
                else{
                    $condition_query .= ' OR is_servico=1';                                    
                }
            }

            $conjuntos = Yii::$app->db->createCommand('SELECT DISTINCT codigo FROM atividademodelo '.$condition_query)->queryAll();

            $conjuntos_string = "";
            
            foreach ($conjuntos as $key => $con) {
                if(!empty($con['codigo'])){
                    $conjuntos_string .= $con['codigo'].',';
                }
            }

            return $conjuntos_string;
        }
    }
}

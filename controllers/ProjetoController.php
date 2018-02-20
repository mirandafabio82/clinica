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
                    $model->proposta = 'AS'.'-'.$model->codigo.'-'.$model->site.'-'.preg_replace('/[^0-9]/', '', $model->nome).'_'.$model->rev_proposta;
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

                $model->resumo_escopo = '1.1- Elaboração de projeto básico, com base no PROJETO CONCEITUAL BK-BA07-00200-PC-03-00003 Rev. 1 Migração para PLC do intertravamento de alta temperatura na purificação de eteno.
1.2- Implementar no PLC de segurança o intertravamento por alta temperatura (TAHH) respectivo das colunas de purificação de eteno C-2107, C-2109, C-2111, C-2112, C-2113 e C-2115, além do TAH da coluna C-2103.
1.3- Elaboração de MEMORA DE CÁLCULO SIL, considerando a instrumentação existe do alarme de TAHH respectivo das colunas de purificação de eteno C-2107, C-2109, C-2111, C-2112, C-2113 e C-2115, além do TAH da coluna C-2103, conforme solicitado pelo conceitual. Incluindo as sugestões de adequação das funções para atendimento do SIL requerido, (caso necessário).';
                $model->resumo_exclusoes = '2.1- Participação em reuniões de HAZOP e Analise de risco.
2.2- Consolidação do Projeto Coceitual.
2.3- Analise de consistência do projeto conceitual.
2.4- Avaliação ou instalação de instrumentos não especificados no Conceitual.
2.5- Especificação de novos instrumentos. O projeto contempla a avaliação dos instrumentos, existentes conforme solicitação do Conceitual.
2.6- Instalação de novas estruturas de PLC e painéis de interligação.
2.7- Emissão de Data Book em papel.';
                $model->resumo_premissas = '3.1- Levantamento de campo para verificação das características e modelos da instrumentação existe de temperatura e pressão das colunas C-2103, C-2107, C-2109, C-2111, C-2112, C-2113 e C-2115.
3.2- Participação em reuniões com a BRASKEM para definição de escopo e acompanhamento do projeto.
3.3- Todos os elementos de temperatura e pressão das colunas C-2103, C-2107, C-2109, C-2111, C-2112, C-2113 e C-2115 já estão configurados no PLC de segurança, assim como as válvulas HV-2001-2 (alimentação de eteno para a purificação) e HV-2113-8A (alimentação de eteno para o reator) e HV-2113-8B (envio do eteno para flare). 
3.4- O intertravamento deverá atender SIL 1 possuindo um PFD de 1,52E-2 com RRF de 66 com votação 2oo3 para cada nível de medição das colunas.
3.5- Para o caso especifico da coluna C-2103 que está fora de operação, o intertravamento deverá ser desenvolvido, porém mantido em hold e implementado caso a coluna seja recolocada em operação.';

                $model->resumo_normas = 'Padrão Normativo de Engenharia - PN-0502-00062 - Critérios para Projetos de Instrumentação
Sistemas Instrumentados de Segurança PNE-80-00087';

                $model->resumo_documentos = 'PROJETO CONCEITUAL BK-BA07-00200-PC-03-00003 Rev. 1 Migração para PLC do intertravamento de alta temperatura na purificação de eteno.';
                $model->resumo_observacoes ='1- O valor desta proposta refere-se ao número de horas previstas na tabela do ANEXO I;                                                     
2 - As condições e valores dessa proposta estão de acordo com o contrato N° 4600008691/2015 firmado entre a BRASKEM e a HCN Automação;                                                      
3 - Esta AS é válida por 30 dias, contados da data da sua emissão;                                                      
4 - Em caso de aprovação desta proposta, favor enviar e-mail para helder@hcnautomacao.com.br ou contato telefônico no número 71-98867-3010                                                      
     para esclarecimentos da emissão do pedido de compra, e posteriormente para andamento dos serviços.                                                     
';

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

        $escopoArray = Yii::$app->db->createCommand('SELECT * FROM atividademodelo JOIN escopo  ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$model->id.' ORDER BY isEntregavel ASC, atividademodelo.id ASC')->queryAll();

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

        $caId_1=0;$caId_2=0;$caId_3=0;
        $tot_1=0;$tot_2=0;$tot_3=0;
         //atualizando os valores de hora e executante do escopo
        if(isset($_POST['Escopo'])){

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
                $coordEAdm_1 = Yii::$app->db->createCommand('SELECT escopo.nome FROM escopo JOIN atividademodelo ON atividademodelo.id=escopo.atividademodelo_id WHERE escopo.id='.$key.' AND disciplina_id=1')->queryScalar();
                $coordEAdm_2 = Yii::$app->db->createCommand('SELECT escopo.nome FROM escopo JOIN atividademodelo ON atividademodelo.id=escopo.atividademodelo_id WHERE escopo.id='.$key.' AND disciplina_id=2')->queryScalar();
                $coordEAdm_3 = Yii::$app->db->createCommand('SELECT escopo.nome FROM escopo JOIN atividademodelo ON atividademodelo.id=escopo.atividademodelo_id WHERE escopo.id='.$key.' AND disciplina_id=3')->queryScalar();

                if(Yii::$app->db->createCommand('SELECT atividademodelo.disciplina_id FROM escopo JOIN atividademodelo ON atividademodelo.id=escopo.atividademodelo_id WHERE escopo.id='.$key)->queryScalar()==1){
                    $tot_1 += $esc['horas_tp'] + $esc['horas_ej']+ $esc['horas_ep']+ $esc['horas_es']+ $esc['horas_ee'];
                }
                if(Yii::$app->db->createCommand('SELECT atividademodelo.disciplina_id FROM escopo JOIN atividademodelo ON atividademodelo.id=escopo.atividademodelo_id WHERE escopo.id='.$key)->queryScalar()==2){
                    $tot_2 += $esc['horas_tp'] + $esc['horas_ej']+ $esc['horas_ep']+ $esc['horas_es']+ $esc['horas_ee'];
                }
                if(Yii::$app->db->createCommand('SELECT atividademodelo.disciplina_id FROM escopo JOIN atividademodelo ON atividademodelo.id=escopo.atividademodelo_id WHERE escopo.id='.$key)->queryScalar()==3){
                    $tot_3 += $esc['horas_tp'] + $esc['horas_ej']+ $esc['horas_ep']+ $esc['horas_es']+ $esc['horas_ee'];
                }

                if($coordEAdm_1=='Coordenação e Administração'){
                   $tot_1 -= $esc['horas_es'];
                   $caId_1 = $key;
                }
                if($coordEAdm_2=='Coordenação e Administração'){
                    $tot_2 -= $esc['horas_es'];
                    $caId_2 = $key;
                }
                if($coordEAdm_3=='Coordenação e Administração'){
                    $tot_3 -= $esc['horas_es'];
                    $caId_3 = $key;
                }
                
            }            
          
           

            $valorProposta = $valorProposta + $model->vl_km;
            
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
                                
                               $existsNp = Yii::$app->db->createCommand('SELECT id FROM escopo WHERE atividademodelo_id='.$np.' AND projeto_id='.$model->id)->queryScalar();
                              
                               if(empty($existsNp)){
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
                
                 //atualiza valor de coordenação e adminstração
            Yii::$app->db->createCommand('UPDATE escopo SET horas_es='.$tot_1*$perc.' WHERE id='.$caId_1)->execute();
            Yii::$app->db->createCommand('UPDATE escopo SET horas_es='.$tot_2*$perc.' WHERE id='.$caId_2)->execute();
            Yii::$app->db->createCommand('UPDATE escopo SET horas_es='.$tot_3*$perc.' WHERE id='.$caId_3)->execute();
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
          
            
            $processoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=2 AND projeto_id='.$projeto->id)->queryAll();
            $automacaoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=1 AND projeto_id='.$projeto->id)->queryAll();
            $instrumentacaoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=3 AND projeto_id='.$projeto->id)->queryAll();

            $escopos = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$projeto->id)->queryAll();

            $ldpreliminarArray = Yii::$app->db->createCommand('SELECT * FROM ld_preliminar')->queryAll();
            
            $basico = '';
            $detalhamento = '';
            $config = '';
            $index = 1;
            $arrayIndex = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX'];

           
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
                'escopos' => $processoArray,
                'index' => $arrayIndex[$index]]);
            if(!empty($processoArray)) $index++;

            $automacao = $this->renderPartial('relatorio/_automacao', [
                'projeto' => $projeto,
                'escopos' => $automacaoArray,
                'index' => $arrayIndex[$index]]);
            if(!empty($automacaoArray)) $index++;

            $instrumentacao = $this->renderPartial('relatorio/_instrumentacao', [
                'projeto' => $projeto,
                'escopos' => $instrumentacaoArray,
                'index' => $arrayIndex[$index]]);
            if(!empty($instrumentacaoArray)) $index++;

            $resumo = $this->renderPartial('relatorio/_resumo', [
                'projeto' => $projeto,
                'index' => $arrayIndex[$index]]);
            $index++;

            $ld_preliminar = $this->renderPartial('relatorio/_ldpreliminar', [
                'projeto' => $projeto,
                'ldpreliminarArray' => $ldpreliminarArray,
                'index' => $arrayIndex[$index]]);


            
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

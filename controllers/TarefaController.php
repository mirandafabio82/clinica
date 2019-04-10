<?php

namespace app\controllers;

use Yii;
use app\models\Tarefa;
use app\models\search\TarefaSearch;
use app\models\Escopo;
use app\models\search\EscopoSearch;
use app\models\Bm;
use app\models\Bmescopo;
use app\models\BmExecutante;
use app\models\search\BmSearch;
use app\models\Projeto;
use app\models\search\ProjetoSearch;
use app\models\Log;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use \Datetime;

/**
 * TarefaController implements the CRUD actions for Tarefa model.
 */
class TarefaController extends Controller
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
    public function actionIndex($projeto_id=null, $executante_id=null)
    {       

        $escopoModel = new Escopo();        
        $searchModel = new ProjetoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        
        
        if(!isset($_POST['projeto']) || !isset($_POST['executante'])){
            $_POST['projeto'] = $projeto_id;
            $_POST['executante'] = $executante_id;  
        } 
                
        if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['contato'])) {
            $clienteId = Yii::$app->db->createCommand('SELECT cliente_id FROM contato WHERE usuario_id='.Yii::$app->user->getId())->queryScalar();

            $dataProvider->query->where('cliente_id='.$clienteId);
        }
        if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante']) && Yii::$app->db->createCommand('SELECT cargo FROM executante WHERE usuario_id='.Yii::$app->user->id)->queryScalar() == null){
            $projetos_id = Yii::$app->db->createCommand('SELECT projeto_id as id FROM projeto_executante WHERE executante_id='.Yii::$app->user->getId())->queryAll();

            foreach ($projetos_id as $key => $pid) {
                $dataProvider->query->orWhere('id='.$pid['id']);
            }
            
        }
        
        $status = Yii::$app->db->createCommand('SELECT id, status FROM escopo_status')->queryAll();
        $listStatus = ArrayHelper::map($status,'id','status');

        $executantes = Yii::$app->db->createCommand('SELECT usuario_id, nome FROM executante JOIN user ON executante.usuario_id = user.id')->queryAll();
        $listExecutantes = ArrayHelper::map($executantes,'usuario_id','nome');

        // $projetos = Yii::$app->db->createCommand('SELECT id, nome FROM projeto WHERE as_aprovada=1 ORDER BY id DESC')->queryAll();
        $projetos = Yii::$app->db->createCommand('SELECT DISTINCT
            projeto.id, 
            CONCAT(projeto.nome, " (",(
            SELECT DISTINCT
                CASE 
                    WHEN projeto.is_conceitual=1 THEN "PCO"
                    WHEN projeto.is_basico=1 THEN "PBA"
                    WHEN projeto.is_detalhamento=1 THEN "PDC"
                    WHEN projeto.is_detalhamento=1 AND projeto.is_configuracao=1 THEN "PDE"
                    WHEN projeto.is_configuracao=1 THEN "CFG"  
                    WHEN projeto.is_servico=1 THEN "SRV"
                    ELSE ""
                END
            
            FROM escopo
                INNER JOIN atividademodelo ON atividademodelo.id = escopo.atividademodelo_id                
            WHERE escopo.projeto_id = projeto.id
            LIMIT 1
            
             ), ")") AS nome
            
        FROM
            projeto
        INNER JOIN escopo ON escopo.projeto_id = projeto.ID
        WHERE
            as_aprovada = 1
        ORDER BY id DESC')->queryAll();
        $listProjetos = ArrayHelper::map($projetos,'id','nome');

        $executante_id = '';
        $isPost = 0;

        $projeto_selected = '';
        if(isset($_POST['executante']) && isset($_POST['projeto'])){    //filtro         
            $dataProvider->query->where('projeto.id = '.$_POST['projeto']);
            $executante_id = $_POST['executante'];
            $projeto_selected = $_POST['projeto'];
            $isPost = 1;
        }        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listStatus' => $listStatus,
            'listProjetos' => $listProjetos,
            'listExecutantes' => $listExecutantes,
            'escopoModel' => $escopoModel,
            'executante_id' => $executante_id,
            'isPost' => $isPost,
            'projeto_selected' => $projeto_selected,
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
        $model = new Tarefa();
        $model->data =  date('d/m/Y');
        $searchModel = new TarefaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $projetos = Yii::$app->db->createCommand('SELECT id, nome FROM projeto')->queryAll();
        $listProjetos = ArrayHelper::map($projetos,'id','nome');

        $executantes = Yii::$app->db->createCommand('SELECT usuario_id, nome FROM executante JOIN user ON executante.usuario_id = user.id')->queryAll();
        $listExecutantes = ArrayHelper::map($executantes,'usuario_id','nome');

        if($_POST){            
            $model->setAttributes($_POST['Tarefa']);
            if(!empty($_POST['Tarefa']['data'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Tarefa']['data']);          
                $model->data = date_format($dat, 'Y-m-d');
            }

            $model->save();

            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'listProjetos' => $listProjetos,
                'listProjetos' => $listProjetos,
                'listExecutantes' => $listExecutantes,
            ]);
        }
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
        $model = $this->findModel($id);
        if(isset($model->data))
            $model->data = date_format(DateTime::createFromFormat('Y-m-d', $model->data), 'd/m/Y');

        $searchModel = new TarefaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $projetos = Yii::$app->db->createCommand('SELECT id, nome FROM projeto')->queryAll();
        $listProjetos = ArrayHelper::map($projetos,'id','nome');

        $executantes = Yii::$app->db->createCommand('SELECT usuario_id, nome FROM executante JOIN user ON executante.usuario_id = user.id')->queryAll();
        $listExecutantes = ArrayHelper::map($executantes,'usuario_id','nome');

        if($_POST){
            $model->setAttributes($_POST['Tarefa']);


            if(isset($_POST['Tarefa']['data'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Tarefa']['data']);
                $model->data = date_format($dat, 'Y-m-d');
            }
            $model->save();

            return $this->redirect(['create']);
        }else {
            return $this->render('update', [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'listProjetos' => $listProjetos,
                'listExecutantes' => $listExecutantes,
            ]);
        }
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionGerarbm($projetoid, $km_consumida, $horas_adiantadas)
    {
        $projetoModel = Projeto::findIdentity($projetoid);

        //atualiza status geral
        Yii::$app->db->createCommand('UPDATE projeto SET status_geral=5 WHERE id='.$projetoid)->execute(); 
        
        $executadas = Yii::$app->db->createCommand('SELECT SUM(horas_ee_bm) ee_bm, SUM(horas_es_bm) es_bm,SUM(horas_ep_bm) ep_bm,SUM(horas_ej_bm) ej_bm,SUM(horas_tp_bm) tp_bm, SUM(horas_acumulada) h_acu, SUM(horas_saldo) h_saldo, SUM(adiantadas_tp) adiantadas_tp, SUM(adiantadas_ej) adiantadas_ej, SUM(adiantadas_ep) adiantadas_ep, SUM(adiantadas_es) adiantadas_es, SUM(adiantadas_ee) adiantadas_ee FROM escopo WHERE projeto_id='.$projetoid)->queryOne();
        
        $ultBM = Yii::$app->db->createCommand('SELECT ultimo_bm FROM config')->queryScalar();
        $ultBM = $ultBM+1;

        Yii::$app->db->createCommand('UPDATE config SET ultimo_bm ='.$ultBM)->execute();

        $numeroBmAtual = count(Yii::$app->db->createCommand('SELECT COUNT(id) FROM bm WHERE projeto_id='.$projetoid)->queryAll()) + 1;



        $acu_saldo = Yii::$app->db->createCommand('SELECT SUM(horas_acumulada) horas_acu, SUM(horas_saldo) h_saldo FROM escopo WHERE projeto_id='.$projetoid)->queryOne();

        
        //quantidade de bms do projeto
        $numbm = count(Yii::$app->db->createCommand('SELECT id FROM bm WHERE projeto_id='.$projetoModel->id)->queryAll()) + 1;

        $horasAS = Yii::$app->db->createCommand('SELECT SUM(horas_ee) h_ee, SUM(horas_es) h_es, SUM(horas_ep) h_ep, SUM(horas_ej) h_ej, SUM(horas_tp) h_tp FROM escopo WHERE projeto_id='.$projetoid)->queryOne();

        $totalHoras = $horasAS['h_ee']+$horasAS['h_es']+$horasAS['h_ep']+$horasAS['h_ej']+$horasAS['h_tp'];

        //horas adiantadas
        $adiantadasArr = explode(';', $horas_adiantadas);

        $adiantadas_ee_valor = 0;
        $adiantadas_es_valor = 0;
        $adiantadas_ep_valor = 0;
        $adiantadas_ej_valor = 0;
        $adiantadas_tp_valor = 0;

        foreach ($adiantadasArr as $key => $adiantada) {
            if(empty($adiantada)) continue;
            
            $tipo = explode('-', $adiantada)[0];
            $id = explode('-', $adiantada)[1];
            $valor = explode('-', $adiantada)[2];

            if($tipo=="adiantadas_ee") $adiantadas_ee_valor = $valor;
            if($tipo=="adiantadas_es") $adiantadas_es_valor = $valor;
            if($tipo=="adiantadas_ep") $adiantadas_ep_valor = $valor;
            if($tipo=="adiantadas_ej") $adiantadas_ej_valor = $valor;
            if($tipo=="adiantadas_tp") $adiantadas_tp_valor = $valor;

            $total_atual = Yii::$app->db->createCommand('SELECT '.$tipo.' FROM escopo WHERE id='.$id)->queryScalar();
            Yii::$app->db->createCommand('UPDATE escopo SET '.$tipo.'='.($total_atual+$valor).' WHERE id='.$id)->execute();
        }       

        
        //$percAcumulada = sprintf("%.2f",(($acu_saldo['horas_acu']+$executadas['ee_bm']+$executadas['es_bm']+$executadas['ep_bm']+$executadas['ej_bm']+$executadas['tp_bm']) * 100) / $totalHoras);

        $vl_km = Yii::$app->db->createCommand('SELECT vl_km FROM executante WHERE usuario_id=61')->queryScalar();
        
        $bmModel = new Bm();
        $bmModel->projeto_id = $projetoModel->id;
        $bmModel->contrato = $projetoModel->contrato;
        $bmModel->objeto = 'Automação';
        $bmModel->contratada = "HCN AUTOMAÇÃO LTDA";
        $bmModel->cnpj = "10.486.000/0002-88";
        $bmModel->data = Date('Y-m-d');
        $bmModel->contato = "HÉLDER CÂMARA DO NASCIMENTO";
        $bmModel->executado_ee = ($executadas['ee_bm'] + $adiantadas_ee_valor)==0 ? null : ($executadas['ee_bm'] + $adiantadas_ee_valor);
        $bmModel->executado_es = ($executadas['es_bm'] + $adiantadas_es_valor)==0 ? null : ($executadas['es_bm'] + $adiantadas_es_valor);
        $bmModel->executado_ep = ($executadas['ep_bm'] + $adiantadas_ep_valor)==0 ? null : ($executadas['ep_bm'] + $adiantadas_ep_valor);
        $bmModel->executado_ej = ($executadas['ej_bm'] + $adiantadas_ej_valor)==0 ? null : ($executadas['ej_bm'] + $adiantadas_ej_valor);
        $bmModel->executado_tp = ($executadas['tp_bm'] + $adiantadas_tp_valor)==0 ? null : ($executadas['tp_bm'] + $adiantadas_tp_valor);
        $bmModel->km = $km_consumida;
        $bmModel->acumulado = $acu_saldo['horas_acu']+$executadas['ee_bm']+$executadas['es_bm']+$executadas['ep_bm']+$executadas['ej_bm']+$executadas['tp_bm'];
        $bmModel->saldo = $acu_saldo['h_saldo'];
        // $bmModel->qtd_dias = $projetoModel->qtd_dias;
        // $bmModel->km = $projetoModel->qtd_km;
        $bmModel->numero_bm = $ultBM;
        $bmModel->num_bm_proj = $numeroBmAtual;

        $tipo_exec = Yii::$app->db->createCommand('SELECT * FROM tipo_executante')->queryAll();
        $valorTotalBm = $bmModel->executado_ee * $tipo_exec[4]['valor_hora']+
                        $bmModel->executado_es * $tipo_exec[3]['valor_hora'] +
                        $bmModel->executado_ep * $tipo_exec[2]['valor_hora']+
                        $bmModel->executado_ej * $tipo_exec[1]['valor_hora']+
                        $bmModel->executado_tp * $tipo_exec[0]['valor_hora']+
                        $bmModel->km * $vl_km;

        
        $bms = Yii::$app->db->createCommand('SELECT * FROM bm WHERE projeto_id='.$projetoid)->queryAll();
        $valor_todos_bms = 0;

        $saldo = Yii::$app->db->createCommand('SELECT SUM(horas_saldo) FROM escopo WHERE projeto_id='.$projetoid)->queryScalar();

        foreach ($bms as $key => $bm) {
            $valor_todos_bms = $valor_todos_bms + $bm['executado_ee'] * $tipo_exec[4]['valor_hora']+
                        $bm['executado_es'] * $tipo_exec[3]['valor_hora'] +
                        $bm['executado_ep'] * $tipo_exec[2]['valor_hora']+
                        $bm['executado_ej'] * $tipo_exec[1]['valor_hora']+
                        $bm['executado_tp'] * $tipo_exec[0]['valor_hora']+
                        $bm['km'] * $vl_km;
        }

        $percBm = (($valorTotalBm) * 100) / $projetoModel->valor_proposta;
        $percAcumulada = number_format(((($valor_todos_bms) * 100) / $projetoModel->valor_proposta) + $percBm, 2, ',', '.');
        $percBm = number_format($percBm, 2, ',', '.');

        $bmModel->descricao = $projetoModel->desc_resumida.'.'.PHP_EOL.'Esse '.$numbm.'º Boletim de Medição corresponde a '.$percBm.'% das atividades citadas na '.$projetoModel->proposta.''.PHP_EOL.'A medição total acumulada incluindo este BM corresponde a '.$percAcumulada.'% das atividades realizadas.';
        
        
        if($saldo <= 0){//se o BM estiver completando os 100% do projeto
        	$bmModel->descricao = $projetoModel->desc_resumida.'.'.PHP_EOL.'Esse Boletim de Medição corresponde a 100% das atividades citadas na '.$projetoModel->proposta;
        }

        if(!$bmModel->save()){
            print_r($bmModel->getErrors());
            die();
        }

        $escopos = Yii::$app->db->createCommand('SELECT * FROM escopo WHERE projeto_id='.$projetoid)->queryAll();
        //salva as horas de cada escopo do BM e zera os valores
        foreach ($escopos as $key => $escopo) {
            $acumulada = $escopo["horas_acumulada"]+$escopo["horas_bm"];
            $saldo = ($escopo["horas_ee"] + $escopo["horas_es"] + $escopo["horas_ep"] + $escopo["horas_ej"] + $escopo["horas_tp"]) - $acumulada;
            //armazena os valores do bm de cada executante
            $bmescopo = new Bmescopo();
            $bmescopo->bm_id = $bmModel->id;
            $bmescopo->escopo_id = $escopo['id'];
            $bmescopo->horas_tp = $escopo["horas_tp_bm"];
            $bmescopo->horas_ej = $escopo["horas_ej_bm"];
            $bmescopo->horas_ep = $escopo["horas_ep_bm"];
            $bmescopo->horas_es = $escopo["horas_es_bm"];
            $bmescopo->horas_ee = $escopo["horas_ee_bm"];
            
            $bmescopo->save();

            Yii::$app->db->createCommand('UPDATE escopo SET horas_acumulada = '.$acumulada.', horas_saldo = '.$saldo.', horas_bm=0, horas_tp_bm=0.00 , horas_ej_bm=0.00 , horas_ep_bm=0.00 , horas_es_bm=0.00 , horas_ee_bm=0.00 WHERE id='.$escopo["id"])->execute();
        }

        $executantes = Yii::$app->db->createCommand('SELECT executante_id FROM projeto_executante WHERE projeto_id='.$bmModel->projeto_id)->queryAll();

        foreach ($executantes as $key => $exe) {
                               
            $bm_exe_model = new BmExecutante();
            $bm_exe_model->bm_id = $bmModel->id;
            $bm_exe_model->executante_id = $exe['executante_id'];
            $bm_exe_model->pago = 0;
            if(!$bm_exe_model->save()){
                print_r($bm_exe_model->getErrors());
                die();
            }
        }         
        
        if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante'])){
            $user_nome = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.Yii::$app->user->getId())->queryScalar();
            $logModel = new Log();
            $logModel->user_id = Yii::$app->user->getId();
            $logModel->descricao = $user_nome.' gerou o BM Nº '.$bmModel->numero_bm.' para o Projeto '.$projetoModel->nome;
            $logModel->data = Date('Y-m-d H:i:s');
            if(!$logModel->save()){
                print_r($logModel->getErrors());
                die();
            }
        }

        return $this->redirect(['bm/update', 'id' => $bmModel->id]);
    }

    /**
     * Finds the Tarefa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tarefa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tarefa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

      //habilitar ajax
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionPreencheprojeto(){
        if (Yii::$app->request->isAjax) {                 
            echo json_encode(Yii::$app->db->createCommand('SELECT projeto.id as id, projeto.nome as nome FROM projeto JOIN projeto_executante ON projeto.id=projeto_executante.projeto_id WHERE as_aprovada=1 AND projeto_executante.executante_id='.Yii::$app->request->post()['id'])->queryAll());  
        }        
    }

    public function actionPreencheexecutante(){
        if (Yii::$app->request->isAjax) {                 
            echo json_encode(Yii::$app->db->createCommand('SELECT user.id as id, user.nome as nome FROM user JOIN projeto_executante ON user.id=projeto_executante.executante_id WHERE projeto_executante.projeto_id='.Yii::$app->request->post()['id'])->queryAll());  
        }        
    }

    public function actionAttatividade(){
        if (Yii::$app->request->isAjax) {                 
           // Yii::$app->db->createCommand('UPDATE escopo SET status='.Yii::$app->request->post()['status'].' WHERE id='.Yii::$app->request->post()['id'])->execute();  

            $projeto_id = Yii::$app->db->createCommand('SELECT projeto_id FROM escopo WHERE id='.Yii::$app->request->post()['id'])->queryScalar();

            $perc_coord_adm = Yii::$app->db->createCommand('SELECT perc_coord_adm FROM projeto WHERE id='.$projeto_id)->queryScalar();

            //valor total da atividade por especialidade
            $valor_escopo_total_especialidade = Yii::$app->db->createCommand('SELECT horas_'.explode('_', Yii::$app->request->post()['tipo'])[1].' FROM escopo WHERE id='.Yii::$app->request->post()['id'])->queryScalar();

            $projeto_nome = Yii::$app->db->createCommand('SELECT nome FROM projeto WHERE id='.$projeto_id)->queryScalar();

            //atualiza status geral
            Yii::$app->db->createCommand('UPDATE projeto SET status_geral=4 WHERE id='.$projeto_id)->execute(); 

            if(Yii::$app->request->post()['tipo']=='executado_tp'){
                $exe = 'exe_tp_id';
                $tipo_bm = "horas_tp_bm";
            }
            if(Yii::$app->request->post()['tipo']=='executado_ej'){
                $exe = 'exe_ej_id';
                $tipo_bm = "horas_ej_bm";
            }
            if(Yii::$app->request->post()['tipo']=='executado_ep'){
                $exe = 'exe_ep_id';
                $tipo_bm = "horas_ep_bm";
            }
            if(Yii::$app->request->post()['tipo']=='executado_es'){
                $exe = 'exe_es_id';
                $tipo_bm = "horas_es_bm";
            }
            if(Yii::$app->request->post()['tipo']=='executado_ee'){
                $exe = 'exe_ee_id';
                $tipo_bm = "horas_ee_bm";
            }

            //adiantadas
            if(Yii::$app->request->post()['tipo']=='adiantadass_tp'){
                $exe = 'exe_tp_id';
                $tipo_bm = "horas_tp_bm";
            }
            if(Yii::$app->request->post()['tipo']=='adiantadas_ej'){
                $exe = 'exe_ej_id';
                $tipo_bm = "horas_ej_bm";
            }
            if(Yii::$app->request->post()['tipo']=='adiantadas_ep'){
                $exe = 'exe_ep_id';
                $tipo_bm = "horas_ep_bm";
            }
            if(Yii::$app->request->post()['tipo']=='adiantadas_es'){
                $exe = 'exe_es_id';
                $tipo_bm = "horas_es_bm";
            }
            if(Yii::$app->request->post()['tipo']=='adiantadas_ee'){
                $exe = 'exe_ee_id';
                $tipo_bm = "horas_ee_bm";
            }

            $executante = Yii::$app->db->createCommand('SELECT '.$exe.' FROM escopo WHERE id='.Yii::$app->request->post()['id'])->queryScalar();

            $tipo_value = Yii::$app->db->createCommand('SELECT '.Yii::$app->request->post()['tipo'].' FROM escopo WHERE id='.Yii::$app->request->post()['id'])->queryScalar();

            $bm_value = Yii::$app->db->createCommand('SELECT horas_bm FROM escopo WHERE id='.Yii::$app->request->post()['id'])->queryScalar();

            $tipo_bm_value = Yii::$app->db->createCommand('SELECT '.$tipo_bm.' FROM escopo WHERE id='.Yii::$app->request->post()['id'])->queryScalar();

            if($tipo_value==null) $tipo_value = 0;
            if($bm_value==null) $bm_value = 0;
            if($tipo_bm_value==null) $tipo_bm_value = 0;

            if(Yii::$app->request->post()['value']!='null'){              

                //verifica se não ultrapassa o valor total de horas
                if($valor_escopo_total_especialidade >= ($tipo_value + intval(Yii::$app->request->post()['value']))){
                    Yii::$app->db->createCommand('UPDATE escopo SET '.Yii::$app->request->post()['tipo'].'='.$tipo_value.'+'.Yii::$app->request->post()['value'].', horas_bm = '.$bm_value.' +'.Yii::$app->request->post()['value'].', '.$tipo_bm.' = '.$tipo_bm_value.'+ '.Yii::$app->request->post()['value'].', horas_saldo=horas_saldo-'.Yii::$app->request->post()['value'].' WHERE id='.Yii::$app->request->post()['id'])->execute(); 
                }
            }

            //atualiza coordenação e administração
            $coord_adm = Yii::$app->db->createCommand('SELECT id FROM escopo WHERE nome="Coordenação e Administração" AND projeto_id='.$projeto_id)->queryScalar();       
            $totalhoras_bm_atual = Yii::$app->db->createCommand('SELECT SUM(horas_bm) FROM escopo WHERE projeto_id='.$projeto_id)->queryScalar();
            $totalhoras_bm_atual = round($totalhoras_bm_atual * $perc_coord_adm * 0.01);

            if(!empty($coord_adm)){     
                 //verifica se não ultrapassa o valor total de horas
                if($valor_escopo_total_especialidade >= ($tipo_value + intval(Yii::$app->request->post()['value']))){
                    Yii::$app->db->createCommand('UPDATE escopo SET horas_es_bm = horas_acumulada + '.$totalhoras_bm_atual.',executado_es = horas_acumulada + '.$totalhoras_bm_atual.', horas_bm = '.$totalhoras_bm_atual.' WHERE id='.$coord_adm)->execute();
                }   
            }

            if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante']) && Yii::$app->request->post()['ultimo'] == 1){
                $user_nome = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.Yii::$app->user->getId())->queryScalar();
                $logModel = new Log();
                $logModel->user_id = Yii::$app->user->getId();
                $logModel->descricao = $user_nome.' cumpriu horas nas atividades do Projeto '.$projeto_nome;
                $logModel->data = Date('Y-m-d H:i:s');
                if(!$logModel->save()){
                    print_r($logModel->getErrors());
                    die();
                }
            }

            echo $valor_escopo_total_especialidade;
           return $this->redirect(['tarefa/index', 'projeto_id' => $projeto_id, 'executante_id' => $executante]);
        }
    }

    public function actionEditahoras(){
        if (Yii::$app->request->isAjax) { 
            $escopo_id = Yii::$app->request->post()['id'];

            try{
                if(!empty(Yii::$app->request->post()['bm'])){
                    $bm = Yii::$app->request->post()['bm'] == "zero" ? 0 : Yii::$app->request->post()['bm'];
                    Yii::$app->db->createCommand('UPDATE escopo SET horas_bm = '.$bm.' WHERE id='.$escopo_id)->execute(); 
                }
                if(!empty(Yii::$app->request->post()['acumulada'])){
                    $acumulada = Yii::$app->request->post()['acumulada'] == "zero" ? 0 : Yii::$app->request->post()['acumulada'];
                    Yii::$app->db->createCommand('UPDATE escopo SET horas_acumulada='.$acumulada.' WHERE id='.$escopo_id)->execute(); 
                }
                if(!empty(Yii::$app->request->post()['saldo'])){
                    $saldo = Yii::$app->request->post()['saldo'] == "zero" ? 0 : Yii::$app->request->post()['saldo'];
                    Yii::$app->db->createCommand('UPDATE escopo SET horas_saldo='.$saldo.' WHERE id='.$escopo_id)->execute(); 
                }

                if(!empty(Yii::$app->request->post()['horas_tp'])){
                    $horas_tp = Yii::$app->request->post()['horas_tp'] == "zero" ? 0 : Yii::$app->request->post()['horas_tp'];
                    Yii::$app->db->createCommand('UPDATE escopo SET executado_tp='.$horas_tp.' WHERE id='.$escopo_id)->execute(); 
                }
                if(!empty(Yii::$app->request->post()['horas_ej'])){
                    $horas_ej = Yii::$app->request->post()['horas_ej'] == "zero" ? 0 : Yii::$app->request->post()['horas_ej'];
                    Yii::$app->db->createCommand('UPDATE escopo SET executado_ej='.$horas_ej.' WHERE id='.$escopo_id)->execute(); 
                }
                if(!empty(Yii::$app->request->post()['horas_ep'])){
                    $horas_ep = Yii::$app->request->post()['horas_ep'] == "zero" ? 0 : Yii::$app->request->post()['horas_ep'];
                    Yii::$app->db->createCommand('UPDATE escopo SET executado_ep='.$horas_ep.' WHERE id='.$escopo_id)->execute(); 
                }
                if(!empty(Yii::$app->request->post()['horas_es'])){
                    $horas_es = Yii::$app->request->post()['horas_es'] == "zero" ? 0 : Yii::$app->request->post()['horas_es'];
                    Yii::$app->db->createCommand('UPDATE escopo SET executado_es='.$horas_es.' WHERE id='.$escopo_id)->execute(); 
                }
                if(!empty(Yii::$app->request->post()['horas_ee'])){
                    $horas_ee = Yii::$app->request->post()['horas_ee'] == "zero" ? 0 : Yii::$app->request->post()['horas_ee'];
                    Yii::$app->db->createCommand('UPDATE escopo SET executado_ee='.$horas_ee.' WHERE id='.$escopo_id)->execute(); 
                }

                if(!empty(Yii::$app->request->post()['bm_tp'])){
                    $bm_tp = Yii::$app->request->post()['bm_tp'] == "zero" ? 0 : Yii::$app->request->post()['bm_tp'];
                    Yii::$app->db->createCommand('UPDATE escopo SET horas_tp_bm='.$bm_tp.' WHERE id='.$escopo_id)->execute(); 
                }
                if(!empty(Yii::$app->request->post()['bm_ej'])){
                    $bm_ej = Yii::$app->request->post()['bm_ej'] == "zero" ? 0 : Yii::$app->request->post()['bm_ej'];
                    Yii::$app->db->createCommand('UPDATE escopo SET horas_ej_bm='.$bm_ej.' WHERE id='.$escopo_id)->execute(); 
                }
                if(!empty(Yii::$app->request->post()['bm_ep'])){
                    $bm_ep = Yii::$app->request->post()['bm_ep'] == "zero" ? 0 : Yii::$app->request->post()['bm_ep'];
                    Yii::$app->db->createCommand('UPDATE escopo SET horas_ep_bm='.$bm_ep.' WHERE id='.$escopo_id)->execute(); 
                }
                if(!empty(Yii::$app->request->post()['bm_es'])){
                    $bm_es = Yii::$app->request->post()['bm_es'] == "zero" ? 0 : Yii::$app->request->post()['bm_es'];
                    Yii::$app->db->createCommand('UPDATE escopo SET horas_es_bm='.$bm_es.' WHERE id='.$escopo_id)->execute(); 
                }
                if(!empty(Yii::$app->request->post()['bm_ee'])){
                    $bm_ee = Yii::$app->request->post()['bm_ee'] == "zero" ? 0 : Yii::$app->request->post()['bm_ee'];
                    Yii::$app->db->createCommand('UPDATE escopo SET horas_ee_bm='.$bm_ee.' WHERE id='.$escopo_id)->execute(); 
                }
                
                return "success";
            }
            catch(Exception $e){
                return $e;
            }

        }
    }

    public function actionCheckhoras(){
        if (Yii::$app->request->isAjax) { 
            $escopo_id = Yii::$app->request->post()['escopo_id'];
            $tipo_executante = Yii::$app->request->post()['tipo_executante'];
            $perc = Yii::$app->request->post()['perc'] * 0.01;

            echo json_encode(Yii::$app->db->createCommand('SELECT '.$perc.'*(horas_'.$tipo_executante.' - IFNULL(executado_'.$tipo_executante.', 0) - IFNULL(adiantadas_'.$tipo_executante.', 0)) FROM projeto JOIN escopo ON escopo.projeto_id = projeto.id WHERE escopo.id='.$escopo_id)->queryScalar());
        }
    }

    public function actionCheckkm(){
        if (Yii::$app->request->isAjax) { 
            $projeto_id = Yii::$app->request->post()['projeto_id'];
            $perc = Yii::$app->request->post()['perc'] * 0.01;

            echo json_encode(Yii::$app->db->createCommand('SELECT ('.$perc.' * qtd_km) FROM projeto WHERE id='.$projeto_id)->queryScalar());
        }
    }

    public function actionCheckhorasadiantadas(){
        if (Yii::$app->request->isAjax) { 
            $escopo_id = Yii::$app->request->post()['escopo_id'];
            $tipo_executante = Yii::$app->request->post()['tipo_executante'];
            $perc = Yii::$app->request->post()['perc'] * 0.01;

            echo json_encode(Yii::$app->db->createCommand('SELECT '.$perc.'*(horas_'.$tipo_executante.' - IFNULL(adiantadas_'.$tipo_executante.', 0)  - IFNULL(executado_'.$tipo_executante.', 0)) FROM projeto JOIN escopo ON escopo.projeto_id = projeto.id WHERE escopo.id='.$escopo_id)->queryScalar());
        }
    }

    public function actionVerificaespecialidade(){
        if (Yii::$app->request->isAjax) { 
            $escopo_id = Yii::$app->request->post()['escopo_id'];

            echo json_encode(Yii::$app->db->createCommand('SELECT horas_tp, horas_ej, horas_ep, horas_es, horas_ee FROM projeto JOIN escopo ON escopo.projeto_id = projeto.id WHERE escopo.id='.$escopo_id)->queryAll());
        }
    }
}

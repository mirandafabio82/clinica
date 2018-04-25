<?php

namespace app\controllers;

use Yii;
use app\models\Tarefa;
use app\models\search\TarefaSearch;
use app\models\Escopo;
use app\models\search\EscopoSearch;
use app\models\Bm;
use app\models\search\BmSearch;
use app\models\Projeto;
use app\models\search\ProjetoSearch;
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
    public function actionIndex()
    {
        /*if(Yii::$app->request->post('editableKey')){

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
            return $this->redirect(['index']);
        }*/
        // $searchModel = new EscopoSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $escopoModel = new Escopo();
        
        $searchModel = new ProjetoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                
        if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['contato'])) {
            $clienteId = Yii::$app->db->createCommand('SELECT cliente_id FROM contato WHERE usuario_id='.Yii::$app->user->getId())->queryScalar();

            $dataProvider->query->where('cliente_id='.$clienteId);
        }
        if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['executante'])){
            $projetos_id = Yii::$app->db->createCommand('SELECT projeto_id as id FROM projeto_executante WHERE executante_id='.Yii::$app->user->getId())->queryAll();

            foreach ($projetos_id as $key => $pid) {
                $dataProvider->query->orWhere('id='.$pid['id']);
            }
            
        }
        
        $status = Yii::$app->db->createCommand('SELECT id, status FROM escopo_status')->queryAll();
        $listStatus = ArrayHelper::map($status,'id','status');

        $executantes = Yii::$app->db->createCommand('SELECT usuario_id, nome FROM executante JOIN user ON executante.usuario_id = user.id')->queryAll();
        $listExecutantes = ArrayHelper::map($executantes,'usuario_id','nome');

        $projetos = Yii::$app->db->createCommand('SELECT id, nome FROM projeto')->queryAll();
        $listProjetos = ArrayHelper::map($projetos,'id','nome');

        $executante_id = '';
        $isPost = 0;

        $projeto_selected = '';
        if(isset($_POST['executante']) && isset($_POST['projeto'])){    //filtro         
            $dataProvider->query->where('id = '.$_POST['projeto']);
            $executante_id = $_POST['executante'];
            $projeto_selected = $_POST['projeto'];
            $isPost = 1;
        }

        if(isset($_POST['Escopo'])){
           
           foreach ($_POST['Escopo'] as $key => $escopo) { //add horas
                $modelEscopo = Escopo::findIdentity($key);
                // $modelEscopo->setAttributes($escopo);
                $executado_tp=0;
                $executado_ej=0;
                $executado_ep=0;
                $executado_es=0;
                $executado_ee=0;
                $executante = 0;
                
                if(isset($escopo['executado_tp'])){
                    $executado_tp = $escopo['executado_tp'];
                    $modelEscopo->executado_tp = $escopo['executado_tp'] + $modelEscopo->executado_tp;
                    $modelEscopo->horas_tp_bm = $modelEscopo->horas_tp_bm + $escopo['executado_tp'];
                    if(!empty($modelEscopo['exe_tp_id'])) $executante = $modelEscopo['exe_tp_id'];

                }
                if(isset($escopo['executado_ej'])){
                    $executado_ej = $escopo['executado_ej'];
                    $modelEscopo->executado_ej = $escopo['executado_ej'] + $modelEscopo->executado_ej;
                    $modelEscopo->horas_ej_bm = $modelEscopo->horas_ej_bm + $escopo['executado_ej'];
                    if(!empty($modelEscopo['exe_ej_id'])) $executante = $modelEscopo['exe_ej_id'];
                }
                if(isset($escopo['executado_ep'])){                    
                    $executado_ep = $escopo['executado_ep'];
                    $modelEscopo->executado_ep = $escopo['executado_ep'] + $modelEscopo->executado_ep;
                    $modelEscopo->horas_ep_bm = $modelEscopo->horas_ep_bm + $escopo['executado_ep'];

                    if(!empty($modelEscopo['exe_ep_id'])) $executante = $modelEscopo['exe_ep_id'];
                }
                if(isset($escopo['executado_es'])){
                    $executado_es = $escopo['executado_es'];
                    $modelEscopo->executado_es = $escopo['executado_es'] + $modelEscopo->executado_es;
                    $modelEscopo->horas_es_bm = $modelEscopo->horas_es_bm + $escopo['executado_es'];
                    if(!empty($modelEscopo['exe_es_id'])) $executante = $modelEscopo['exe_es_id'];
                }
                if(isset($escopo['executado_ee'])){  
                    $executado_ee = $escopo['executado_ee'];
                    $modelEscopo->executado_ee = $escopo['executado_ee'] + $modelEscopo->executado_ee;
                    $modelEscopo->horas_ee_bm = $modelEscopo->horas_ee_bm + $escopo['executado_ee'];
                    if(!empty($modelEscopo['exe_ee_id'])) $executante = $modelEscopo['exe_ee_id'];
                }
                
                $modelEscopo->horas_bm = $executado_tp + $executado_ej + $executado_ep + $executado_es + $executado_ee + $modelEscopo->horas_bm;
                
                if(!empty($escopo['executado_tp'])) $modelEscopo->executado_tp = $modelEscopo->executado_tp - $escopo['executado_tp']/2;
                if(!empty($escopo['executado_ej']))$modelEscopo->executado_ej = $modelEscopo->executado_ej - $escopo['executado_ej']/2;
                if(!empty($escopo['executado_ep']))$modelEscopo->executado_ep = $modelEscopo->executado_ep - $escopo['executado_ep']/2;
                if(!empty($escopo['executado_es']))$modelEscopo->executado_es = $modelEscopo->executado_es - $escopo['executado_es']/2;
                if(!empty($escopo['executado_ee']))$modelEscopo->executado_ee = $modelEscopo->executado_ee - $escopo['executado_ee']/2;

                if(!$modelEscopo->save()){
                    print_r($modelEscopo->getErrors());
                    die();
                }              

                


           }

            $dataProvider->query->where('id = '.$modelEscopo->projeto_id);
            $executante_id = $executante;
            $projeto_selected = $modelEscopo->projeto_id;
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

    public function actionGerarbm($projetoid)
    {
        $projetoModel = Projeto::findIdentity($projetoid);
        
        $executadas = Yii::$app->db->createCommand('SELECT SUM(horas_ee_bm) ee_bm, SUM(horas_es_bm) es_bm,SUM(horas_ep_bm) ep_bm,SUM(horas_ej_bm) ej_bm,SUM(horas_tp_bm) tp_bm, SUM(horas_acumulada) h_acu, SUM(horas_saldo) h_saldo FROM escopo WHERE projeto_id='.$projetoid)->queryOne();
        
        $ultBM = Yii::$app->db->createCommand('SELECT ultimo_bm FROM config')->queryScalar();
        $ultBM = $ultBM+1;

        Yii::$app->db->createCommand('UPDATE config SET ultimo_bm ='.$ultBM)->execute();

        $escopos = Yii::$app->db->createCommand('SELECT * FROM escopo WHERE projeto_id='.$projetoid)->queryAll();

        foreach ($escopos as $key => $escopo) {
            $acumulada = $escopo["horas_acumulada"]+$escopo["horas_bm"];
            $saldo = ($escopo["horas_ee"] + $escopo["horas_es"] + $escopo["horas_ep"] + $escopo["horas_ej"] + $escopo["horas_tp"]) - $acumulada;

            Yii::$app->db->createCommand('UPDATE escopo SET horas_acumulada = '.$acumulada.', horas_saldo = '.$saldo.', horas_bm=0, horas_tp_bm="" , horas_ej_bm="" , horas_ep_bm="" , horas_es_bm="" , horas_ee_bm="" WHERE id='.$escopo["id"])->execute();
        }

        $acu_saldo = Yii::$app->db->createCommand('SELECT SUM(horas_acumulada) horas_acu, SUM(horas_saldo) h_saldo FROM escopo WHERE projeto_id='.$projetoid)->queryOne();
        
        $bmModel = new Bm();
        $bmModel->projeto_id = $projetoModel->id;
        $bmModel->contrato = $projetoModel->contrato;
        $bmModel->contratada = "HCN AUTOMAÇÃO LTDA";
        $bmModel->cnpj = "10.486.000/0001-05";
        $bmModel->contato = "HÉLDER CÂMARA DO NASCIMENTO";
        $bmModel->executado_ee = $executadas['ee_bm']==0 ? null : $executadas['ee_bm'];
        $bmModel->executado_es = $executadas['es_bm']==0 ? null : $executadas['es_bm'];
        $bmModel->executado_ep = $executadas['ep_bm']==0 ? null : $executadas['ep_bm'];
        $bmModel->executado_ej = $executadas['ej_bm']==0 ? null : $executadas['ej_bm'];
        $bmModel->executado_tp = $executadas['tp_bm']==0 ? null : $executadas['tp_bm'];
        $bmModel->acumulado = $acu_saldo['horas_acu'];
        $bmModel->saldo = $acu_saldo['h_saldo'];
        $bmModel->qtd_dias = $projetoModel->qtd_dias;
        $bmModel->km = $projetoModel->qtd_km;
        $bmModel->numero_bm = $ultBM;

        if(!$bmModel->save()){
            print_r($bmModel->getErrors());
            die();
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
            echo json_encode(Yii::$app->db->createCommand('SELECT projeto.id as id, projeto.nome as nome FROM projeto JOIN projeto_executante ON projeto.id=projeto_executante.projeto_id WHERE projeto_executante.executante_id='.Yii::$app->request->post()['id'])->queryAll());  
        }        
    }

    public function actionAttatividade(){
        if (Yii::$app->request->isAjax) {                 
           // Yii::$app->db->createCommand('UPDATE escopo SET status='.Yii::$app->request->post()['status'].' WHERE id='.Yii::$app->request->post()['id'])->execute();  
           
           Yii::$app->db->createCommand('UPDATE escopo SET '.Yii::$app->request->post()['tipo'].'='.Yii::$app->request->post()['value'].' WHERE id='.Yii::$app->request->post()['id'])->execute(); 

            echo 'success';
        }
        
    }
}

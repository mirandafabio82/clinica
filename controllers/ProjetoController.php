<?php

namespace app\controllers;

use Yii;
use app\models\Projeto;
use app\models\Cliente;
use app\models\Contato;
use app\models\Escopo;
use app\models\search\EscopoSearch;
use app\models\search\ProjetoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

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

        $searchModel = new ProjetoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $clientes = Yii::$app->db->createCommand('SELECT id, nome FROM cliente')->queryAll();
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

        $searchEscopo = new EscopoSearch();
        $escopoDataProvider = $searchEscopo->search(Yii::$app->request->queryParams);
        $escopoDataProvider->query->join('join','atividademodelo', 'atividademodelo.id=escopo.atividademodelo_id')->where('1=2');

        if ($model->load(Yii::$app->request->post())) {
            try{
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                $model->setAttributes($_POST['Projeto']);                

                if($model->save()){
                    if(isset($_POST['Escopos'])){
                        $escopos = $_POST['Escopos'];
                        $automacoes = isset($escopos['Automação']) ? $escopos['Automação'] : array();
                        $processos = isset($escopos['Processo']) ? $escopos['Processo'] : array();
                        $instrumentacoes = isset($escopos['Instrumentação']) ? $escopos['Instrumentação'] : array();

                        foreach ($automacoes as $key => $automacao) {
                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE escopopadrao_id='.$automacao.' AND disciplina_id = 1')->queryAll();
                            
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
                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE escopopadrao_id='.$processo.' AND disciplina_id = 2')->queryAll();

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
                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE escopopadrao_id='.$instrumentacao.' AND disciplina_id = 2')->queryAll();

                            foreach ($atvmodelos as $key => $atv) {
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
                'searchEscopo' => $searchEscopo

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

        $searchModel = new ProjetoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $clientes = Yii::$app->db->createCommand('SELECT id, nome FROM cliente')->queryAll();
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

        $plantas = Yii::$app->db->createCommand('SELECT id, nome FROM planta WHERE site_id='.$model->site)->queryAll();
        $listPlantas = ArrayHelper::map($plantas,'id','nome');

        $searchEscopo = new EscopoSearch();
        $escopoDataProvider = $searchEscopo->search(Yii::$app->request->queryParams);
        $escopoDataProvider->query->join('join','atividademodelo', 'atividademodelo.id=escopo.atividademodelo_id')->where('projeto_id='.$model->id);

          if ($model->load(Yii::$app->request->post())) {
            try{
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                $model->setAttributes($_POST['Projeto']);                

                if($model->save()){                        
                    if(isset($_POST['Escopos'])){
                        $escopos = $_POST['Escopos'];
                        $automacoes = isset($escopos['Automação']) ? $escopos['Automação'] : array();
                        $processos = isset($escopos['Processo']) ? $escopos['Processo'] : array();
                        $instrumentacoes = isset($escopos['Instrumentação']) ? $escopos['Instrumentação'] : array();


                        foreach ($automacoes as $key => $automacao) {
                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE escopopadrao_id='.$automacao.' AND disciplina_id = 1')->queryAll();
                            
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
                        foreach ($processos as $key => $processo) {
                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE escopopadrao_id='.$processo.' AND disciplina_id = 2')->queryAll();

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
                        foreach ($instrumentacoes as $key => $instrumentacao) {
                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE escopopadrao_id='.$instrumentacao.' AND disciplina_id = 3')->queryAll();

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
                'listPlantas' => $listPlantas,
                'listDisciplina' => $listDisciplina,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'escopoDataProvider' => $escopoDataProvider,
                'searchEscopo' => $searchEscopo
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
}

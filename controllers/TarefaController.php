<?php

namespace app\controllers;

use Yii;
use app\models\Tarefa;
use app\models\search\TarefaSearch;
use app\models\Escopo;
use app\models\search\EscopoSearch;
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

        if($_POST){
            $dataProvider->query->where('id = '.$_POST['projeto']);
            $executante_id = $_POST['executante'];
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
            'isPost' => $isPost
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

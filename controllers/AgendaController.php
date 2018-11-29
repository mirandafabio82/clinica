<?php

namespace app\controllers;

use Yii;
use app\models\Agenda;
use app\models\search\AgendaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use \Datetime;
use yii\helpers\Json;
/**
 * AgendaController implements the CRUD actions for Agenda model.
 */
class AgendaController extends Controller
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
                    ['allow' => true,'roles' => ['admin']],
                    ['allow' => true,'roles' => ['executante']],                    
                    ['actions' => ['index', 'view'],'allow' => true,'roles' => ['executante']],
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
     * Lists all Agenda models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AgendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Agenda model.
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
     * Creates a new Agenda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
         $searchModel = new AgendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(isset($_GET['pagination'])) $dataProvider->pagination = false;

        $model = new Agenda();
        $projetos = Yii::$app->db->createCommand('SELECT id, nome FROM projeto')->queryAll();
        $listProjetos = ArrayHelper::map($projetos,'id','nome');

        $sites = Yii::$app->db->createCommand('SELECT id, nome FROM site')->queryAll();
        $listSites = ArrayHelper::map($sites,'id','nome');

        $status = Yii::$app->db->createCommand('SELECT id, status FROM agenda_status')->queryAll();
        $listStatus = ArrayHelper::map($status,'id','status');

        $executantes = Yii::$app->db->createCommand('SELECT usuario_id, nome FROM executante JOIN user ON executante.usuario_id = user.id')->queryAll();
        $listExecutantes = ArrayHelper::map($executantes,'usuario_id','nome');

        $contatos = Yii::$app->db->createCommand('SELECT id, nome FROM contato JOIN user ON contato.usuario_id = user.id')->queryAll();
        $listContatos = ArrayHelper::map($contatos,'id','nome');

        $arrayEventos = Yii::$app->db->createCommand('SELECT * FROM agenda')->queryAll();
 
        if($_POST){            
            $model->setAttributes($_POST['Agenda']);

            
            $dat = DateTime::createFromFormat('d/m/Y H:i:s', $_POST['Agenda']['hr_inicio']); 
            $model->hr_inicio = date_format($dat, 'Y-m-d H:i:s');
            
            $dat = DateTime::createFromFormat('d/m/Y H:i:s', $_POST['Agenda']['hr_final']);          
            $model->hr_final = date_format($dat, 'Y-m-d H:i:s');

            if(!$model->save()){
                print_r($model->getErrors());
                die();
            }

            $_SESSION['msg'] = '<div class="alert alert-success" role="alert">Cadastrado com sucesso</div';

            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'listProjetos' => $listProjetos,
                'listSites' => $listSites,
                'listStatus' => $listStatus,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'arrayEventos' => $arrayEventos,
                'listContatos' => $listContatos,
                'listExecutantes' => $listExecutantes
            ]);
        }
    }

    /**
     * Updates an existing Agenda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $searchModel = new AgendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = $this->findModel($id);
               
        if($_POST){
            $model->setAttributes($_POST['Agenda']);

            
            $dat = DateTime::createFromFormat('d/m/Y H:i:s', $_POST['Agenda']['hr_inicio']); 
            $model->hr_inicio = date_format($dat, 'Y-m-d H:i:s');
            
            $dat = DateTime::createFromFormat('d/m/Y H:i:s', $_POST['Agenda']['hr_final']);          
            $model->hr_final = date_format($dat, 'Y-m-d H:i:s');

            if(!$model->save()){
                print_r($model->getErrors());
                die();
            }

            $_SESSION['msg'] = '<div class="alert alert-success" role="alert">Atualizado com sucesso</div';

            return $this->redirect(['create']);
        }else {
            return $this->render('update');
        }
    }

     //habilitar ajax
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    /**
     * Deletes an existing Agenda model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $this->findModel($id)->delete();

        return $this->redirect(['create']);
    }

    /**
     * Finds the Agenda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Agenda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Agenda::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionGetevent(){
        if (Yii::$app->request->isAjax) {                 
            return json_encode(Yii::$app->db->createCommand('SELECT projeto_id, DATE_FORMAT(hr_inicio, "%d/%m/%Y %H:%i:%s") AS hr_inicio, DATE_FORMAT(hr_final, "%d/%m/%Y %H:%i:%s") AS hr_final, local, responsavel, contato, assunto, status, descricao, prazo, pendente, cor FROM agenda WHERE id ='.Yii::$app->request->post()['id'])->queryOne());  
        }
        
    }
}

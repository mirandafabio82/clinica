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
                    [
                        // 'actions' => ['index', 'view', 'create', 'update'],
                        'allow' => true,
                        'roles' => ['admin'],
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

        $model = new Agenda();
        $model->data =  date('d/m/Y');
        $projetos = Yii::$app->db->createCommand('SELECT id, nome FROM projeto')->queryAll();
        $listProjetos = ArrayHelper::map($projetos,'id','nome');

        $sites = Yii::$app->db->createCommand('SELECT id, nome FROM site')->queryAll();
        $listSites = ArrayHelper::map($sites,'id','nome');

        $status = Yii::$app->db->createCommand('SELECT id, status FROM agenda_status')->queryAll();
        $listStatus = ArrayHelper::map($status,'id','status');


        if(Yii::$app->request->post('editableKey')){
            $agenda_id = Yii::$app->request->post('editableKey');
            $agenda = Agenda::findOne($agenda_id);

            $out = Json::encode(['output'=>'', 'message'=>'']);
            $post =[];
            $posted = current($_POST['Agenda']);
            $post['Agenda'] = $posted;

            if($agenda->load($post)){
                $agenda->save();
                // $output = 'teste';
                $out = Json::encode(['output'=>'', 'message'=>'']);
            }
            echo $out;
            return $this->redirect(['create']);
        }
        if($_POST){
            $model->setAttributes($_POST['Agenda']);
            $dat = DateTime::createFromFormat('d/m/Y', $_POST['Agenda']['data']);          
            $model->data = date_format($dat, 'Y-m-d');
            $model->save();

            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'listProjetos' => $listProjetos,
                'listSites' => $listSites,
                'listStatus' => $listStatus,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
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
        $model->data = date_format(DateTime::createFromFormat('Y-m-d', $model->data), 'd/m/Y');
        $projetos = Yii::$app->db->createCommand('SELECT projeto.id, nome FROM projeto')->queryAll();
        $listProjetos = ArrayHelper::map($projetos,'id','nome');

        $sites = Yii::$app->db->createCommand('SELECT id, nome FROM site')->queryAll();
        $listSites = ArrayHelper::map($sites,'id','nome');

        $status = Yii::$app->db->createCommand('SELECT id, status FROM agenda_status')->queryAll();
        $listStatus = ArrayHelper::map($status,'id','status');

        if(Yii::$app->request->post('editableKey')){
            $agenda_id = Yii::$app->request->post('editableKey');
            $agenda = Agenda::findOne($agenda_id);

            $out = Json::encode(['output'=>'', 'message'=>'']);
            $post =[];
            $posted = current($_POST['Agenda']);
            $post['Agenda'] = $posted;

            if($agenda->load($post)){
                $agenda->save();
                // $output = 'teste';
                $out = Json::encode(['output'=>'', 'message'=>'']);
            }
            echo $out;
            return $this->redirect(['create']);
        }
        if($_POST){
            $model->setAttributes($_POST['Agenda']);
            $dat = DateTime::createFromFormat('d/m/Y', $_POST['Agenda']['data']);
            $model->data = date_format($dat, 'Y-m-d');
            $model->save();

            return $this->redirect(['create']);
        }else {
            return $this->render('update', [
                'model' => $model,
                'listProjetos' => $listProjetos,
                'listSites' => $listSites,
                'listStatus' => $listStatus,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
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
}

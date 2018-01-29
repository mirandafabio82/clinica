<?php

namespace app\controllers;

use Yii;
use app\models\Atividade;
use app\models\search\AtividadeSearch;
use app\models\Escopo;
use app\models\search\EscopoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
/**
 * AtividadeController implements the CRUD actions for Atividade model.
 */
class AtividadeController extends Controller
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
     * Lists all Atividade models.
     * @return mixed
     */
    public function actionIndex()
    {
        
         if(Yii::$app->request->post('editableKey')){

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
        }
        $searchModel = new EscopoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $status = Yii::$app->db->createCommand('SELECT id, status FROM agenda_status')->queryAll();
        $listStatus = ArrayHelper::map($status,'id','status');


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listStatus' => $listStatus,
        ]);
    }

    /**
     * Displays a single Atividade model.
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
     * Creates a new Atividade model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Atividade();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Atividade model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Atividade model.
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
     * Finds the Atividade model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Atividade the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Atividade::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

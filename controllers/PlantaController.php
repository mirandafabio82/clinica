<?php

namespace app\controllers;

use Yii;
use app\models\Planta;
use app\models\search\PlantaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * PlantaController implements the CRUD actions for Planta model.
 */
class PlantaController extends Controller
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
     * Lists all Planta models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlantaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Planta model.
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
     * Creates a new Planta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $searchModel = new PlantaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new Planta();
        $sites = Yii::$app->db->createCommand('SELECT id, nome FROM site')->queryAll();
        $listSites = ArrayHelper::map($sites,'id','nome');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'listSites' => $listSites,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Updates an existing Planta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $searchModel = new PlantaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = $this->findModel($id);
        $sites = Yii::$app->db->createCommand('SELECT id, nome FROM site')->queryAll();
        $listSites = ArrayHelper::map($sites,'id','nome');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['create']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'listSites' => $listSites,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing Planta model.
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
     * Finds the Planta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Planta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Planta::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace app\controllers;

use Yii;
use app\models\Atividademodelo;
use app\models\search\AtividademodeloSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * AtividademodeloController implements the CRUD actions for Atividademodelo model.
 */
class AtividademodeloController extends Controller
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
     * Lists all Atividademodelo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AtividademodeloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Atividademodelo model.
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
     * Creates a new Atividademodelo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Atividademodelo();

        $searchModel = new AtividademodeloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $escopo = Yii::$app->db->createCommand('SELECT id, nome FROM escopopadrao')->queryAll();
        $listEscopo = ArrayHelper::map($escopo,'id','nome');

        $disciplina = Yii::$app->db->createCommand('SELECT id, nome FROM disciplina')->queryAll();
        $listDisciplina = ArrayHelper::map($disciplina,'id','nome');

        

        if ($model->load(Yii::$app->request->post()) ) {

            $model->setAttributes($_POST['Atividademodelo']);

            if(isset($_POST['Atividademodelo']['isPrioritaria'])){
                $model->isPrioritaria = 1;
            }
            if(isset($_POST['Atividademodelo']['isEntregavel'])){
                $model->isEntregavel = 1;
            }

            $model->save();

            return $this->redirect(['create', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'listEscopo' => $listEscopo,
                'listDisciplina' => $listDisciplina,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Updates an existing Atividademodelo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $searchModel = new AtividademodeloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $escopo = Yii::$app->db->createCommand('SELECT id, nome FROM escopopadrao')->queryAll();
        $listEscopo = ArrayHelper::map($escopo,'id','nome');

        $disciplina = Yii::$app->db->createCommand('SELECT id, nome FROM disciplina')->queryAll();
        $listDisciplina = ArrayHelper::map($disciplina,'id','nome');

        

        if ($model->load(Yii::$app->request->post()) ) {
            
            $model->setAttributes($_POST['Atividademodelo']);
            if(isset($_POST['Atividademodelo']['isPrioritaria'])){
                $model->isPrioritaria = 1;
            }
            else{
                $model->isPrioritaria = 0;   
            }
            if(isset($_POST['Atividademodelo']['isEntregavel'])){
                $model->isEntregavel = 1;
            }
            else{
                $model->isPrioritaria = 0;   
            }

            $model->save();

            return $this->redirect(['create', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'listEscopo' => $listEscopo,
                'listDisciplina' => $listDisciplina,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing Atividademodelo model.
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
     * Finds the Atividademodelo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Atividademodelo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Atividademodelo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

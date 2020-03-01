<?php

namespace app\controllers;

use Yii;
use app\models\TratamentoRealizado;
use app\models\search\TratamentoRealizadoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TratamentoRealizadoController implements the CRUD actions for TratamentoRealizado model.
 */
class TratamentoRealizadoController extends Controller
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
     * Lists all TratamentoRealizado models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TratamentoRealizadoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (isset($_GET['pagination'])) $dataProvider->pagination = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TratamentoRealizado model.
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
     * Creates a new TratamentoRealizado model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TratamentoRealizado();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_tratamento]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TratamentoRealizado model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_tratamento]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TratamentoRealizado model.
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

    //habilitar ajax
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }


    public function actionGetagendamento()
    {
        return json_encode(Yii::$app->db->createCommand('SELECT a.nome, a.cpf, DATE_FORMAT(a.horario, "%Y-%m-%dT%H:%i:%s") AS horario, t.dente, t.tratamento_realizado FROM tratamento_realizado t JOIN agendamento a ON (a.id_agendamento = t.id_agendamento) WHERE t.id_tratamento =' . Yii::$app->request->post()['id'])->queryOne());
    }

    public function actionUpdateone()
    {

        $id = Yii::$app->request->post()['id'];
        $dente = Yii::$app->request->post()['dente'];
        $tratamento = Yii::$app->request->post()['tratamento'];


        Yii::$app->db->createCommand('UPDATE tratamento_realizado SET dente="' . $dente . '", tratamento_realizado="' . $tratamento . '" WHERE 	id_tratamento =' . $id)->execute();

        $model = $this->findModel(Yii::$app->request->post()['id']);

        return $this->redirect(['index', 'id' => $model->id_tratamento]);
    }

    /**
     * Finds the TratamentoRealizado model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TratamentoRealizado the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TratamentoRealizado::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

<?php

namespace app\controllers;

use Yii;
use app\models\TratamentoPlanejamento;
use app\models\search\TratamentoPlanejamentoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TratamentoPlanejamentoController implements the CRUD actions for TratamentoPlanejamento model.
 */
class TratamentoPlanejamentoController extends Controller
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
     * Lists all TratamentoPlanejamento models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TratamentoPlanejamentoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new TratamentoPlanejamento();

        if (isset($_GET['pagination'])) $dataProvider->pagination = false;

        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TratamentoPlanejamento model.
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
     * Creates a new TratamentoPlanejamento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TratamentoPlanejamento();

        $model->setAttributes($_POST['TratamentoPlanejamento']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_tratamento_planejamento]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateone()
    {

        $cpf = Yii::$app->request->post()['cpf'];

        $cpf = str_replace(".", "", $cpf);
        $cpf = str_replace("-", "", $cpf);

        $model = new TratamentoPlanejamento();

        $model->id_paciente = Yii::$app->db->createCommand('SELECT id_paciente FROM paciente WHERE cpf = ' . $cpf)->queryOne()['id_paciente'];
        $model->primeira_opcao = Yii::$app->request->post()['primeira'];
        $model->segunda_opcao = Yii::$app->request->post()['segunda'];

        // Yii::$app->db->createCommand('INSERT INTO tratamento_planejamento(id_paciente, primeira_opcao, segunda_opcao) VALUES (' . $model->id_paciente . ', "' . $model->primeira_opcao . '", "' . $model->segunda_opcao . '")')->execute();
        $model->save();

        return $this->redirect(['index']);
    }

    public function actionGetplanejamento()
    {

        $id = Yii::$app->request->post()['id'];

        $model = $this->findModel($id);

        return json_encode(Yii::$app->db->createCommand('SELECT * FROM planejamento_paciente  WHERE id_tratamento = ' . $id)->queryOne());
    }

    /**
     * Updates an existing TratamentoPlanejamento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->post()['id_tratamento'];

        $model = $this->findModel($id);
        $model->primeira_opcao = Yii::$app->request->post()['primeira'];
        $model->segunda_opcao = Yii::$app->request->post()['segunda'];

        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing TratamentoPlanejamento model.
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
     * Finds the TratamentoPlanejamento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TratamentoPlanejamento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TratamentoPlanejamento::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //habilitar ajax
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
}

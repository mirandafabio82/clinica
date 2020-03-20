<?php

namespace app\controllers;

use Yii;
use app\models\Documento;
use app\models\search\DocumentoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * DocumentoController implements the CRUD actions for Documento model.
 */
class DocumentoController extends Controller
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

    //habilitar ajax
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Lists all Documento models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Documento model.
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

    public function actionUpload()
    {

        $model = new Documento();

        $fileName = $_FILES["Documento"]["name"]['path'];

        $cpf = Yii::$app->request->post()['Documento']['cpf'];
        $nome = Yii::$app->request->post()['Documento']['nome'];
        $model->id_tipo_documento = Yii::$app->request->post()['Documento']['id_tipo_documento'];
        $model->observacao = Yii::$app->request->post()['Documento']['observacao'];
        $model->data = Yii::$app->request->post()['Documento']['data'];

        if ($model->id_tipo_documento == '1003') {
            $tipo_documento = Yii::$app->request->post()['Documento']['outro_tipo'];
            Yii::$app->db->createCommand('INSERT INTO tipo_documento (nome) VALUES ("' . $tipo_documento  . '");')->execute();
        }

        $pieces = explode("/", $model->data);
        $model->data = $pieces[2] . '-' . $pieces[1] . '-' . $pieces[0];

        $cpf = str_replace(".", "", $cpf);
        $cpf = str_replace("-", "", $cpf);

        $model->id_paciente = Yii::$app->db->createCommand('SELECT id_paciente FROM paciente WHERE cpf= "' . $cpf . '" AND nome= "' . $nome . '"')->queryOne()['id_paciente'];

        $target = '../../Documentos/' . $model->id_paciente . '/';

        // Caso o diretório não exista, cria um novo
        if (!file_exists($target)) {
            mkdir($target, 0700, true);
        }

        $fileName = str_replace("-", "_", $fileName);
        $fileName = str_replace(" ", "_", $fileName);
        $fileName = $this->stripAccents($fileName);

        $fileTarget = $target . $fileName;
        $tempFileName = $_FILES["Documento"]["tmp_name"]['path'];
        $result = move_uploaded_file($tempFileName, $fileTarget);

        $model->path = $fileTarget;
        /*
        *     If file was successfully uploaded in the destination folder
        */
        if ($result) {
            $model->save();
        } else {
            echo "Sorry !!! There was an error in uploading your file";
        }

        $model = new Documento();

        $searchModel = new DocumentoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $tipo_docs = Yii::$app->db->createCommand('SELECT id_tipo_documento, nome FROM tipo_documento  UNION SELECT 1003,"Outro"')->queryAll();
        $listTipoDoc = ArrayHelper::map($tipo_docs, 'id_tipo_documento', 'nome');

        return $this->render('create', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listTipoDoc' => $listTipoDoc,
        ]);
    }

    /**
     * Creates a new Documento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Documento();

        $searchModel = new DocumentoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $tipo_docs = Yii::$app->db->createCommand('SELECT id_tipo_documento, nome FROM tipo_documento  UNION SELECT 1003,"Outro"')->queryAll();
        $listTipoDoc = ArrayHelper::map($tipo_docs, 'id_tipo_documento', 'nome');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_documento]);
        }

        return $this->render('create', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listTipoDoc' => $listTipoDoc,
        ]);
    }

    /**
     * Updates an existing Documento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_documento]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Documento model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        $model = new Documento();

        $searchModel = new DocumentoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $tipo_docs = Yii::$app->db->createCommand('SELECT id_tipo_documento, nome FROM tipo_documento  UNION SELECT 1003,"Outro"')->queryAll();
        $listTipoDoc = ArrayHelper::map($tipo_docs, 'id_tipo_documento', 'nome');

        return $this->render('create', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listTipoDoc' => $listTipoDoc,
        ]);
    }

    /**
     * Finds the Documento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Documento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Documento::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function stripAccents($string)
    {

        $string = strtr(
            utf8_decode($string),
            utf8_decode('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
            'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy'
        );

        return $string;
    }
}

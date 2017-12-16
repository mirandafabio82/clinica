<?php

namespace app\controllers;

use Yii;
use app\models\Documento;
use app\models\search\DocumentoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

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
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
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

        $projetos = Yii::$app->db->createCommand('SELECT id, nome FROM projeto')->queryAll();
        $listProjetos = ArrayHelper::map($projetos,'id','nome');

        if (isset($_POST['Documento'])) {           
            try{
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $model->setAttributes($_POST['Documento']);


                if(UploadedFile::getInstance($model,'path') != null){

                    $nomeOriginal = UploadedFile::getInstance($model,'path')->name;
                    $extensao = explode('.', $nomeOriginal)[1];
                    
                    if (!is_dir(Yii::$app->basePath . '/web/uploaded-files/' . $model->projeto_id)) {
                        mkdir(Yii::$app->basePath . '/web/uploaded-files/' . $model->projeto_id);
                    }

                    $model->path = UploadedFile::getInstance($model,'path');                
                    $model->path->name = $model->path->name;                
                    $rnd = rand(0,9999);               
                    $fileName = "{$model->nome}-{$nomeOriginal}";                
                    $model->path->saveAs(Yii::$app->basePath.'/web/uploaded-files/'.$model->projeto_id.'/'.$fileName);                
                    $model->path = $fileName;
                }
                $model->save();
                $transaction->commit();
                return $this->redirect(['create']);
            }
            catch(Exception $e){
                $transaction->rollBack();
                throw $e;
            }            
        } else {
            return $this->render('create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'listProjetos' => $listProjetos
            ]);
        }
    }

    /**
     * Updates an existing Documento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new DocumentoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $projetos = Yii::$app->db->createCommand('SELECT id, nome FROM projeto')->queryAll();
        $listProjetos = ArrayHelper::map($projetos,'id','nome');

        if (isset($_POST['Documento'])) {           
            try{
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $model->setAttributes($_POST['Documento']);


                if(UploadedFile::getInstance($model,'path') != null){

                    $nomeOriginal = UploadedFile::getInstance($model,'path')->name;
                    $extensao = explode('.', $nomeOriginal)[1];
                    
                    if (!is_dir(Yii::$app->basePath . '/web/uploaded-files/' . $model->projeto_id)) {
                        mkdir(Yii::$app->basePath . '/web/uploaded-files/' . $model->projeto_id);
                    }

                    $model->path = UploadedFile::getInstance($model,'path');                
                    $model->path->name = $model->path->name;                
                    $rnd = rand(0,9999);               
                    $fileName = "{$model->nome}-{$nomeOriginal}";                
                    $model->path->saveAs(Yii::$app->basePath.'/web/uploaded-files/'.$model->projeto_id.'/'.$fileName);                
                    $model->path = $fileName;
                }
                $model->save();
                $transaction->commit();
                return $this->redirect(['create']);
            }
            catch(Exception $e){
                $transaction->rollBack();
                throw $e;
            } 
            }else {
            return $this->render('update', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'listProjetos' => $listProjetos
            ]);
        }
    }
     //habilitar ajax
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Deletes an existing Documento model.
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
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

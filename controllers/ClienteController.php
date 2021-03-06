<?php

namespace app\controllers;

use Yii;
use app\models\Cliente;
use app\models\search\ClienteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
/**
 * ClienteController implements the CRUD actions for Cliente model.
 */
class ClienteController extends Controller
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
                    [
                        'allow' => true,'roles' => ['executante'],
                        'matchCallback' => function ($rule, $action) {
                            $cargo = Yii::$app->db->createCommand('SELECT cargo FROM executante WHERE usuario_id='.Yii::$app->user->id)->queryScalar();
                            if($cargo==2){
                                return true;
                            }
                            else{
                                return false;
                            }
                        }
                    ],                  
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Cliente models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClienteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cliente model.
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
     * Creates a new Cliente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cliente();

        $searchModel = new ClienteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(isset($_GET['pagination'])) $dataProvider->pagination = false;  

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
              if(UploadedFile::getInstance($model,'logo') != null){

                    $nomeOriginal = UploadedFile::getInstance($model,'logo')->name;
                    $extensao = explode('.', $nomeOriginal)[1];
                    
                    $model->logo = UploadedFile::getInstance($model,'logo');                
                    $model->logo->name = $model->logo->name;     

                    $fileName = $model->id;  
                    
                    $model->logo->saveAs(Yii::$app->basePath.'/web/logos/'.$fileName.'.'.$extensao);                
                    $model->logo = $fileName.'.'.$extensao;
                }
                $model->save();
            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Updates an existing Cliente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $searchModel = new ClienteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($model->load(Yii::$app->request->post()) ) {
           if(UploadedFile::getInstance($model,'logo') != null){

                    $nomeOriginal = UploadedFile::getInstance($model,'logo')->name;
                    $extensao = explode('.', $nomeOriginal)[1];
                    
                    $model->logo = UploadedFile::getInstance($model,'logo');                
                    $model->logo->name = $model->logo->name;     

                    $fileName = $model->id;  
                    
                    $model->logo->saveAs(Yii::$app->basePath.'/web/logos/'.$fileName.'.'.$extensao);                
                    $model->logo = $fileName.'.'.$extensao;
                }
                $model->save();
            return $this->redirect(['create']);
        } else {
            return $this->render('update', [
                'model' => $model,
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
     * Deletes an existing Cliente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
       $projeto = Yii::$app->db->createCommand('SELECT id FROM projeto WHERE cliente_id='.$id)->queryScalar();
        if(empty($projeto)){
            Yii::$app->db->createCommand('DELETE FROM contato WHERE cliente_id='.$id)->execute();
            $this->findModel($id)->delete();
        }
        else{
            //botar uma mensagem (setFlash)
            echo 'Existe um projeto utilizando este contato';
        }

        return $this->redirect(['create']);
    }

    /**
     * Finds the Cliente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cliente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cliente::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

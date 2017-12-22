<?php

namespace app\controllers;

use Yii;
use app\models\Executante;
use app\models\ExecutanteTipo;
use app\models\DBUser;
use app\models\search\ExecutanteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * ExecutanteController implements the CRUD actions for Executante model.
 */
class ExecutanteController extends Controller
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
     * Lists all Executante models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExecutanteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Executante model.
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
     * Creates a new Executante model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $searchModel = new ExecutanteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new Executante();
        $user = new DBUser();
        $exectipo = new ExecutanteTipo();

        $tipos_executantes = Yii::$app->db->createCommand('SELECT id, cargo FROM tipo_executante')->queryAll();
        $listTipos = ArrayHelper::map($tipos_executantes,'id','cargo');

        if ($_POST) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->setAttributes($_POST['Executante']);

                $user->setAttributes($_POST['DBUser']);
                $user->username = $user->email;               
                $user->save();

                $model->usuario_id = $user->id;
                $model->criado = date('Y-m-d h:m:s');
                $model->save();



                foreach ($_POST['Tipos'] as $key => $tipo) {
                    Yii::$app->db->createCommand('INSERT INTO executante_tipo (tipo_id, executante_id) VALUES ('.$tipo.', '.$model->usuario_id.')')->execute();    
                }

                $auth = \Yii::$app->authManager;
                $authorRole = $auth->getRole('executante');
                $auth->assign($authorRole, $user->getId());  

                $transaction->commit();
                return $this->redirect(['create']);                
            }
            catch(Exception $e){
                $transaction->rollBack();
                throw $e;
            }
        }
        else {
            return $this->render('create', [
                'model' => $model,
                'user' => $user,
                'listTipos' => $listTipos,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Updates an existing Executante model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $searchModel = new ExecutanteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = $this->findModel($id);
        $user = DBUser::find()->where(['id' => $id])->one(); 
        $beforeSenha = $user->password;

        $tipos_executantes = Yii::$app->db->createCommand('SELECT id, cargo FROM tipo_executante')->queryAll();
        $listTipos = ArrayHelper::map($tipos_executantes,'id','cargo');

        if ($_POST) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->setAttributes($_POST['Executante']);

                $user->load(Yii::$app->request->post());
                $user->username = $_POST['DBUser']['email'];
                $user->email = $_POST['DBUser']['email']; 
                $user->password = $_POST['DBUser']['password'];    
                
                $user->save();

                if(empty($_POST['DBUser']['password'])){                   
                    Yii::$app->db->createCommand('UPDATE user SET password="'.$beforeSenha.'" WHERE id='.$user->id)->execute();   
                }       

                $model->usuario_id = $user->id;
                $model->criado = date('Y-m-d h:m:s');
                $model->save();

               

                $transaction->commit();
                return $this->redirect(['create']);                
            }
            catch(Exception $e){
                $transaction->rollBack();
                throw $e;
            }
        }
        else {
            return $this->render('create', [
                'model' => $model,
                'user' => $user,
                'listTipos' => $listTipos,
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
     * Deletes an existing Executante model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $atividade = Yii::$app->db->createCommand('SELECT id FROM atividade WHERE executante_id='.$id)->queryScalar();
        
        if(empty($atividade)){
            Yii::$app->db->createCommand('DELETE FROM executante_tipo WHERE executante_id='.$id)->execute();
            $this->findModel($id)->delete();
        }
        else{
            //botar uma mensagem (setFlash)
            echo 'Existe uma atividade utilizando este executante';
        }

        return $this->redirect(['create']);
    }

    /**
     * Finds the Executante model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Executante the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Executante::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPreenchehora()
    {
        if (Yii::$app->request->isAjax) {                 
            echo json_encode(Yii::$app->db->createCommand('SELECT valor_hora FROM tipo_executante WHERE id='.Yii::$app->request->post()['id'])->queryScalar());  
        }
    }
}

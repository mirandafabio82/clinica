<?php

namespace app\controllers;

use Yii;
use app\models\Contato;
use app\models\Cliente;
use app\models\DBUser;
use app\models\search\ContatoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;

/**
 * ContatoController implements the CRUD actions for Contato model.
 */
class ContatoController extends Controller
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
     * Lists all Contato models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContatoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contato model.
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
     * Creates a new Contato model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $model = new Contato();
        $user = new DBUser();
        $clientes = Yii::$app->db->createCommand('SELECT id, nome FROM cliente')->queryAll();
        $listClientes = ArrayHelper::map($clientes,'id','nome');

        if ($_POST) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->setAttributes($_POST['Contato']);

                $user->setAttributes($_POST['DBUser']);
                $user->username = $user->email;               
                $user->save();

                $model->usuario_id = $user->id;
                $model->criado = date('Y-m-d h:m:s');
                $model->save();

                $auth = \Yii::$app->authManager;
                $authorRole = $auth->getRole('contato');
                $auth->assign($authorRole, $user->getId());  

                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->usuario_id]);                
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
                'listClientes' => $listClientes
            ]);
        }
    }

    /**
     * Updates an existing Contato model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = new DBUser();
        $clientes = Yii::$app->db->createCommand('SELECT id, nome FROM cliente')->queryAll();
        $listClientes = ArrayHelper::map($clientes,'id','nome');

        if ($_POST) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->setAttributes($_POST['Contato']);

                $user->setAttributes($_POST['DBUser']);
                $user->username = $user->email;               
                $user->save();

                $model->usuario_id = $user->id;
                $model->criado = date('Y-m-d h:m:s');
                $model->save();

                $auth = \Yii::$app->authManager;
                $authorRole = $auth->getRole('contato');
                $auth->assign($authorRole, $user->getId());  

                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->usuario_id]);                
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
                'listClientes' => $listClientes
            ]);
        }
    }

    /**
     * Deletes an existing Contato model.
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
     * Finds the Contato model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contato the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contato::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

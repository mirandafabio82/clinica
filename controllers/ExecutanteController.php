<?php

namespace app\controllers;

use Yii;
use app\models\Executante;
use app\models\DBUser;
use app\models\search\ExecutanteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

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
        $model = new Executante();
        $user = new DBUser();
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

                $auth = \Yii::$app->authManager;
                $authorRole = $auth->getRole('executante');
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
                'listTipos' => $listTipos
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
        $model = $this->findModel($id);
        $user = new DBUser();
        $tipos_executantes = Yii::$app->db->createCommand('SELECT id, cargo FROM tipo_executante')->queryAll();
        $listTipos = ArrayHelper::map($tipos_executantes,'id','cargo');

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
     * Deletes an existing Executante model.
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
}

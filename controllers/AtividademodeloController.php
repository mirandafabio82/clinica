<?php

namespace app\controllers;

use Yii;
use app\models\Atividademodelo;
use app\models\search\AtividademodeloSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['*'],
                'rules' => [
                    ['allow' => true,'roles' => ['admin']],
                    ['allow' => true,'roles' => ['executante']],                    
                    ['actions' => ['index', 'view'],'allow' => true,'roles' => ['executante']],
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
        $model2 = new Atividademodelo();
        $model3 = new Atividademodelo();
        $model4 = new Atividademodelo();

        $searchModel = new AtividademodeloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $escopo = Yii::$app->db->createCommand('SELECT id, nome FROM escopopadrao')->queryAll();
        $listEscopo = ArrayHelper::map($escopo,'id','nome');

        $disciplina = Yii::$app->db->createCommand('SELECT id, nome FROM disciplina')->queryAll();
        $listDisciplina = ArrayHelper::map($disciplina,'id','nome');

        
        if(isset($_GET['pagination'])) $dataProvider->pagination = false;        

        
        if ($model->load(Yii::$app->request->post()) ) {
            if(empty($model->escopopadrao_id) || !isset($model->escopopadrao_id)){
                $model->escopopadrao_id = 0;
            }
            //atualiza ordem
            $existePosicao = Yii::$app->db->createCommand('SELECT id FROM atividademodelo WHERE escopopadrao_id='.$model->escopopadrao_id.' AND ordem='.$model->ordem)->queryScalar();

            if(!empty($existePosicao)){
                $atividades = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE escopopadrao_id='.$model->escopopadrao_id.' AND ordem > '.$model->ordem)->queryAll();
                foreach ($atividades as $key => $atv) {
                    Yii::$app->db->createCommand('UPDATE atividademodelo SET ordem=ordem+1 WHERE id='.$atv['id'])->execute();
                }
            }

           if($model->disciplina_id==0){ //se não tiver disciplina
                $model->disciplina_id = 1;
                
                $model2->setAttributes($_POST['Atividademodelo']);
                $model2->disciplina_id = 2;
                if(empty($model2->escopopadrao_id) || !isset($model2->escopopadrao_id)){
                    $model2->escopopadrao_id = 0;
                }
                $model2->save();

                $model3->setAttributes($_POST['Atividademodelo']);
                $model3->disciplina_id = 3;
                if(empty($model3->escopopadrao_id) || !isset($model3->escopopadrao_id)){
                    $model3->escopopadrao_id = 0;
                }
                $model3->save();

                $model4->setAttributes($_POST['Atividademodelo']);
                $model4->disciplina_id = 4;
                if(empty($model4->escopopadrao_id) || !isset($model4->escopopadrao_id)){
                    $model4->escopopadrao_id = 0;
                }
                $model4->save();
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
        $model2 = new Atividademodelo();
        $model3 = new Atividademodelo();
        $model4 = new Atividademodelo();

        $searchModel = new AtividademodeloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $escopo = Yii::$app->db->createCommand('SELECT id, nome FROM escopopadrao')->queryAll();
        $listEscopo = ArrayHelper::map($escopo,'id','nome');

        $disciplina = Yii::$app->db->createCommand('SELECT id, nome FROM disciplina')->queryAll();
        $listDisciplina = ArrayHelper::map($disciplina,'id','nome');

        $dataProvider->pagination = false;   
        $sort = '-escopopadrao_id';
        if(!empty($_GET['sort'])){
            $sort = $_GET['sort'];           
        }
        

        if ($model->load(Yii::$app->request->post()) ) {
            
            $model->setAttributes($_POST['Atividademodelo']); 
            if(empty($model->escopopadrao_id) || !isset($model->escopopadrao_id)){
                $model->escopopadrao_id = 0;
            }
           if($model->disciplina_id==0){
                $model->disciplina_id = 1;
                
                $model2->setAttributes($_POST['Atividademodelo']);
                $model2->disciplina_id = 2;
                if(empty($model2->escopopadrao_id) || !isset($model2->escopopadrao_id)){
                    $model2->escopopadrao_id = 0;
                }
                $model2->save();

                $model3->setAttributes($_POST['Atividademodelo']);
                $model3->disciplina_id = 3;
                if(empty($model3->escopopadrao_id) || !isset($model3->escopopadrao_id)){
                    $model3->escopopadrao_id = 0;
                }
                $model3->save();

                $model4->setAttributes($_POST['Atividademodelo']);
                $model4->disciplina_id = 4;
                if(empty($model4->escopopadrao_id) || !isset($model4->escopopadrao_id)){
                    $model4->escopopadrao_id = 0;
                }
                $model4->save();
           }           

            $model->save();

            return $this->redirect(['update', 'id' => $model->id, 'sort' => $sort]);
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

    //habilitar ajax
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
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

        return $this->redirect(['create']);
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

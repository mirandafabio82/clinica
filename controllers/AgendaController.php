<?php

namespace app\controllers;

use Yii;
use app\models\Agenda;
use app\models\search\AgendaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use \Datetime;
use yii\helpers\Json;
use app\models\Log;
/**
 * AgendaController implements the CRUD actions for Agenda model.
 */
class AgendaController extends Controller
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
     * Lists all Agenda models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AgendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Agenda model.
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
     * Creates a new Agenda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
         $searchModel = new AgendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(isset($_GET['pagination'])) $dataProvider->pagination = false;

        $model = new Agenda();
        $projetos = Yii::$app->db->createCommand('SELECT id, nome FROM projeto')->queryAll();
        $listProjetos = ArrayHelper::map($projetos,'id','nome');

        $sites = Yii::$app->db->createCommand('SELECT id, nome FROM site')->queryAll();
        $listSites = ArrayHelper::map($sites,'id','nome');

        $status = Yii::$app->db->createCommand('SELECT id, status FROM agenda_status')->queryAll();
        $listStatus = ArrayHelper::map($status,'id','status');

        $executantes = Yii::$app->db->createCommand('SELECT usuario_id, nome, cor FROM executante JOIN user ON executante.usuario_id = user.id')->queryAll();
        $listExecutantes = ArrayHelper::map($executantes,'usuario_id','nome');

        $contatos = Yii::$app->db->createCommand('SELECT id, nome FROM contato JOIN user ON contato.usuario_id = user.id')->queryAll();
        $listContatos = ArrayHelper::map($contatos,'id','nome');

        $arrayEventos = Yii::$app->db->createCommand('SELECT * FROM agenda')->queryAll();

        $proj_autocomplete = '';
            foreach ($projetos as $key => $pr) {  
                $proj_autocomplete .= '"'.$pr['nome'].'", ';
        } 

        $cont_autocomplete = '';
            foreach ($contatos as $key => $ct) {  
                $cont_autocomplete .= '"'.$ct['nome'].'", ';
        } 

        $resp_autocomplete = '';
            foreach ($executantes as $key => $res) {  
                $resp_autocomplete .= '"'.$res['nome'].'", ';
        } 
 
        if($_POST){            
            $model->setAttributes($_POST['Agenda']); 

            
            $model->hr_inicio = str_replace('T', ' ', $_POST['Agenda']['hr_inicio']);
            $model->hr_final = str_replace('T', ' ', $_POST['Agenda']['hr_final']);

            if(!$model->save()){
                print_r($model->getErrors());
                die();
            }

           
            $_SESSION['msg'] = '<div class="alert alert-success" role="alert">Cadastrado com sucesso</div';

            $destinatario = Yii::$app->db->createCommand('SELECT email FROM user WHERE nome = "'.$model->responsavel.'"')->queryScalar();
            $assunto = 'Evento Adicionado à sua Agenda';
            $corpoEmail = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id = '.Yii::$app->user->getId())->queryScalar().' lhe adicionou ao evento '.$model->assunto.' de '.date('d/m/Y H:i', strtotime($model->hr_inicio)).' até '. date('d/m/Y H:i', strtotime($model->hr_final)).'.';
            
                try{
                    Yii::$app->mailer->compose()
                    ->setFrom('hcnautomacaoweb@gmail.com')
                    ->setTo(trim($destinatario))
                    ->setSubject($assunto)
                    ->setTextBody($corpoEmail)
                    ->send();
                }
                catch(Exception $e){
                    print_r($e);
                    die();
                }

            $user_nome = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.Yii::$app->user->getId())->queryScalar();
            $logModel = new Log();
            $logModel->user_id = Yii::$app->user->getId();
            $logModel->descricao = $user_nome.' criou o evento '.$model->assunto.'. Inicio:'.$model->hr_inicio.' Fim: '.$model->hr_final;
            $logModel->data = Date('Y-m-d H:i:s');
            if(!$logModel->save()){
                print_r($logModel->getErrors());
                die();
            }
              
            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'listProjetos' => $listProjetos,
                'listSites' => $listSites,
                'listStatus' => $listStatus,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'arrayEventos' => $arrayEventos,
                'listContatos' => $listContatos,
                'listExecutantes' => $listExecutantes,
                'arrayExecutantes' => $executantes,
                'proj_autocomplete' => $proj_autocomplete,
                'cont_autocomplete' => $cont_autocomplete,
                'resp_autocomplete' => $resp_autocomplete,
            ]);
        }
    }

    /**
     * Updates an existing Agenda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $searchModel = new AgendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = $this->findModel($id);
               
        if($_POST){
            $model->setAttributes($_POST['Agenda']);            
            $model->hr_inicio = str_replace('T', ' ', $_POST['Agenda']['hr_inicio']);
            $model->hr_final = str_replace('T', ' ', $_POST['Agenda']['hr_final']);

            if(!$model->save()){
                print_r($model->getErrors());
                die();
            }

            $_SESSION['msg'] = '<div class="alert alert-success" role="alert">Atualizado com sucesso</div';
            
            $user_nome = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.Yii::$app->user->getId())->queryScalar();
            $logModel = new Log();
            $logModel->user_id = Yii::$app->user->getId();
            $logModel->descricao = $user_nome.' editou o evento '.$model->assunto.'. Inicio:'.$model->hr_inicio.' Fim: '.$model->hr_final;
            $logModel->data = Date('Y-m-d H:i:s');
            if(!$logModel->save()){
                print_r($logModel->getErrors());
                die();
            }

            return $this->redirect(['create']);
        }else {
            return $this->render('update');
        }
    }

     //habilitar ajax
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    /**
     * Deletes an existing Agenda model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel(Yii::$app->request->post()['id']);
            $this->findModel(Yii::$app->request->post()['id'])->delete();

            $user_nome = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.Yii::$app->user->getId())->queryScalar();
            $logModel = new Log();
            $logModel->user_id = Yii::$app->user->getId();
            $logModel->descricao = $user_nome.' excluiu o evento '.$model->assunto.'. Inicio:'.$model->hr_inicio.' Fim: '.$model->hr_final;
            $logModel->data = Date('Y-m-d H:i:s');
            if(!$logModel->save()){
                print_r($logModel->getErrors());
                die();
            }
            return $this->redirect(['create']);
        }
    }

    /**
     * Finds the Agenda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Agenda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Agenda::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionGetevent(){
        if (Yii::$app->request->isAjax) {                 
            return json_encode(Yii::$app->db->createCommand('SELECT projeto, DATE_FORMAT(hr_inicio, "%Y-%m-%dT%H:%i:%s") AS hr_inicio, DATE_FORMAT(hr_final, "%Y-%m-%dT%H:%i:%s") AS hr_final, local, responsavel, contato, assunto, status, descricao, prazo, pendente, cor FROM agenda WHERE id ='.Yii::$app->request->post()['id'])->queryOne());  
        }
        
    }

    public function actionUpdateevent(){
        if (Yii::$app->request->isAjax) {               
            if(empty(Yii::$app->request->post()['hr_final'])){
                return json_encode(Yii::$app->db->createCommand('UPDATE agenda SET hr_inicio="'.Yii::$app->request->post()['hr_inicio'].'" WHERE id='.Yii::$app->request->post()['id'])->execute());  
            }else{
                return json_encode(Yii::$app->db->createCommand('UPDATE agenda SET hr_inicio="'.Yii::$app->request->post()['hr_inicio'].'", hr_final="'.Yii::$app->request->post()['hr_final'].'" WHERE id='.Yii::$app->request->post()['id'])->execute());  
            }  
        }
        
    }
}

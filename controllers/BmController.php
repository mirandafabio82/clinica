<?php

namespace app\controllers;

use Yii;
use app\models\Bm;
use app\models\search\BmSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use \Datetime;
use app\models\Projeto;
use yii\filters\AccessControl;
use app\models\Documento;
/**
 * BmController implements the CRUD actions for Bm model.
 */
class BmController extends Controller
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
     * Lists all Bm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BmSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bm model.
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
     * Creates a new Bm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bm();
         $searchModel = new BmSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $projetos = Yii::$app->db->createCommand('SELECT id, nome FROM projeto')->queryAll();
        $listProjetos = ArrayHelper::map($projetos,'id','nome');

        if(isset($_GET['pagination'])) $dataProvider->pagination = false;  

        if ($model->load(Yii::$app->request->post())) {
            $ultBM = $model->numero_bm+1;
            if(!empty($_POST['Bm']['data'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Bm']['data']);          
                $model->data = date_format($dat, 'Y-m-d');
            }
            if(!empty($_POST['Bm']['de'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Bm']['de']);          
                $model->de = date_format($dat, 'Y-m-d');
            }
            if(!empty($_POST['Bm']['para'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Bm']['para']);          
                $model->para = date_format($dat, 'Y-m-d');
            }

            $model->save();
            Yii::$app->db->createCommand('UPDATE config SET ultimo_bm ='.$ultBM)->execute();
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'listProjetos' => $listProjetos,
        ]);
    }

    /**
     * Updates an existing Bm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new BmSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $projetos = Yii::$app->db->createCommand('SELECT id, nome FROM projeto')->queryAll();
        $listProjetos = ArrayHelper::map($projetos,'id','nome');

        if(!empty($model->data))
            $model->data = date_format(DateTime::createFromFormat('Y-m-d', $model->data), 'd/m/Y');
        if(!empty($model->de))
            $model->de = date_format(DateTime::createFromFormat('Y-m-d', $model->de), 'd/m/Y');
        if(!empty($model->para))
            $model->para = date_format(DateTime::createFromFormat('Y-m-d', $model->para), 'd/m/Y');

        if ($model->load(Yii::$app->request->post())) {
            
            if(!empty($_POST['Bm']['data'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Bm']['data']);          
                $model->data = date_format($dat, 'Y-m-d');
            }
            if(!empty($_POST['Bm']['de'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Bm']['de']);          
                $model->de = date_format($dat, 'Y-m-d');
            }
            if(!empty($_POST['Bm']['para'])){
                $dat = DateTime::createFromFormat('d/m/Y', $_POST['Bm']['para']);          
                $model->para = date_format($dat, 'Y-m-d');
            }

            $model->save();            
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'listProjetos' => $listProjetos,
        ]);
    }

    /**
     * Deletes an existing Bm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['create']);
    }

      //habilitar ajax
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionPreencheform(){
        if (Yii::$app->request->isAjax) {    
            $data = Yii::$app->db->createCommand('SELECT * FROM projeto WHERE id='.Yii::$app->request->post()['id'])->queryOne(); 

            $horas = Yii::$app->db->createCommand('SELECT SUM(executado_ee) executado_ee,SUM(executado_es) executado_es,SUM(executado_ep) executado_ep,SUM(executado_ej) executado_ej,SUM(executado_tp) executado_tp, SUM(horas_ee) horas_ee,SUM(horas_es) horas_es,SUM(horas_ep) horas_ep,SUM(horas_ej) horas_ej,SUM(horas_tp) horas_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.Yii::$app->request->post()['id'])->queryOne();

            $tot_horas = $horas['horas_ee'] + $horas['horas_es'] + $horas['horas_ep'] + $horas['horas_ej'] + $horas['horas_tp'];

            $exec = $horas['executado_ee']+$horas['executado_es']+$horas['executado_ep']+$horas['executado_ej']+$horas['executado_tp'];

            $saldo = $tot_horas - $exec;
            
            array_push($data, $saldo, $exec,$horas['executado_ee'],$horas['executado_es'],$horas['executado_ep'],$horas['executado_ej'],$horas['executado_tp']);

            echo json_encode($data);  
        }
        
    }

    /**
     * Finds the Bm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGerarbm()
    {
        if($_GET['id']){
            $bm = Yii::$app->db->createCommand('SELECT * FROM bm WHERE id='.$_GET['id'])->queryOne();
            $projeto = Projeto::findOne($bm['projeto_id']);
            $escopos = Yii::$app->db->createCommand('SELECT SUM(horas_tp) h_tp,SUM(horas_ej) h_ej,SUM(horas_ep) h_ep,SUM(horas_es) h_es,SUM(horas_ee) h_ee,SUM(executado_tp) executado_tp,SUM(executado_ej) executado_ej,SUM(executado_ep) executado_ep,SUM(executado_es) executado_es,SUM(executado_ee) executado_ee, escopo.nome as nomeE FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$projeto->id)->queryOne();

            $tipo_exec = Yii::$app->db->createCommand('SELECT * FROM tipo_executante')->queryAll();


            $bm_page = $this->renderPartial('relatorio/_bm', [
                'bm' => $bm,
                'projeto' => $projeto,
                'escopos' => $escopos,
                'tipo_exec' => $tipo_exec]);

            if (!file_exists('uploaded-files/'.$projeto['id'])) {
                mkdir('uploaded-files/'.$projeto['id'], 0777, true);
            }

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($bm_page);   
            $mpdf->Output('uploaded-files/'.$projeto['id'].'/BM-'.$projeto['proposta'].'_'.$bm['numero_bm'].'.pdf', 'F');     
            $mpdf->Output('uploaded-files/'.$projeto['id'].'/BM-'.$projeto['proposta'].'_'.$bm['numero_bm'].'.pdf', 'I');         

            $existsFile = Yii::$app->db->createCommand('SELECT id FROM documento WHERE nome="BM-'.$projeto['proposta'].'_'.$bm['numero_bm'].'.pdf"')->queryScalar();

            if(empty($existsFile)){                          
                //cria o registro do arquivo
                $doc = new Documento();
                $doc->projeto_id = $bm['projeto_id'];
                $doc->nome = 'BM-'.$projeto->proposta.'_'.$bm['numero_bm'].'.pdf';
                $doc->revisao = 0;
                $doc->path = 'BM-'.$projeto->proposta.'_'.$bm['numero_bm'].'.pdf';
                $doc->save();
            }

        }
    }
}

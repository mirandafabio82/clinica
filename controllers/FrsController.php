<?php

namespace app\controllers;

use Yii;
use app\models\Frs;
use app\models\Bm;
use app\models\search\FrsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;
use \Datetime;

/**
 * FrsController implements the CRUD actions for Frs model.
 */
class FrsController extends Controller
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
     * Lists all Frs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FrsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new Frs();

        if (!empty($_POST)) {         

            // $targetPath = 'uploads/'.$_FILES['file']['name'];
            // move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

            $temp = explode(".", $_FILES['Frs']['name']['id']);            
            $extension = end($temp);  

            if (!is_dir(Yii::$app->basePath . '/web/uploaded-files/temp_files')) {
                mkdir(Yii::$app->basePath . '/web/uploaded-files/temp_files');
                FileHelper::createDirectory(Yii::$app->basePath . '/web/uploaded-files/temp_files', $mode = 0775, $recursive = true);
            }

            $path = Yii::$app->basePath . '/web/uploaded-files/temp_files/temp_import_frs.'.$extension; 
            
            $file = \yii\web\UploadedFile::getInstance($model, 'id');
            $file->saveAs($path);


            $objPHPExcel = \PHPExcel_IOFactory::load($path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            
            foreach ($sheetData as $key => $line) {
                if($key <= 1){
                    continue;
                }
                
                $frs_id = Yii::$app->db->createCommand('SELECT id FROM frs WHERE frs LIKE "%'.$line['C'].'%"')->queryScalar();

                $model = new Frs();

                if(!empty($frs_id)){
                  $model = $model::findOne($frs_id);
                }

                $date_e = explode('-',$line['E']);
                $date_g = explode('-',$line['G']);

                $model->contrato = ''.$line['A'];
                $model->pedido = ''.$line['B'];
                $model->frs = ''.$line['C'];
                $model->criador = $line['D'];
                $model->data_criacao = !empty($line['E']) ? $date_e[2].'-'.$date_e[0].'-'.$date_e[1] : '';
                $model->aprovador = $line['F'];
                $model->data_aprovacao = !empty($line['G']) ? $date_g[2].'-'.$date_g[0].'-'.$date_g[1] : '';
                $model->cnpj_emitente = $line['H'];
                $model->cnpj_braskem = $line['I'];
                $model->valor = str_replace(',','',$line['J']);
                $model->nota_fiscal = ''.$line['K'];
                $model->referencia = ''.$line['L'];
                $model->texto_breve = ''.$line['M'];
                $numero_bm_frs = ''.$line['N'];               

                $tipo_exec = Yii::$app->db->createCommand('SELECT * FROM tipo_executante')->queryAll();

                $bm_id =Yii::$app->db->createCommand('
                                    SELECT bm.id
                                        FROM bm 
                                            JOIN projeto ON bm.projeto_id = projeto.id
                                            JOIN cliente ON cliente.id = projeto.cliente_id
                                        WHERE
                                            bm.numero_bm = '.$numero_bm_frs)->queryScalar();
                if(!empty($bm_id)){
                    $bm_model = new Bm();
                    $bm_model = $bm_model::findOne($bm_id);
                    $bm_model->frs_numero = $model->frs; 
                    $bm_model->frs_data_aprovacao = $model->data_aprovacao;
                    $model->bm = $bm_model->numero_bm;

                    if(!$bm_model->save()){
                        print_r($bm_model->getErrors());
                        die();
                    } 
                }

                if(!$model->save()){
                    print_r($model->getErrors());
                    die();
                }

            }

        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

     //habilitar ajax
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Displays a single Frs model.
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
     * Creates a new Frs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Frs();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Frs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Frs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Frs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Frs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Frs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

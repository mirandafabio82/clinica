<?php

namespace app\controllers;

use Yii;
use app\models\Nfse;
use app\models\search\NfseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;
use \Datetime;

/**
 * NfseController implements the CRUD actions for Nfse model.
 */
class NfseController extends Controller
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
     * Lists all Nfse models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NfseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new Nfse();

        if (!empty($_POST)) {         

            // $targetPath = 'uploads/'.$_FILES['file']['name'];
            // move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

            $temp = explode(".", $_FILES['Nfse']['name']['id']);            
            $extension = end($temp);  

            if (!is_dir(Yii::$app->basePath . '/web/uploaded-files/temp_files')) {
                mkdir(Yii::$app->basePath . '/web/uploaded-files/temp_files');
                FileHelper::createDirectory(Yii::$app->basePath . '/web/uploaded-files/temp_files', $mode = 0775, $recursive = true);
            }

            $path = Yii::$app->basePath . '/web/uploaded-files/temp_files/temp_import_nfse.'.$extension; 
            
            $file = \yii\web\UploadedFile::getInstance($model, 'id');
            $file->saveAs($path);


            $objPHPExcel = \PHPExcel_IOFactory::load($path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            
            foreach ($sheetData as $key => $line) {
                if($key <= 1){
                    continue;
                }
                
                $model = new Nfse();
                $date_d = explode('-',$line['D']);
                $date_e = explode('-',$line['E']);
                $date_j = explode('-',$line['J']);

                $model->processo = ''.$line['A'];
                $model->nota_fiscal = ''.$line['B'];
                $model->serie = $line['C'];
                $model->data_emissao = !empty($line['D']) ? $date_d[2].'-'.$date_d[0].'-'.$date_d[1] : '';
                $model->data_entrega = !empty($line['E']) ? $date_e[2].'-'.$date_e[0].'-'.$date_e[1] : '';
                $model->status = $line['F'];
                $model->pendencia = $line['G'];
                $model->nf_devolvida = $line['H'];
                $model->comentario_devolucao = $line['I'];
                $model->data_pagamento = !empty($line['J']) ? $date_j[2].'-'.$date_j[0].'-'.$date_j[1] : '';
                $model->usuario_pendencia = $line['K'];
                $model->cnpj_emitente = $line['L'];

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
     * Displays a single Nfse model.
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
     * Creates a new Nfse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Nfse();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Nfse model.
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
     * Deletes an existing Nfse model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Nfse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Nfse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Nfse::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

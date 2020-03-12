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
use yii\helpers\ArrayHelper;


/**
 * ImpressaoController implements the CRUD actions for Frs model.
 */
class ImpressaoController extends Controller
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

    public function actionIndex()
    {
        $documentos = Yii::$app->db->createCommand('SELECT id_tipo_impressao, nome_documento FROM tipo_impressao')->queryAll();
        $listDocumentos = ArrayHelper::map($documentos, 'id_tipo_impressao', 'nome_documento');

        return $this->render('index', [
            'listDocumentos' => $listDocumentos,
        ]);
    }

    //habilitar ajax
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionGerarfileanaminese()
    {

        // This will need to be the path relative to the root of your app.
        $filePath = '/views/impressao/relatorio/';
        // Might need to change '@app' for another alias
        $completePath = Yii::getAlias('@app' . $filePath . 'VS_Bloco Anaminese_21x30.pdf');

        return Yii::$app->response->sendFile($completePath, null, ['inline' => true]);
    }

    public function actionGerarfileatestado()
    {

        // This will need to be the path relative to the root of your app.
        $filePath = '/views/impressao/relatorio/';
        // Might need to change '@app' for another alias
        $completePath = Yii::getAlias('@app' . $filePath . 'VS_Bloco Atestado_15x21.pdf');

        return Yii::$app->response->sendFile($completePath, null, ['inline' => true]);
    }

    public function actionGerarfilereceituario()
    {

        // This will need to be the path relative to the root of your app.
        $filePath = '/views/impressao/relatorio/';
        // Might need to change '@app' for another alias
        $completePath = Yii::getAlias('@app' . $filePath . 'VS_Bloco Receituario_15x21.pdf');

        return Yii::$app->response->sendFile($completePath, null, ['inline' => true]);
    }
}

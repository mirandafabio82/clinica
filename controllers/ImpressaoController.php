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
        $status = Yii::$app->db->createCommand('SELECT id, status FROM agenda_status')->queryAll();
        $listStatus = ArrayHelper::map($status, 'id', 'status');

        return $this->render('index', [
            'listStatus' => $listStatus,
        ]);
    }

    //habilitar ajax
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionGeraratestadocomparecimento()
    {
        
        $page = $this->renderPartial('relatorio/_atestado', [
            'nome' => 'Rafael de Oliveira Bahia',
            'rg' => '14.023.550-76',
            'data' => '01/março/2020'
        ]);

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($page);
        $mpdf->Output();
    }
}

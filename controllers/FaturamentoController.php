<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;


class FaturamentoController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

      //habilitar ajax
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionReadbm()
    {
        if (Yii::$app->request->isAjax) {    
        	Yii::$app->request->post()['file_bm'];
        }
    }

    public function actionReadfrs()
    {
        if (Yii::$app->request->isAjax) {    
        	//Yii::$app->request->post()['file_frs'];

            header("Content-type: application/pdf");
            $file = readfile('C:\xampp\htdocs\hcn\web\uploaded-files\64\BM-AS-BSK-PE1-0300177_0_97.pdf');

            return $file;
        }
    }

    public function actionReadnfse()
    {
        if (Yii::$app->request->isAjax) {    
        	Yii::$app->request->post()['file_nfse'];
        }
    }

}

<?php

namespace app\controllers;

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
        	Yii::$app->request->post()['file_frs'];
        }
    }

    public function actionReadnfse()
    {
        if (Yii::$app->request->isAjax) {    
        	Yii::$app->request->post()['file_nfse'];
        }
    }

}

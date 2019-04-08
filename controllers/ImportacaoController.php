<?php

namespace app\controllers;

class ImportacaoController extends \yii\web\Controller
{
    public function actionFrs()
    {
        return $this->render('frs');
    }

    public function actionNfse()
    {
        return $this->render('nfse');
    }

}

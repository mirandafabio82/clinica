<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'index'],
                'rules' => [
                    ['actions' => ['logout', 'index'],'allow' => true,'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $emitirAS = Yii::$app->db->createCommand('SELECT count(id) FROM projeto WHERE status=6')->queryScalar();
        $aguardando = Yii::$app->db->createCommand('SELECT count(id) FROM projeto WHERE status=5')->queryScalar();
        $concluido = Yii::$app->db->createCommand('SELECT count(id) FROM projeto WHERE status=2')->queryScalar();
        $numBm = Yii::$app->db->createCommand('SELECT ultimo_bm FROM config')->queryScalar();

        $pagamentos_dia = Yii::$app->db->createCommand('SELECT * FROM bm_executante WHERE previsao_pgt BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 5 DAY) AND pago = 0 LIMIT 5')->queryAll();

        $logs = Yii::$app->db->createCommand('SELECT * FROM log ORDER BY id DESC LIMIT 5')->queryAll();

        return $this->render('index', [
            'emitirAS' => $emitirAS,
            'aguardando' => $aguardando,
            'concluido' => $concluido,
            'numBm' => $numBm,
            'logs' => $logs,
            'pagamentos_dia' => $pagamentos_dia,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        $this->layout = 'login';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

       return $this->redirect(['login']);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    /*public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }*/

    /**
     * Displays about page.
     *
     * @return string
     */
    /*public function actionAbout()
    {
        return $this->render('about');
    }*/
}

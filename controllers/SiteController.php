<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\helpers\ArrayHelper;

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

        $arrayEventos = Yii::$app->db->createCommand('SELECT * FROM agenda')->queryAll();

        if(isset($_POST['contato']) && !empty($_POST['contato'])){
                $contatos = Yii::$app->db->createCommand('SELECT id AS usuario_id FROM user WHERE nome = "'.$_POST['contato'].'" ORDER BY id DESC')->queryAll();                
            }
            else{
                $contatos = Yii::$app->db->createCommand('SELECT usuario_id FROM contato ORDER BY usuario_id DESC')->queryAll();
            }


            $conts = '';            
            foreach ($contatos as $key => $contato) { 
                if(sizeof($contatos)-1 == 0){
                    $conts .= $contato['usuario_id'];
                    continue;
                }     
                if(empty($contato['usuario_id']) || sizeof($contatos)-1 == $key )      {
                    $conts = substr($conts, 0, -1);
                    continue;
                }       
                $conts .= $contato['usuario_id'].','; 
            }

            /*if(isset($_POST['executante'])){
                $executantes = Yii::$app->db->createCommand('SELECT DISTINCT executante_id FROM projeto_executante WHERE executante_id IN ('.implode(',', $_POST['executante']).') ORDER BY executante_id DESC')->queryAll();
            }
            else{
                $executantes = Yii::$app->db->createCommand('SELECT DISTINCT executante_id FROM projeto_executante ORDER BY executante_id DESC')->queryAll();
            }

            $exec = '';            
            foreach ($executantes as $key2 => $executante) {    
                if(empty($executante['executante_id']) || sizeof($executantes)-1 == $key2){
                    $exec = substr($exec, 0, -1);
                    continue;                    
                }         
                $exec .= $executante['executante_id'].','; 
            }*/
            $bm = ''; 
            if(isset($_POST['bm']))
                $bm = ' AND count(bm.id) > 0';            

            $frs = ''; 
            if(isset($_POST['frs']))
                $frs = $_POST['frs'];

            $mostrar_valor = ''; 
            if(isset($_POST['valor']))
                $mostrar_valor = $_POST['valor'];

            $as_de = ''; 
            if(!empty($_POST['as_de']))
                $as_de = ' AND criado > "'.$_POST['as_de'].'"';

            $as_ate = ''; 
            if(!empty($_POST['as_ate']))
                $as_ate = ' AND criado < "'.$_POST['as_ate'].'"';

            $bm_de = ''; 
            if(!empty($_POST['bm_de']))
                $bm_de = ' AND data > "'.$_POST['bm_de'].'"';

            $bm_ate = ''; 
            if(!empty($_POST['bm_ate']))
                $bm_ate = ' AND data < "'.$_POST['bm_ate'].'"';

           
            if(isset($_POST['projeto']) && !empty($_POST['projeto'])){  
                $projetos = Yii::$app->db->createCommand('SELECT projeto.id AS projetoID, nome, projeto.descricao, site, projeto.contato, proposta, valor_proposta, projeto.qtd_km, nota_geral FROM projeto JOIN bm ON bm.projeto_id = projeto.id WHERE projeto.nome = "'.$_POST['projeto'].'" AND contato_id IN ('.$conts.') '.$as_de.' '.$as_ate.' '.$bm_de.' '.$bm_ate.' ORDER BY projeto.id DESC')->queryAll();
            }
            else{
                $projetos = Yii::$app->db->createCommand('SELECT projeto.id AS projetoID, nome, projeto.descricao, site, projeto.contato, proposta, valor_proposta, projeto.qtd_km, nota_geral FROM projeto JOIN bm ON bm.projeto_id = projeto.id WHERE contato_id IN ('.$conts.') '.$as_de.' '.$as_ate.' '.$bm_de.' '.$bm_ate.' ORDER BY projeto.id DESC')->queryAll();
            }
            $listProjetos = ArrayHelper::map($projetos,'id','nome');

        return $this->render('index', [
            'emitirAS' => $emitirAS,
            'aguardando' => $aguardando,
            'concluido' => $concluido,
            'numBm' => $numBm,
            'logs' => $logs,
            'pagamentos_dia' => $pagamentos_dia,
            'arrayEventos' => $arrayEventos,
            'projetos' => $projetos,
            'listProjetos' => $listProjetos,
            'com_bm' => $bm,
            'com_frs' => $frs,
            'mostrar_valor' => $mostrar_valor
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

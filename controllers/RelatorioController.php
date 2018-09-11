<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use \Datetime;

/**
 * TarefaController implements the CRUD actions for Tarefa model.
 */
class RelatorioController extends Controller
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
     * Lists all Tarefa models.
     * @return mixed
     */
    public function actionIndex()
    {       

       $prestadores = Yii::$app->db->createCommand('SELECT * FROM executante JOIN user ON user.id=executante.usuario_id WHERE is_prestador=1')->queryAll();
       $listPrestadores = ArrayHelper::map($prestadores,'id','nome');

        return $this->render('index', [
            'listPrestadores' => $listPrestadores
        ]);
    }

    /**
     * Displays a single Tarefa model.
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
     * Creates a new Tarefa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
    }

    /**
     * Updates an existing Tarefa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
    }

    /**
     * Deletes an existing Tarefa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        
    }

    //habilitar ajax
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionTabelaextrato(){  
        if (Yii::$app->request->isAjax) {                 
            $executante_id = Yii::$app->request->post()['executante_id'];

            $total = Yii::$app->db->createCommand('SELECT bm.id, projeto.nome, bm.numero_bm, projeto.descricao, bm.executado_ee, bm.executado_es, bm.executado_ep, bm.executado_ej, bm.executado_tp, executante.vl_hh_tp, executante.vl_hh_ej, executante.vl_hh_ep, executante.vl_hh_es, executante.vl_hh_ee, bm_executante.data_pgt FROM bm JOIN projeto ON bm.projeto_id=projeto.id JOIN projeto_executante ON projeto_executante.projeto_id=projeto.id JOIN executante ON executante.usuario_id=projeto_executante.executante_id JOIN bm_executante ON bm.id=bm_executante.bm_id  WHERE projeto_executante.executante_id='.$executante_id.' AND bm_executante.executante_id='.$executante_id)->queryAll();

            foreach ($total as $key => $tot) { 

              $horas_exe_ee = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ee) h_ee FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_ee_id='.$executante_id)->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ee) h_ee FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_ee_id='.$executante_id)->queryScalar();
              $horas_exe_es =empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_es) h_es FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_es_id='.$executante_id)->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_es) h_es FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_es_id='.$executante_id)->queryScalar();
              $horas_exe_ep = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ep) h_ep FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_ep_id='.$executante_id)->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ep) h_ep FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_ep_id='.$executante_id)->queryScalar();
              $horas_exe_ej = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ej) h_ej FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_ej_id='.$executante_id)->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_ej) h_ej FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_ej_id='.$executante_id)->queryScalar();
              $horas_exe_tp = empty(Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_tp) h_tp FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_tp_id='.$executante_id)->queryScalar()) ? '0.00' : Yii::$app->db->createCommand('SELECT SUM(bm_escopo.horas_tp) h_tp FROM bm_escopo JOIN escopo ON bm_escopo.escopo_id=escopo.id WHERE bm_id = '.$tot['id'].' AND exe_tp_id='.$executante_id)->queryScalar();

                $hora = ['numero_bm' => $tot['numero_bm'], 'executado_ee' => $horas_exe_ee, 'executado_es' => $horas_exe_es, 'executado_ep' => $horas_exe_ep, 'executado_ej' => $horas_exe_ej, 'executado_tp' => $horas_exe_tp];
                array_push($total, $hora);
            }


            return json_encode($total);

        }        
    }

    public function actionRelatorioextrato(){  
        
        $bms = explode('-', $_GET['bms']);
        $executante_id = $_GET['executante_id'];

        $executante = Yii::$app->db->createCommand('SELECT * FROM executante JOIN user ON executante.usuario_id=user.id WHERE executante.usuario_id='.$executante_id)->queryOne();

        $executante_extrato_page = $this->renderPartial('_extrato_executante', [
            'bms' => $bms,    
            'prestador' => $executante,       
            'date' => date('d/m/Y'),      
        ]);

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($executante_extrato_page);   
        $mpdf->Output('uploaded-files/extratos/Extrato-temp.pdf', 'I');  
    }
    
}

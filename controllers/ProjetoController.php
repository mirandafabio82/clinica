<?php

namespace app\controllers;

use Yii;
use app\models\Projeto;
use app\models\Cliente;
use app\models\Contato;
use app\models\Escopo;
use app\models\search\EscopoSearch;
use app\models\search\ProjetoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use \Datetime;
use yii\helpers\Url;
// use kartik\mpdf\Pdf;

require_once  'C:\xampp\htdocs\hcn\vendor\autoload.php';
/**
 * ProjetoController implements the CRUD actions for Projeto model.
 */
class ProjetoController extends Controller
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
     * Lists all Projeto models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjetoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->post('Projeto')){
            $projeto_id = Yii::$app->request->post('editableKey');
            $projeto = Projeto::findOne($projeto_id);

            $out = Json::encode(['output'=>'', 'message'=>'']);
            $post =[];
            $posted = current($_POST['Projeto']);
            $post['Projeto'] = $posted;

            if($projeto->load($post)){
                $projeto->save();
                // $output = 'teste';
                $out = Json::encode(['output'=>'', 'message'=>'']);
            }
            echo $out;
            // return;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Projeto model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Projeto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Projeto();
        $model->tipo = 'A';
        $searchModel = new ProjetoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->post('editableKey')){
            try{
                $projeto_id = Yii::$app->request->post('editableKey');
                $projeto = Projeto::findOne($projeto_id);

                $out = Json::encode(['output'=>'', 'message'=>'']);
                $post =[];
                $posted = current($_POST['Projeto']);
                $post['Projeto'] = $posted;                
                $projeto->save();
               
                // $output = 'teste';
                $out = Json::encode(['output'=>'', 'message'=>'']);
                
                echo $out;
                return $this->redirect(['create']);
            }
            catch(Exception $e){
                throw $e;
            }
        }

        $clientes = Yii::$app->db->createCommand('SELECT id, CONCAT(nome," - " ,site) as nome FROM cliente')->queryAll();
        $listClientes = ArrayHelper::map($clientes,'id','nome');

        $sites = Yii::$app->db->createCommand('SELECT id, nome FROM site')->queryAll();
        $listSites = ArrayHelper::map($sites,'id','nome');

        $nomes = Yii::$app->db->createCommand('SELECT id, nome FROM projeto_nome')->queryAll();
        $listNomes = ArrayHelper::map($nomes,'id','nome');

        $contatos = Yii::$app->db->createCommand('SELECT id, nome FROM contato JOIN user ON contato.usuario_id = user.id')->queryAll();
        $listContatos = ArrayHelper::map($contatos,'id','nome');

        $escopo = Yii::$app->db->createCommand('SELECT id, nome FROM escopopadrao')->queryAll();
        $listEscopo = ArrayHelper::map($escopo,'id','nome');

        $disciplina = Yii::$app->db->createCommand('SELECT id, nome FROM disciplina')->queryAll();
        $listDisciplina = ArrayHelper::map($disciplina,'id','nome');

        $status = Yii::$app->db->createCommand('SELECT id, status FROM projeto_status')->queryAll();
        $listStatus = ArrayHelper::map($status,'id','status');

        $searchEscopo = new EscopoSearch();
        $escopoDataProvider = $searchEscopo->search(Yii::$app->request->queryParams);
        $escopoDataProvider->query->join('join','atividademodelo', 'atividademodelo.id=escopo.atividademodelo_id')->where('1=2');


        if ($model->load(Yii::$app->request->post())) {
            try{
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                $model->setAttributes($_POST['Projeto']); 
                if($model->tipo == "P"){
                    $model->proposta = '(PTC)'.'-'.$model->codigo.'-'.$model->site.'-'.$model->rev_proposta;
                }
                else{
                    $model->proposta = '(AS)'.'-'.$model->codigo.'-'.$model->site;
                }
                

                if($model->save()){       

                    if(isset($_POST['Escopos'])){
                        $escopos = $_POST['Escopos'];
                        $automacoes = isset($escopos['Automação']) ? $escopos['Automação'] : array();
                        $processos = isset($escopos['Processo']) ? $escopos['Processo'] : array();
                        $instrumentacoes = isset($escopos['Instrumentação']) ? $escopos['Instrumentação'] : array();

                        foreach ($automacoes as $key => $automacao) {
                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE escopopadrao_id='.$automacao.' AND disciplina_id = 1')->queryAll();
                            
                            foreach ($atvmodelos as $key => $atv) {
                                $escopo_model = new Escopo();
                                $escopo_model->projeto_id = $model->id;
                                $escopo_model->atividademodelo_id = $atv['id'];
                                $escopo_model->nome = $atv['nome'];
                                $escopo_model->descricao = $atv['nome'];
                                $escopo_model->save();
                            }
                        }
                        foreach ($processos as $key => $processo) {

                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE escopopadrao_id='.$processo.' AND disciplina_id = 2')->queryAll();

                            foreach ($atvmodelos as $key => $atv) {
                                $escopo_model = new Escopo();
                                $escopo_model->projeto_id = $model->id;
                                $escopo_model->atividademodelo_id = $atv['id'];
                                $escopo_model->nome = $atv['nome'];
                                $escopo_model->descricao = $atv['nome'];
                                $escopo_model->save();
                            }
                        }
                        foreach ($instrumentacoes as $key => $instrumentacao) {

                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE escopopadrao_id='.$instrumentacao.' AND disciplina_id = 3')->queryAll();

                            foreach ($atvmodelos as $key => $atv) {
                                $escopo_model = new Escopo();
                                $escopo_model->projeto_id = $model->id;
                                $escopo_model->atividademodelo_id = $atv['id'];
                                $escopo_model->nome = $atv['nome'];
                                $escopo_model->descricao = $atv['nome'];
                                $escopo_model->save();
                            }
                        }

                     
                    }                    
                }

                $transaction->commit();
                return $this->redirect(['update', 'id' => $model->id]);
            }
            catch(Exception $e){
                $transaction->rollBack();
                throw $e;
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'listClientes' => $listClientes,
                'listContatos' => $listContatos,
                'listEscopo' => $listEscopo,
                'listSites' => $listSites,
                'listNomes' => $listNomes,
                'listStatus' => $listStatus,
                'listDisciplina' => $listDisciplina,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'escopoDataProvider' => $escopoDataProvider,
                'searchEscopo' => $searchEscopo

            ]);
        }
    }

    /**
     * Updates an existing Projeto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id); 
        if(!empty($model->data_pendencia))
            $model->data_pendencia = date_format(DateTime::createFromFormat('Y-m-d', $model->data_pendencia), 'd/m/Y');
        if(!empty($model->data_entrega))
            $model->data_entrega = date_format(DateTime::createFromFormat('Y-m-d', $model->data_entrega), 'd/m/Y');

        $searchModel = new ProjetoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->post('editableKey')){
             
            /*if(isset($_POST['Escopo'])){

                $escopo_id = Yii::$app->request->post('editableKey');
                $escopo = Escopo::findOne($escopo_id);

                $out = Json::encode(['output'=>'', 'message'=>'']);
                $post =[];
                $posted = current($_POST['Escopo']);
                $post['Escopo'] = $posted;

                if($escopo->load($post)){
                    $escopo->save();
                    // $output = 'teste';
                    $out = Json::encode(['output'=>'', 'message'=>'']);
                }
                echo $out;
                return $this->redirect(['update', 'id' => $model->id]);
            }*/
               
            if(isset($_POST['Projeto'])){
                $projeto_id = Yii::$app->request->post('editableKey');
                $projeto = Projeto::findOne($projeto_id);

                $out = Json::encode(['output'=>'', 'message'=>'']);
                $post =[];
                $posted = current($_POST['Projeto']);
                $post['Projeto'] = $posted;

                if($projeto->load($post)){
                    $projeto->save();
                    // $output = 'teste';
                    $out = Json::encode(['output'=>'', 'message'=>'']);
                }
                echo $out;
                return $this->redirect(['update', 'id' => $model->id]);
            }

        }

 
        $clientes = Yii::$app->db->createCommand('SELECT id, CONCAT(nome," - " ,site) as nome FROM cliente')->queryAll();
        $listClientes = ArrayHelper::map($clientes,'id','nome');

        $sites = Yii::$app->db->createCommand('SELECT id, nome FROM site')->queryAll();
        $listSites = ArrayHelper::map($sites,'id','nome');

        $nomes = Yii::$app->db->createCommand('SELECT id, nome FROM projeto_nome')->queryAll();
        $listNomes = ArrayHelper::map($nomes,'id','nome');

        $contatos = Yii::$app->db->createCommand('SELECT id, nome FROM contato JOIN user ON contato.usuario_id = user.id WHERE cliente_id='.$model->cliente_id)->queryAll();
        $listContatos = ArrayHelper::map($contatos,'id','nome');

        $escopo = Yii::$app->db->createCommand('SELECT id, nome FROM escopopadrao')->queryAll();
        $listEscopo = ArrayHelper::map($escopo,'id','nome');

        $disciplina = Yii::$app->db->createCommand('SELECT id, nome FROM disciplina')->queryAll();
        $listDisciplina = ArrayHelper::map($disciplina,'id','nome');

        $status = Yii::$app->db->createCommand('SELECT id, status FROM projeto_status')->queryAll();
        $listStatus = ArrayHelper::map($status,'id','status');

        $escopoArray = Yii::$app->db->createCommand('SELECT * FROM escopo WHERE projeto_id='.$model->id)->queryAll();

        $executantes_tp = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id WHERE tipo_executante.id =1')->queryAll();
        $listExecutantes_tp = ArrayHelper::map($executantes_tp,'exec_id','nome'); 

        $executantes_ej = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id WHERE tipo_executante.id =2')->queryAll();
        $listExecutantes_ej = ArrayHelper::map($executantes_ej,'exec_id','nome'); 

        $executantes_ep = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id WHERE tipo_executante.id =3')->queryAll();
        $listExecutantes_ep = ArrayHelper::map($executantes_ep,'exec_id','nome');

        $executantes_es = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id WHERE tipo_executante.id =4')->queryAll();
        $listExecutantes_es = ArrayHelper::map($executantes_es,'exec_id','nome'); 

        $executantes_ee = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id WHERE tipo_executante.id =5')->queryAll();
        $listExecutantes_ee = ArrayHelper::map($executantes_ee,'exec_id','nome'); 

        
        $searchEscopo = new EscopoSearch();
        $escopoDataProvider = $searchEscopo->search(Yii::$app->request->queryParams);
        $escopoDataProvider->query->join('join','atividademodelo', 'atividademodelo.id=escopo.atividademodelo_id')->where('projeto_id='.$model->id);

         //atualizando os valores de hora e executante do escopo
        if(isset($_POST['Escopo'])){
            
            $totalHoras = 0;

            foreach ($_POST['Escopo'] as $key => $esc) {
                $totalHoras = $totalHoras + $esc['horas_tp'] 
                                            + $esc['horas_ej'] 
                                            + $esc['horas_ep']
                                            + $esc['horas_es']
                                            + $esc['horas_ee'];
            }
            
            
           Yii::$app->db->createCommand('UPDATE projeto SET total_horas='.$totalHoras.' WHERE id='.$model->id)->execute();
           $model->total_horas = $totalHoras;
            
            foreach ($_POST['Escopo'] as $key => $esc) {

                $escopo = Escopo::findIdentity($key);
                if(!empty($escopo)){                   

                    $escopo->setAttributes($_POST['Escopo'][$key]);
                    $escopo->save();
                }
            }
           
        }

          if ($model->load(Yii::$app->request->post())) {
            try{
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();

                $model->setAttributes($_POST['Projeto']);      
                if($model->tipo == "P"){
                    $model->proposta = '(PTC)'.'-'.$model->codigo.'-'.$model->site.'-'.$model->rev_proposta;
                }
                else{
                    $model->proposta = '(AS)'.'-'.$model->codigo.'-'.$model->site;
                }

                if($model->save()){                        
                    if(isset($_POST['Escopos'])){
                        $escopos = $_POST['Escopos'];
                        $automacoes = isset($escopos['Automação']) ? $escopos['Automação'] : array();
                        $processos = isset($escopos['Processo']) ? $escopos['Processo'] : array();
                        $instrumentacoes = isset($escopos['Instrumentação']) ? $escopos['Instrumentação'] : array();

                        
                        if(!isset($automacoes[1]))
                         Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id NOT IN(SELECT id FROM atividademodelo WHERE escopopadrao_id=1 AND disciplina_id=1 AND projeto_id=) AND projeto_id='.$model->id)->execute();

                        if(!isset($automacoes[2]))
                         Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id NOT IN(SELECT id FROM atividademodelo WHERE escopopadrao_id=2 AND disciplina_id=1) AND projeto_id='.$model->id )->execute();

                        if(!isset($automacoes[3]))
                         Yii::$app->db->createCommand('DELETE FROM escopo WHERE atividademodelo_id NOT IN(SELECT id FROM atividademodelo WHERE escopopadrao_id=3 AND disciplina_id=1) AND projeto_id='.$model->id)->execute();

                        foreach ($automacoes as $key => $automacao) {
                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE escopopadrao_id='.$automacao.' AND disciplina_id = 1')->queryAll();

                            foreach ($atvmodelos as $key => $atv) {
                                $existeEscopo = Yii::$app->db->createCommand('SELECT id FROM escopo WHERE projeto_id='.$model->id.' AND atividademodelo_id='.$atv['id'])->queryScalar();
                                


                                if(!$existeEscopo){
                                    $escopo_model = new Escopo();
                                    $escopo_model->projeto_id = $model->id;
                                    $escopo_model->atividademodelo_id = $atv['id'];
                                    $escopo_model->nome = $atv['nome'];
                                    $escopo_model->descricao = $atv['nome'];
                                    $escopo_model->save();
                                }
                            }
                        }
                        foreach ($processos as $key => $processo) {
                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE escopopadrao_id='.$processo.' AND disciplina_id = 2')->queryAll();

                            foreach ($atvmodelos as $key => $atv) {
                                $existeEscopo = Yii::$app->db->createCommand('SELECT id FROM escopo WHERE projeto_id='.$model->id.' AND atividademodelo_id='.$atv['id'])->queryScalar();
                                if(!$existeEscopo){
                                    $escopo_model = new Escopo();
                                    $escopo_model->projeto_id = $model->id;
                                    $escopo_model->atividademodelo_id = $atv['id'];
                                    $escopo_model->nome = $atv['nome'];
                                    $escopo_model->descricao = $atv['nome'];
                                    $escopo_model->save();
                                }
                            }
                        }
                        foreach ($instrumentacoes as $key => $instrumentacao) {
                            $atvmodelos = Yii::$app->db->createCommand('SELECT * FROM atividademodelo WHERE escopopadrao_id='.$instrumentacao.' AND disciplina_id = 3')->queryAll();

                            foreach ($atvmodelos as $key => $atv) {
                                $existeEscopo = Yii::$app->db->createCommand('SELECT id FROM escopo WHERE projeto_id='.$model->id.' AND atividademodelo_id='.$atv['id'])->queryScalar();
                                if(!$existeEscopo){
                                    $escopo_model = new Escopo();
                                    $escopo_model->projeto_id = $model->id;
                                    $escopo_model->atividademodelo_id = $atv['id'];
                                    $escopo_model->nome = $atv['nome'];
                                    $escopo_model->descricao = $atv['nome'];
                                    $escopo_model->save();
                                }
                            }
                        }
                    }                    
                }

                $transaction->commit();
                return $this->redirect(['update', 'id' => $model->id]);
            }
            catch(Exception $e){
                $transaction->rollBack();
                throw $e;
            }
            catch(Exception $e){
                $transaction->rollBack();
                throw $e;
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'listClientes' => $listClientes,
                'listContatos' => $listContatos,
                'listEscopo' => $listEscopo,
                'listSites' => $listSites,
                'listNomes' => $listNomes,
                'listStatus' => $listStatus,
                'listDisciplina' => $listDisciplina,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'escopoDataProvider' => $escopoDataProvider,
                'searchEscopo' => $searchEscopo,
                'escopoArray' => $escopoArray,
                'listExecutantes_tp' => $listExecutantes_tp,
                'listExecutantes_ej' => $listExecutantes_ej,
                'listExecutantes_ep' => $listExecutantes_ep,
                'listExecutantes_es' => $listExecutantes_es,
                'listExecutantes_ee' => $listExecutantes_ee,
            ]);
        }
    }

    /**
     * Deletes an existing Projeto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $projeto = Yii::$app->db->createCommand('SELECT id FROM projeto WHERE cliente_id='.$id)->queryScalar();
        if(empty($projeto)){
        }
        $this->findModel($id)->delete();

        return $this->redirect(['create']);
    }

    /**
     * Finds the Projeto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Projeto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Projeto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

     //habilitar ajax
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionPreencheformcliente(){
        if (Yii::$app->request->isAjax) {                 
            echo json_encode(Yii::$app->db->createCommand('SELECT * FROM cliente WHERE id='.Yii::$app->request->post()['id'])->queryOne());  
        }
        
    }

    public function actionPreenchepreenchecontatos(){
        if (Yii::$app->request->isAjax) {                 
            echo json_encode(Yii::$app->db->createCommand('SELECT * FROM contato JOIN user ON contato.usuario_id = user.id WHERE cliente_id='.Yii::$app->request->post()['id'])->queryAll());  
        }
        
    }

    public function actionPreencheformcontato(){
        if (Yii::$app->request->isAjax) {                 
            echo json_encode(Yii::$app->db->createCommand('SELECT * FROM contato JOIN user ON user.id = contato.usuario_id WHERE usuario_id='.Yii::$app->request->post()['id'])->queryOne());  
        }
        
    }

    public function actionPreencheformsite(){
        if (Yii::$app->request->isAjax) {                 
            echo json_encode(Yii::$app->db->createCommand('SELECT * FROM planta WHERE site_id='.Yii::$app->request->post()['id'])->queryAll());  
        }
        
    }

    public function actionGerarrelatorio()
    {
        if($_GET['id']){
            $projeto = Projeto::findOne($_GET['id']);
          
            
            $processoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=2 AND projeto_id='.$projeto->id)->queryAll();
            $automacaoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=1 AND projeto_id='.$projeto->id)->queryAll();
            $instrumentacaoArray = Yii::$app->db->createCommand('SELECT * FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=3 AND projeto_id='.$projeto->id)->queryAll();

            /*return $this->render('_planilha', [
                'projeto' => $projeto,
            ]);*/

            $capa = $this->renderPartial('relatorio\_capa', [
                'projeto' => $projeto]);

            $as = $this->renderPartial('relatorio\_as', [
                'projeto' => $projeto]);

            $processo = $this->renderPartial('relatorio\_processo', [
                'projeto' => $projeto,
                'escopos' => $processoArray]);

            $automacao = $this->renderPartial('relatorio\_automacao', [
                'projeto' => $projeto,
                'escopos' => $automacaoArray]);

            $instrumentacao = $this->renderPartial('relatorio\_instrumentacao', [
                'projeto' => $projeto,
                'escopos' => $instrumentacaoArray]);

            $resumo = $this->renderPartial('relatorio\_resumo', [
                'projeto' => $projeto]);

            $ld_preliminar = $this->renderPartial('relatorio\_ldpreliminar', [
                'projeto' => $projeto]);


            
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($capa);
            $mpdf->AddPage();
            $mpdf->WriteHTML($as);
            if(!empty($processoArray)){
                $mpdf->AddPage();
                $mpdf->WriteHTML($processo);
            }
            if(!empty($automacaoArray)){
                $mpdf->AddPage();
                $mpdf->WriteHTML($automacao);
            }
            if(!empty($instrumentacaoArray)){
                $mpdf->AddPage();
                $mpdf->WriteHTML($instrumentacao);
            }
            $mpdf->AddPage();
            $mpdf->WriteHTML($resumo);
            $mpdf->AddPage();
            $mpdf->WriteHTML($ld_preliminar);

            $mpdf->Output();
        }

            
     
        
        
    }
}

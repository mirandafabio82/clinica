<?php

namespace app\controllers;

use DateTime;
use Yii;
use app\models\Agendamento;
use app\models\search\AgendaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * AgendaController implements the CRUD actions for Agenda model.
 */
class AgendaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['*'],
                'rules' => [
                    ['allow' => true, 'roles' => ['admin']],
                    ['allow' => true, 'roles' => ['executante']],
                    ['actions' => ['index', 'view'], 'allow' => true, 'roles' => ['executante']],
                ],

            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Agenda models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AgendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Agenda model.
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
     * Creates a new Agenda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $searchModel = new AgendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (isset($_GET['pagination'])) $dataProvider->pagination = false;

        $model = new Agendamento();

        // Cancelar agendamentos não confirmados
        $status = Yii::$app->db->createCommand('UPDATE `agendamento` SET id_status = 3, descricao = "Paciente não confirmou o agendamento" WHERE horario < CURDATE() AND id_status = 1 AND descricao = ""')->execute();

        $status = Yii::$app->db->createCommand('SELECT id, status FROM agenda_status')->queryAll();
        $listStatus = ArrayHelper::map($status, 'id', 'status');

        $consultas = Yii::$app->db->createCommand('SELECT id_agendamento, nome, s.cor as cor FROM agendamento a JOIN agenda_status s ON a.id_status = s.id')->queryAll();
        $listConsultas = ArrayHelper::map($consultas, 'id_agendamento', 'nome');

        $procedimento = Yii::$app->db->createCommand('SELECT id_procedimento, nome FROM procedimento  ORDER BY nome ASC')->queryAll();
        $listProcedimento = ArrayHelper::map($procedimento, 'id_procedimento', 'nome');

        $responsavel = Yii::$app->db->createCommand('SELECT id_responsavel, nome FROM responsavel')->queryAll();
        $listResponsavel = ArrayHelper::map($responsavel, 'id_responsavel', 'nome');

        $arrayEventos = Yii::$app->db->createCommand('SELECT * FROM agendamento a JOIN agenda_status s ON a.id_status = s.id WHERE a.id_status <> 3')->queryAll();

        $bandeira = Yii::$app->db->createCommand('SELECT id_forma_pagamento, bandeira FROM forma_pagamento')->queryAll();
        $listBandeira = ArrayHelper::map($bandeira, 'id_forma_pagamento', 'bandeira');

        $resp_autocomplete = '';
        foreach ($consultas as $key => $res) {
            $resp_autocomplete .= '"' . $res['nome'] . '", ';
        }

        if ($_POST) {
            $model->setAttributes($_POST['Agenda']);


            $model->horario = str_replace('T', ' ', $_POST['Agenda']['horario']);

            $data = new DateTime($model->horario);
            $data->modify('+1 hour');
            $model->horario_final = $data->format('Y-m-d H:i:s');

            if (!$model->save()) {
                print_r($model->getErrors());
                die();
            }


            // $_SESSION['msg'] = '<div class="alert alert-success" role="alert">Cadastrado com sucesso</div';

            // $destinatario = Yii::$app->db->createCommand('SELECT email FROM user WHERE nome = "'.$model->responsavel.'"')->queryScalar();
            // $assunto = 'Evento Adicionado à sua Agenda';
            // $corpoEmail = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id = '.Yii::$app->user->getId())->queryScalar().' lhe adicionou ao evento '.$model->assunto.' de '.date('d/m/Y H:i', strtotime($model->hr_inicio)).' até '. date('d/m/Y H:i', strtotime($model->hr_final)).'.';

            //     try{
            //         Yii::$app->mailer->compose()
            //         ->setFrom('hcnautomacaoweb@gmail.com')
            //         ->setTo(trim($destinatario))
            //         ->setSubject($assunto)
            //         ->setTextBody($corpoEmail)
            //         ->send();
            //     }
            //     catch(Exception $e){
            //         print_r($e);
            //         die();
            //     }

            // $user_nome = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.Yii::$app->user->getId())->queryScalar();
            // $logModel = new Log();
            // $logModel->user_id = Yii::$app->user->getId();
            // $logModel->descricao = $user_nome.' criou o evento '.$model->assunto.'. Inicio:'.$model->hr_inicio.' Fim: '.$model->hr_final;
            // $logModel->data = Date('Y-m-d H:i:s');
            // if(!$logModel->save()){
            //     print_r($logModel->getErrors());
            //     die();
            // }

            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'listStatus' => $listStatus,
                'listProcedimento' => $listProcedimento,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'listResponsavel' => $listResponsavel,
                'arrayEventos' => $arrayEventos,
                'listConsultas' => $listConsultas,
                'resp_autocomplete' => $resp_autocomplete,
                'listBandeira' => $listBandeira
            ]);
        }
    }

    /**
     * Updates an existing Agenda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->post()['id'];
        $up_nome = Yii::$app->request->post()['nome'];
        $up_tipo_atendimento = Yii::$app->request->post()['tipo_atendimento'];
        $up_cpf = Yii::$app->request->post()['cpf'];
        $up_horario = Yii::$app->request->post()['horario'];
        $up_plano_particular = Yii::$app->request->post()['plano_particular'];
        $up_status = Yii::$app->request->post()['status'];
        $up_descricao = Yii::$app->request->post()['descricao'];

        $data = new DateTime($up_horario);
        $data->modify('+1 hour');
        $horario_final = $data->format('Y-m-d H:i:s');

        Yii::$app->db->createCommand('UPDATE agendamento SET nome="' . $up_nome . '",cpf="' . $up_cpf . '",horario="' . $up_horario . '",horario_final="' . $horario_final . '",tipo_atendimento="' . $up_tipo_atendimento . '",plano_particular="' . $up_plano_particular . '",id_status=' . $up_status . ',descricao="' . $up_descricao . '" WHERE id_agendamento=' . $id)->execute();

        return $this->redirect(['create']);
    }


    //habilitar ajax
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    /**
     * Deletes an existing Agenda model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFinish()
    {

        $id = Yii::$app->request->post()['id'];

        $tratamento = Yii::$app->request->post()['tratamento'];
        $dente = Yii::$app->request->post()['dente'];

        Yii::$app->db->createCommand('INSERT INTO tratamento_realizado(id_agendamento, dente, tratamento_realizado) VALUES (' . $id . ',"' . $dente . '","' . $tratamento . '")')->execute();

        Yii::$app->db->createCommand('UPDATE agendamento SET id_status = 4 WHERE id_agendamento= ' . $id)->execute();

        $forma = Yii::$app->request->post()['forma_pagamento'];

        $desconto = Yii::$app->request->post()['desconto_valor'];
        $valor_final = Yii::$app->request->post()['valor_pago'];

        if ($forma == 'money') {
            Yii::$app->db->createCommand('INSERT INTO pagamento(id_agendamento, forma_pagamento, desconto, valor_pago) VALUES (' . $id . ', "Dinheiro", ' . $desconto . ',' . $valor_final . ')')->execute();
        } else {

            $bandeira_card = Yii::$app->request->post()['bandeira_card'];
            $transacao_card = Yii::$app->request->post()['transacao_card'];
            $parcelamento_card = Yii::$app->request->post()['parcelamento_card'];
            $parcelamento_card = explode("x", $parcelamento_card)[0];

            


            Yii::$app->db->createCommand('INSERT INTO pagamento(id_agendamento, forma_pagamento, id_bandeira, tipo_transacao, desconto, valor_pago, parcelamento) VALUES (' . $id . ', "Cartao", ' . $bandeira_card . ', "' . $transacao_card . '" ,' . $desconto . ',' . $valor_final . ',' . $parcelamento_card . ')')->execute();
        }

        return $this->redirect(['create']);
    }

    public function actionDelete()
    {

        $id = Yii::$app->request->post()['id'];

        Yii::$app->db->createCommand('UPDATE agendamento SET id_status = 3 WHERE id_agendamento= ' . $id)->execute();

        return $this->redirect(['create']);
    }

    public function actionDeleteone()
    {

        $id = Yii::$app->request->post()['id'];

        $this->findModel($id)->delete();

        return $this->redirect(['create']);
    }

    public function actionConfirm()
    {

        $id = Yii::$app->request->post()['id'];

        $model =  $this->findModel($id);
        if ($model->descricao == '') {
            Yii::$app->db->createCommand('UPDATE agendamento SET id_status = 2 WHERE id_agendamento= ' . $id)->execute();
        } else if ($model->descricao == 'Paciente não confirmou o agendamento') {
            Yii::$app->db->createCommand('UPDATE agendamento SET id_status = 2, descricao = "" WHERE id_agendamento= ' . $id)->execute();
        }

        return $this->redirect(['create']);
    }

    /**
     * Finds the Agenda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Agenda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Agendamento::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionGetevent()
    {
        if (Yii::$app->request->isAjax) {
            return json_encode(Yii::$app->db->createCommand('SELECT a.nome, p.id_procedimento as tipo_atendimento, p.nome as nome_atendimento, p.valor_inicial as valor_inicial, p.valor_final as valor_final, cpf, DATE_FORMAT(horario, "%Y-%m-%dT%H:%i:%s") AS horario, plano_particular, c.id as status, descricao, c.cor, a.id_responsavel FROM agendamento a JOIN agenda_status c ON (a.id_status = c.id) JOIN procedimento p ON (CONVERT(tipo_atendimento, INT) = p.id_procedimento) WHERE id_agendamento = ' . Yii::$app->request->post()['id'])->queryOne());
        }
    }

    public function actionUpdateevent()
    {

        if (Yii::$app->request->isAjax) {
            if (empty(Yii::$app->request->post()['horario'])) {
                return json_encode(Yii::$app->db->createCommand('UPDATE agendamento SET horario="' . Yii::$app->request->post()['horario'] . '" WHERE id_agendamento=' . Yii::$app->request->post()['id'])->execute());
            }
        }

        return $this->redirect(['create']);
    }

    public function actionGetagendamento()
    {
        if (Yii::$app->request->isAjax) {

            $tipo = Yii::$app->request->post()['tipo'];
            $dataStart = Yii::$app->request->post()['dataStart'];
            $dataEnd = Yii::$app->request->post()['dataEnd'];

            if ($tipo == 'agendaDay') {
                return json_encode(Yii::$app->db->createCommand('SELECT id_agendamento, nome, tipo_atendimento, cpf, DATE_FORMAT(horario, "%Y-%m-%dT%H:%i:%s") AS horario, plano_particular, c.id as status, descricao, c.cor FROM agendamento JOIN agenda_status c ON (agendamento.id_status = c.id) WHERE horario LIKE "'  . $dataStart . '%" ORDER BY id_status ASC,  horario ASC')->queryAll());
            } else {
                return json_encode(Yii::$app->db->createCommand('SELECT id_agendamento, nome, tipo_atendimento, cpf, DATE_FORMAT(horario, "%Y-%m-%dT%H:%i:%s") AS horario, plano_particular, c.id as status, descricao, c.cor FROM agendamento JOIN agenda_status c ON (agendamento.id_status = c.id) WHERE horario BETWEEN "'  . $dataStart . '" AND "' . $dataEnd . '" ORDER BY id_status ASC,  horario ASC')->queryAll());
            }
        }
    }

    public function actionGetformapagamento()
    {
        $bandeira = Yii::$app->request->post()['bandeira'];
        $tipo_transacao = Yii::$app->db->createCommand('SELECT debito, credito_a_vista, credito_parcelado_2x6, credito_parcelado_7x12 FROM `forma_pagamento` WHERE id_forma_pagamento = ' . $bandeira)->queryAll();
        $json = json_encode($tipo_transacao);

        $retorno = "";

        $myArr = array();

        $tipo_transacao[0]['debito'] != '' ? array_push($myArr, "Debito") : "";
        $tipo_transacao[0]['credito_a_vista'] != '' ?  array_push($myArr, "Credito a Vista") : "";
        $tipo_transacao[0]['credito_parcelado_2x6'] != '' ?  array_push($myArr, "Credito Parcelado 2 a 6") : "";
        $tipo_transacao[0]['credito_parcelado_7x12'] != '' ?  array_push($myArr, "Credito Parcelado 7 a 12") : "";

        return json_encode($myArr);
    }
}

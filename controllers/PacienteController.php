<?php

namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use app\models\Paciente;
use app\controllers\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use app\models\search\PacienteSearch;

class PacienteController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
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

    public function actionCreate()
    {
        $searchModel = new PacienteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (isset($_GET['pagination'])) $dataProvider->pagination = false;

        $model = new Paciente();

        $raca = Yii::$app->db->createCommand('SELECT id_cor_pele, nome FROM cor_pele')->queryAll();
        $listRaca = ArrayHelper::map($raca, 'id_cor_pele', 'nome');

        $status = Yii::$app->db->createCommand('SELECT id_status_civil, nome FROM status_civil')->queryAll();
        $listStatusCivil = ArrayHelper::map($status, 'id_status_civil', 'nome');

        if (Yii::$app->request->post()) {
            $model->setAttributes($_POST['Paciente']);

            // Estruturando a data de nascimento
            $data = $model->nascimento;
            $pieces = explode("/", $data);
            $data = $pieces[2] . '-' . $pieces[1] . '-' . $pieces[0];
            $model->nascimento = $data;

            // Estruturando RG e CPF
            $data = $model->cpf;
            $data = str_replace('.', '', $data);
            $data = str_replace('-', '', $data);
            $model->cpf = $data;

            $data = $model->rg;
            $data = str_replace('.', '', $data);
            $data = str_replace('-', '', $data);
            $model->rg = $data;

            $model->save();


            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'listRaca' => $listRaca,
                'listStatusCivil' => $listStatusCivil,
            ]);
        }
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionProfile() {
        return $this->render('profile');
    }

    public function actionGetdata()
    {

        $cpf = Yii::$app->request->post()['cpf'];

        // Estruturando CPF
        $cpf = str_replace('.', '', $cpf);
        $cpf = str_replace('-', '', $cpf);

        return json_encode(Yii::$app->db->createCommand('SELECT id_agendamento, nome, tipo_atendimento, cpf, DATE_FORMAT(horario, "%Y-%m-%dT%H:%i:%s") AS horario, plano_particular, c.id as status, descricao, c.cor FROM agendamento JOIN agenda_status c ON (agendamento.id_status = c.id) WHERE cpf = "' . $cpf . '"')->queryAll());
    }

    public function actionGetdatapaciente()
    {

        $cpf = Yii::$app->request->post()['cpf'];

        // Estruturando CPF
        $cpf = str_replace('.', '', $cpf);
        $cpf = str_replace('-', '', $cpf);

        return json_encode(Yii::$app->db->createCommand('SELECT * FROM paciente WHERE cpf = "' . $cpf . '"')->queryAll());
    }

    public function actionUpdate($id)
    {
        $data = '';

        $searchModel = new PacienteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = $this->findModel($id);
        $data = $model->nascimento;
        $pieces = explode("-", $data);
        $data = $pieces[2] . '/' . $pieces[1] . '/' . $pieces[0];
        $model->nascimento = $data;

        $raca = Yii::$app->db->createCommand('SELECT id_cor_pele, nome FROM cor_pele')->queryAll();
        $listRaca = ArrayHelper::map($raca, 'id_cor_pele', 'nome');

        $status = Yii::$app->db->createCommand('SELECT id_status_civil, nome FROM status_civil')->queryAll();
        $listStatusCivil = ArrayHelper::map($status, 'id_status_civil', 'nome');

        if (Yii::$app->request->post()) {

            $model->setAttributes($_POST['Paciente']);

            // Estruturando a data de nascimento
            $data = $model->nascimento;
            $pieces = explode("/", $data);
            $data = $pieces[2] . '-' . $pieces[1] . '-' . $pieces[0];
            $model->nascimento = $data;

            // Estruturando RG e CPF
            $data = $model->cpf;
            $data = str_replace('.', '', $data);
            $data = str_replace('-', '', $data);
            $model->cpf = $data;

            $data = $model->rg;
            $data = str_replace('.', '', $data);
            $data = str_replace('-', '', $data);
            $model->rg = $data;

            $model->save();

            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'listRaca' => $listRaca,
                'listStatusCivil' => $listStatusCivil,
            ]);
        }
    }


    //habilitar ajax
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Deletes an existing Executante model.
     * If deletion is successful, the browser will be redirected to the 'create' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $this->findModel($id)->delete();

        return $this->redirect(['create']);
    }

    /**
     * Finds the Executante model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Executante the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Paciente::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

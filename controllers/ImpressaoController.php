<?php

namespace app\controllers;

use Yii;
use app\models\Frs;
use app\models\Bm;
use app\models\search\FrsSearch;
use yii\web\Controller;
use app\models\Paciente;
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
        $documentos = Yii::$app->db->createCommand('SELECT id_tipo_impressao, nome_documento FROM tipo_impressao')->queryAll();
        $listDocumentos = ArrayHelper::map($documentos, 'id_tipo_impressao', 'nome_documento');

        $model = new Paciente();

        return $this->render('index', [
            'listDocumentos' => $listDocumentos,
            'model' => $model
        ]);
    }

    //habilitar ajax
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionGerarfileanaminese()
    {

        $id =  Yii::$app->request->post()['Impressao']['id_paciente'];

        if ($id == '') {
            $name = '';
            $data_nascimento = '';
            $cpf = '';
            $endereco = '';
            $profissao = '';
            $telefone = '';
            $celular = '';
            $status_civil = '';
        } else {

            $model = $this->findModel($id);

            $model->nome == null ? $name = '' : $name = $model->nome;
            $model->nascimento == null ? $data_nascimento = '' : $data_nascimento  = $model->nascimento;
            $model->cpf == null ? $cpf = '' : $cpf = $model->cpf;
            $model->endereco == null ? $endereco = '' : $endereco = $model->endereco;
            $model->profissao_empresa == null ? $profissao = '' : $profissao = $model->profissao_empresa;
            $model->telefone == null ? $telefone = '' : $telefone = $model->telefone;
            $model->celular == null ? $celular = '' : $celular = $model->celular;
            $model->estado_civil == null ? $status_civil = '' : $status_civil = $model->estado_civil;
        }

        //$name = $this->stripAccents($name);
        $size_char = 65 - strlen($name);
        $name = strtoupper($name);
        $name = str_repeat('&nbsp;', $size_char / 2) . $name;
        $name = $name . str_repeat('&nbsp;', $size_char / 2);


        $data_nascimento = $this->formatDate($data_nascimento);
        $size_char = 17 - strlen($data_nascimento);
        $data_nascimento = strtoupper($data_nascimento);
        $data_nascimento = str_repeat('&nbsp;', $size_char / 2) . $data_nascimento;
        $data_nascimento = $data_nascimento . str_repeat('&nbsp;', $size_char / 2);

        $cpf = $this->formatCnpjCpf($cpf);
        $size_char = 25 - strlen($cpf);
        $cpf = strtoupper($cpf);
        $cpf = str_repeat('&nbsp;', $size_char / 2) . $cpf;
        $cpf = $cpf . str_repeat('&nbsp;', $size_char / 2);


        //$endereco = $this->stripAccents($endereco);
        $size_char = 100 - strlen($endereco);
        $endereco = strtoupper($endereco);
        $endereco = explode(' - CEP', $endereco)[0];
        $endereco = str_repeat('&nbsp;', $size_char / 2) . $endereco;
        $endereco = $endereco . str_repeat('&nbsp;', $size_char / 2);


        // $profissao = $this->stripAccents($profissao);
        $size_char = 42 - strlen($profissao);
        $profissao = strtoupper($profissao);
        $profissao = str_repeat('&nbsp;', $size_char / 2) . $profissao;
        $profissao = $profissao . str_repeat('&nbsp;', $size_char / 2);


        $size_char = 30 - strlen($telefone);
        $telefone = strtoupper($telefone);
        $telefone = str_repeat('&nbsp;', $size_char / 2) . $telefone;
        $telefone = $telefone . str_repeat('&nbsp;', $size_char / 2);


        $size_char = 30 - strlen($celular);
        $celular = strtoupper($celular);
        $celular = str_repeat('&nbsp;', $size_char / 2) . $celular;
        $celular = $celular . str_repeat('&nbsp;', $size_char / 2);

        $status_civil == 1 ? $status_civil = 'Solteiro(a)' : '';
        $status_civil == 1 ? $status_civil = 'Casado(a)' : '';
        $status_civil == 1 ? $status_civil = 'Viúvo(a)' : '';
        $status_civil == 1 ? $status_civil = 'Divorciado(a)' : '';
        $size_char = 26 - strlen($status_civil);
        $status_civil = strtoupper($status_civil);
        $status_civil = str_repeat('&nbsp;', $size_char / 2) . $status_civil;
        $status_civil = $status_civil . str_repeat('&nbsp;', $size_char / 2);


        $bm_page = $this->renderPartial('relatorio/_anamnese', [
            'nome' => $name,
            'data_nascimento' => $data_nascimento,
            'cpf' => $cpf,
            'endereco' => $endereco,
            'profissao' => $profissao,
            'telefone' => $telefone,
            'celular' => $celular,
            'status_civil' => $status_civil
        ]);

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetJS('window.print();');
        $mpdf->WriteHTML($bm_page);
        $mpdf->Output();
    }

    protected function formatCnpjCpf($value)
    {
        $cnpj_cpf = preg_replace("/\D/", '', $value);

        if (strlen($cnpj_cpf) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        }

        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }

    protected function formatDate($value)
    {
        return explode("-", $value)[2] . "/" . explode("-", $value)[1] . "/" . explode("-", $value)[0];
    }


    public function actionGerarfileatestado()
    {

        // This will need to be the path relative to the root of your app.
        $filePath = '/views/impressao/relatorio/';
        // Might need to change '@app' for another alias
        $completePath = Yii::getAlias('@app' . $filePath . 'VS_Bloco Atestado_15x21.pdf');

        return Yii::$app->response->sendFile($completePath, null, ['inline' => true]);
    }

    public function actionGerarfilereceituario()
    {

        // This will need to be the path relative to the root of your app.
        $filePath = '/views/impressao/relatorio/';
        // Might need to change '@app' for another alias
        $completePath = Yii::getAlias('@app' . $filePath . 'VS_Bloco Receituario_15x21.pdf');

        return Yii::$app->response->sendFile($completePath, null, ['inline' => true]);
    }

    protected function stripAccents($string)
    {

        $string = strtr(
            utf8_decode($string),
            utf8_decode('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
            'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy'
        );

        return $string;
    }

    public function actionGetdatapaciente()
    {

        $cpf = Yii::$app->request->post()['cpf'];
        $nome = Yii::$app->request->post()['nome'];

        // Estruturando CPF
        $cpf = str_replace('.', '', $cpf);
        $cpf = str_replace('-', '', $cpf);

        return json_encode(Yii::$app->db->createCommand('SELECT * FROM paciente WHERE cpf = "' . $cpf . '" AND nome="' . $nome . '"')->queryAll());
    }

    protected function findModel($id)
    {
        if (($model = Paciente::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

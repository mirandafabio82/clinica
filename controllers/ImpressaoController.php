<?php

namespace app\controllers;

use Yii;
use app\models\Frs;
use app\models\Bm;
use app\models\search\FrsSearch;
use yii\web\Controller;
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

        return $this->render('index', [
            'listDocumentos' => $listDocumentos,
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

        // This will need to be the path relative to the root of your app.
        $filePath = '/views/impressao/relatorio/';
        // Might need to change '@app' for another alias
        $completePath = Yii::getAlias('@app' . $filePath . 'VS_Bloco Anaminese_21x30.pdf');

        return Yii::$app->response->sendFile($completePath, null, ['inline' => true]);
    }

    public function actionTeste()
    {

        $name = 'Rafael de Oliveira Bahia';
        $size_char = 60 - strlen($name);
        $name = strtoupper($name);
        $name = str_repeat('&nbsp;', $size_char / 2) . $name;
        $name = $name . str_repeat('&nbsp;', $size_char / 2);

        $data_nascimento = '30/07/1999';
        $size_char = 17 - strlen($data_nascimento);
        $data_nascimento = strtoupper($data_nascimento);
        $data_nascimento = str_repeat('&nbsp;', $size_char / 2) . $data_nascimento;
        $data_nascimento = $data_nascimento . str_repeat('&nbsp;', $size_char / 2);

        $cpf = '065.197.515-88';
        $size_char = 25 - strlen($cpf);
        $cpf = strtoupper($cpf);
        $cpf = str_repeat('&nbsp;', $size_char / 2) . $cpf;
        $cpf = $cpf . str_repeat('&nbsp;', $size_char / 2);

        $endereco = 'Rua Alto do Formoso, nº: 04, Cosme de Farias - CEP 40250-190, Salvador - BA';
        $size_char = 100 - strlen($endereco);
        $endereco = strtoupper($endereco);
        $endereco = explode(' - CEP', $endereco)[0];
        $endereco = str_repeat('&nbsp;', $size_char / 2) . $endereco;
        $endereco = $endereco . str_repeat('&nbsp;', $size_char / 2);

        $profissao = 'Estagiario - EISA';
        $size_char = 42 - strlen($profissao);
        $profissao = strtoupper($profissao);
        $profissao = str_repeat('&nbsp;', $size_char / 2) . $profissao;
        $profissao = $profissao . str_repeat('&nbsp;', $size_char / 2);

        $telefone = '(71) 3382-2820';
        $size_char = 30 - strlen($telefone);
        $telefone = strtoupper($telefone);
        $telefone = str_repeat('&nbsp;', $size_char / 2) . $telefone;
        $telefone = $telefone . str_repeat('&nbsp;', $size_char / 2);

        $celular = '(71) 9 8893-0178';
        $size_char = 30 - strlen($celular);
        $celular = strtoupper($celular);
        $celular = str_repeat('&nbsp;', $size_char / 2) . $celular;
        $celular = $celular . str_repeat('&nbsp;', $size_char / 2);

        $status_civil = 'Solteiro(a)';
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
}

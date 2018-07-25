<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\models\Documento;
use yii\helpers\FileHelper;

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
            if($_FILES['file']['name'] != '')  
             {  
                  $temp = explode(".", $_FILES['file']['name']);
                  $extension = end($temp);  
                  $allowed_type = array("pdf", "PDF");  
                  if(in_array($extension, $allowed_type))  
                  {                           
                        if (!is_dir(Yii::$app->basePath . '/web/uploaded-files/temp_files')) {
                            mkdir(Yii::$app->basePath . '/web/uploaded-files/temp_files');
                            FileHelper::createDirectory(Yii::$app->basePath . '/web/uploaded-files/temp_files', $mode = 0775, $recursive = true);
                        }

                       $path = Yii::$app->basePath . '/web/uploaded-files/temp_files/temp_frs.pdf'; 
                       if(move_uploaded_file($_FILES['file']['tmp_name'], $path))  
                       {  
                            chmod($path, 0775);
                            $parser = new \Smalot\PdfParser\Parser();
                            $pdf = $parser->parseFile($path);
                             
                            $text = $pdf->getText();
                            $num_folha_reg = trim(explode('No. Pedido / item Data', explode('No. da Folha de Registro Data', $text)[1])[0]);
                            $cnpj = trim(explode('I.E:', explode('CNPJ:', $text)[2])[0]);
                            $cnpjMask = "%s%s.%s%s%s.%s%s%s/%s%s%s%s-%s%s";
                            $cnpj = vsprintf($cnpjMask, str_split($cnpj));
                            
                            
                            $cliente = Yii::$app->db->createCommand('SELECT * FROM cliente WHERE cnpj="'.$cnpj.'"')->queryOne();

                            $valor_total = trim(explode(' ', trim(explode(',00', $text)[1]))[0]);


                            $text_compilado = '
SERVIÇO DE AUTOMAÇÃO INDUSTRIAL.
FRS Nº '.$num_folha_reg.'

NÃO RETER INSS. SERVIÇO EXECUTADO PELO SÓCIO NAS INSTALAÇÕES DA '.$cliente['nome'].'-'.$cliente['site'].' NO MUNICÍPIO DE '.$cliente['cidade'].'-'.$cliente['uf'].'.

DEPOSITAR NO BANCO: 033 SANTANDER.
AG: 0933 C/C PJ: 13000565-3.

CNPJ: '.$cnpj.'
Inscrição Municipal: '.$cliente['insc_municipal'].'
Valor: '.$valor_total;

                            return  $text_compilado;
                       }  
                  }  
                  else  
                  {  
                       echo '<script>alert("Invalid File Formate")</script>';  
                  }  
             }  
             else  
             {  
                  echo '<script>alert("Please Select File")</script>';  
             }     	
            

            /*$doc = new Documento();
            $doc->path = Yii::$app->request->post()['frs_file'];
            $doc->projeto_id = 93; //mudar
            
            $doc->revisao = 0;           
            $doc->is_global = 0;
            $doc->path = UploadedFile::getInstance($doc,'path');                
            $doc->path->name = $doc->path->name;     

            $fileName = "{$nomeOriginal}";  
            $doc->nome = $fileName;              

            $doc->path->saveAs(Yii::$app->basePath.'/web/uploaded-files/'.$doc->projeto_id.'/'.$fileName);                
            $doc->path = $fileName;
            $doc->save();*/

            // Parse pdf file and build necessary objects.
            //https://pdfparser.org/documentation
            
            
        }
    }

    public function actionReadnfse()
    {
        if (Yii::$app->request->isAjax) {    
        	Yii::$app->request->post()['file_nfse'];
        }
    }



    

}

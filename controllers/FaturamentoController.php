<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\models\Documento;
use yii\helpers\FileHelper;
use \Datetime;
use app\models\Bm;

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
                            //ler PDF
                            chmod($path, 0775);
                            $parser = new \Smalot\PdfParser\Parser();
                            $pdf = $parser->parseFile($path);
                             
                            $text = $pdf->getText();

                            $num_folha_reg = explode(' ', trim(explode('No. Pedido / item Data', explode('No. da Folha de Registro Data', $text)[1])[0]))[0];
                            $cnpj = trim(explode('I.E:', explode('CNPJ:', $text)[2])[0]);
                            $cnpjMask = "%s%s.%s%s%s.%s%s%s/%s%s%s%s-%s%s";
                            $cnpj = vsprintf($cnpjMask, str_split($cnpj));   
                            $data_aprovacao = explode(' ',explode('No. da Folha de Registro Data', explode('No. Pedido / item Data', trim($text))[0])[1])[2];

                            $data_aprovacao = date('Y-m-d', strtotime($data_aprovacao));
                            
                            $cliente = Yii::$app->db->createCommand('SELECT * FROM cliente WHERE cnpj="'.$cnpj.'"')->queryOne();

                            $valor_total = trim(explode(' ', trim(explode(',00', $text)[1]))[0]);

                            $periodo_de = trim(explode('a', explode('Período:', $text)[1])[0]);
                            $periodo_de = date_format(DateTime::createFromFormat('d.m.Y', $periodo_de), 'Y-m-d'); 

                            $periodo_para = trim(explode(' ',ltrim(explode('a', explode('Período:', $text)[1])[1]))[0]);
                            $periodo_para = date_format(DateTime::createFromFormat('d.m.Y', $periodo_para), 'Y-m-d'); 
                            
                            if(!strpos($text, 'PGT BM') && empty($_POST['frs_num_bm'])){ //nao possui BM na FRS
                              return 'sem_num_bm';
                            }
                            else{
                              if(!strpos($text, 'PGT BM')){
                                $num_bm = $_POST['frs_num_bm'];
                                $ano = trim('20'.explode(' ', explode('.20', $text)[1])[0]);
                              }
                              else{
                                $num_bm = trim(explode('/', explode('PGT BM ', $text)[1])[0]);  
                                $ano = trim(explode(' ',trim(explode('/', explode('PGT BM ', $text)[1])[1]))[0]);
                              }

                              // $projeto_id = Yii::$app->db->createCommand('SELECT projeto_id FROM bm WHERE de="'.$periodo_de.'" AND para ="'.$periodo_para.'"')->queryScalar();
                              $projeto_id = Yii::$app->db->createCommand('SELECT projeto_id FROM bm WHERE numero_bm='.$num_bm)->queryScalar();

                              $projeto_arr = Yii::$app->db->createCommand('SELECT * FROM projeto WHERE id='.$projeto_id)->queryOne();

                              $num_proj = explode('-', $projeto_arr['nome'])[1];

                              $path_projeto = Yii::$app->basePath . '/web/uploaded-files/'.$projeto_arr['id'].'/FRS-'.$projeto_arr['codigo'].'-'.$projeto_arr['site'].'-'.$num_proj.'_'.$num_bm.'_'.$ano.'.pdf';

                              if(!empty($projeto_arr)){
                                  if(copy($path, $path_projeto)){//copia o arquivo para a pasta correta
                                      $exists_frs = Yii::$app->db->createCommand('SELECT id FROM documento WHERE nome="FRS-'.$projeto_arr['codigo'].'-'.$projeto_arr['site'].'-'.$num_proj.'_'.$num_bm.'_'.$ano.'.pdf"')->queryScalar();

                                      if(empty($exists_frs)){
                                          $doc = new Documento();
                                          $doc->projeto_id = $projeto_arr['id'];
                                          $doc->nome = 'FRS-'.$projeto_arr['codigo'].'-'.$projeto_arr['site'].'-'.$num_proj.'_'.$num_bm.'_'.$ano.'.pdf';
                                          $doc->revisao = 0;
                                          $doc->path = 'FRS-'.$projeto_arr['codigo'].'-'.$projeto_arr['site'].'-'.$num_proj.'_'.$num_bm.'_'.$ano.'.pdf';
                                          $doc->is_global = 0;
                                          $doc->data = Date('Y-m-d');
                                          $doc->save();
                                      }                                    
                                  }  
                              }
                              else{
                                  echo 'Essa FRS não foi salva pois não existe nenhum BM com esse número cadastrado no sistema.';
                              }
                            }



                            $text_compilado = '
SERVIÇO DE AUTOMAÇÃO INDUSTRIAL.
FRS Nº '.$num_folha_reg.'

NÃO RETER INSS. SERVIÇO EXECUTADO PELO SÓCIO NAS INSTALAÇÕES DA '.$cliente['nome'].'-'.$cliente['site'].' NO MUNICÍPIO DE '.$cliente['cidade'].'-'.$cliente['uf'].'.

DEPOSITAR NO BANCO: 033 SANTANDER.
AG: 0933 C/C PJ: 13000565-3.

CNPJ: '.$cnpj.'
Inscrição Municipal: '.$cliente['insc_municipal'].'
Valor: '.$valor_total.'
Número do BM: '.$num_bm;

                          //update BM com dados do FRS
                          Yii::$app->db->createCommand('UPDATE bm SET frs_numero = "'.$num_folha_reg.'", frs_data_faturamento="'.date('Y-m-d').'", frs_data_aprovacao="'.$data_aprovacao.'" WHERE numero_bm="'.$num_bm.'"')->execute();
    
                            return  $text_compilado.'##'.$path_projeto;
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

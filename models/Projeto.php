<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "projeto".
 *
 * @property integer $id
 * @property integer $cliente_id
 * @property integer $contato_id
 * @property integer $escopo_id
 * @property string $descricao
 * @property string $codigo
 * @property string $site
 * @property string $planta
 * @property string $municipio
 * @property string $uf
 * @property string $cnpj
 * @property string $tratamento
 * @property string $contato
 * @property string $setor
 * @property string $fone_contato
 * @property string $celular
 * @property string $email
 * @property string $documenstos
 * @property string $proposta
 * @property integer $rev_proposta
 * @property string $data_proposta
 * @property integer $qtd_hh
 * @property string $vl_hh
 * @property string $total_horas
 * @property integer $qtd_dias
 * @property integer $qtd_km
 * @property string $vl_km
 * @property string $total_km
 * @property string $valor_proposta
 * @property string $valor_consumido
 * @property string $valor_saldo
 * @property string $status
 * @property string $data_pendencia
 * @property string $pendencia
 * @property string $comentarios
 * @property string $data_entrega
 * @property string $cliente_fatura
 * @property string $site_fatura
 * @property string $municipio_fatura
 * @property string $uf_fatura
 * @property string $cnpj_fatura
 * @property string $criado
 * @property string $modificado
 *
 * @property Agenda[] $agendas
 * @property Atividade[] $atividades
 * @property Cliente $cliente
 * @property Contato $contato0
 */
class Projeto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projeto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['cliente_id', 'contato_id', 'nome', 'status', 'site', 'planta'], 'required'],
            [['id', 'cliente_id', 'contato_id', 'rev_proposta', 'qtd_hh', 'qtd_dias', 'qtd_km', 'status', 'documentos' ,'executante_id', 'perc_coord_adm'], 'integer'],
            [['data_proposta', 'data_entrega', 'criado', 'modificado'], 'safe'],
            [['vl_hh', 'total_horas', 'vl_km', 'total_km', 'valor_proposta', 'valor_consumido', 'valor_saldo'], 'number'],
            [['descricao'], 'string', 'max' => 500],
            [['codigo', 'contato', 'cliente_fatura'], 'string', 'max' => 12],
            [['site', 'planta', 'site_fatura'], 'string', 'max' => 10],
            [['municipio', 'setor', 'municipio_fatura'], 'string', 'max' => 20],
            [['uf', 'uf_fatura', 'tipo'], 'string', 'max' => 2],
            [['cnpj', 'cnpj_fatura'], 'string', 'max' => 18],
            [['tratamento'], 'string', 'max' => 7],
            [['fone_contato'], 'string', 'max' => 15],
            [['celular'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 30],
            [['proposta'], 'string', 'max' => 50],
            [['comentarios'], 'string', 'max' => 80],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['cliente_id' => 'id']],
            [['contato_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contato::className(), 'targetAttribute' => ['contato_id' => 'usuario_id']],
            [['nome', 'contrato'], 'string', 'max' => 255],
            [['resumo_escopo','resumo_exclusoes','resumo_premissas','resumo_restricoes','resumo_normas','resumo_documentos','resumo_itens','resumo_prazo','resumo_observacoes', 'desc_resumida', 'pendencia'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cliente_id' => 'Cliente',
            'contato_id' => 'Contato',
            'descricao' => 'Descrição',
            'codigo' => 'Código',
            'site' => 'Site',
            'planta' => 'Área',
            'municipio' => 'Municipio',
            'uf' => 'UF',
            'cnpj' => 'CNPJ',
            'tratamento' => 'Tratamento',
            'contato' => 'Contato',
            'setor' => 'Setor',
            'fone_contato' => 'Fone Contato',
            'celular' => 'Celular',
            'email' => 'Email',
            'proposta' => 'Proposta',
            'rev_proposta' => 'Revisão',
            'data_proposta' => 'Data',
            'qtd_hh' => 'Qtd Hh',
            'vl_hh' => 'Valor Hh',
            'total_horas' => 'Total Horas',
            'qtd_dias' => 'Qtd Dias',
            'qtd_km' => 'Qtd Km',
            'vl_km' => 'Valor Km',
            'total_km' => 'Total Km',
            'valor_proposta' => 'Valor da Proposta',
            'valor_consumido' => 'Valor Consumido',
            'valor_saldo' => 'Valor Saldo',
            'status' => 'Status',
            'pendencia' => 'Pendencia',
            'comentarios' => 'Comentarios',
            'data_entrega' => 'Data de Entrega',
            'cliente_fatura' => 'Cliente Fatura',
            'site_fatura' => 'Site Fatura',
            'municipio_fatura' => 'Municipio Fatura',
            'uf_fatura' => 'UF Fatura',
            'cnpj_fatura' => 'CNPJ Fatura',
            'criado' => 'Criado',
            'modificado' => 'Modificado',
            'nome' => 'Projeto',
            'documentos' => 'Qtd Docs',
            'data_pendencia' => 'Data Resp Pendência',
            'tipo' => '',
            'contrato'=> 'Contrato Nº',
            'nota_geral' => 'Notas Gerais',
            'resumo_escopo' => '1. ESCOPO',
            'resumo_exclusoes' =>'2. EXCLUSÕES DE ESCOPO',
            'resumo_premissas' =>'3. PREMISSAS ADOTADAS',
            'resumo_restricoes' =>'4. RESTRIÇÕES',
            'resumo_normas' =>'5. NORMAS E ESPECIFICAÇÕES A SEREM UTILIZADAS',
            'resumo_documentos' =>'6. DOCUMENTOS DE REFÊRENCIA',
            'resumo_itens' =>'7. ITENS DE COMPRA',
            'resumo_prazo' =>'8. PRAZO',
            'resumo_observacoes' =>'OBSERVAÇÕES',
            'desc_resumida' => 'Descrição Resumida dos Serviços',
            'executante_id' => ''
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgendas()
    {
        return $this->hasMany(Agenda::className(), ['projeto_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAtividades()
    {
        return $this->hasMany(Atividade::className(), ['projeto_id' => 'id']);
    }
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContato0()
    {
        return $this->hasOne(Contato::className(), ['usuario_id' => 'contato_id']);
    }

    
}

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
 * @property integer $documentos
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
 * @property Documento[] $documentos0
 * @property Cliente $cliente
 * @property Contato $contato0
 * @property Escopo $escopo
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
            [['id', 'cliente_id', 'contato_id', 'escopo_id'], 'required'],
            [['id', 'cliente_id', 'contato_id', 'escopo_id', 'documentos', 'rev_proposta', 'qtd_hh', 'qtd_dias', 'qtd_km'], 'integer'],
            [['data_proposta', 'data_entrega', 'criado', 'modificado'], 'safe'],
            [['vl_hh', 'total_horas', 'vl_km', 'total_km', 'valor_proposta', 'valor_consumido', 'valor_saldo'], 'number'],
            [['descricao'], 'string', 'max' => 500],
            [['codigo', 'contato', 'cliente_fatura'], 'string', 'max' => 12],
            [['site', 'planta', 'site_fatura'], 'string', 'max' => 10],
            [['municipio', 'setor', 'status', 'pendencia', 'municipio_fatura'], 'string', 'max' => 20],
            [['uf', 'uf_fatura'], 'string', 'max' => 2],
            [['cnpj', 'cnpj_fatura'], 'string', 'max' => 18],
            [['tratamento'], 'string', 'max' => 7],
            [['fone_contato'], 'string', 'max' => 13],
            [['celular'], 'string', 'max' => 14],
            [['email'], 'string', 'max' => 30],
            [['proposta'], 'string', 'max' => 50],
            [['comentarios'], 'string', 'max' => 80],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['cliente_id' => 'id']],
            [['contato_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contato::className(), 'targetAttribute' => ['contato_id' => 'usuario_id']],
            [['escopo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Escopo::className(), 'targetAttribute' => ['escopo_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cliente_id' => 'Cliente ID',
            'contato_id' => 'Contato ID',
            'escopo_id' => 'Escopo ID',
            'descricao' => 'Descricao',
            'codigo' => 'Codigo',
            'site' => 'Site',
            'planta' => 'Planta',
            'municipio' => 'Municipio',
            'uf' => 'Uf',
            'cnpj' => 'Cnpj',
            'tratamento' => 'Tratamento',
            'contato' => 'Contato',
            'setor' => 'Setor',
            'fone_contato' => 'Fone Contato',
            'celular' => 'Celular',
            'email' => 'Email',
            'documentos' => 'Documentos',
            'proposta' => 'Proposta',
            'rev_proposta' => 'Rev Proposta',
            'data_proposta' => 'Data Proposta',
            'qtd_hh' => 'Qtd Hh',
            'vl_hh' => 'Vl Hh',
            'total_horas' => 'Total Horas',
            'qtd_dias' => 'Qtd Dias',
            'qtd_km' => 'Qtd Km',
            'vl_km' => 'Vl Km',
            'total_km' => 'Total Km',
            'valor_proposta' => 'Valor Proposta',
            'valor_consumido' => 'Valor Consumido',
            'valor_saldo' => 'Valor Saldo',
            'status' => 'Status',
            'pendencia' => 'Pendencia',
            'comentarios' => 'Comentarios',
            'data_entrega' => 'Data Entrega',
            'cliente_fatura' => 'Cliente Fatura',
            'site_fatura' => 'Site Fatura',
            'municipio_fatura' => 'Municipio Fatura',
            'uf_fatura' => 'Uf Fatura',
            'cnpj_fatura' => 'Cnpj Fatura',
            'criado' => 'Criado',
            'modificado' => 'Modificado',
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
    public function getDocumentos0()
    {
        return $this->hasMany(Documento::className(), ['projeto_id' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEscopo()
    {
        return $this->hasOne(Escopo::className(), ['id' => 'escopo_id']);
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "executante".
 *
 * @property integer $usuario_id
 * @property string $cidade
 * @property string $uf
 * @property string $cpf
 * @property string $telefone
 * @property string $celular
 * @property string $criado
 * @property string $modificado
 *
 * @property Atividade[] $atividades
 */
class Executante extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'executante';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usuario_id'], 'required'],
            [['usuario_id', 'is_prestador', 'cargo'], 'integer'],
            [['criado', 'modificado'], 'safe'],
            [['cpf','conta_tipo'], 'string', 'max' => 45],
            [['cnpj'], 'string', 'max' => 18],
            [['cidade', 'endereco', 'cnpj', 'nome_empresa', 'endereco_empresa', 'insc_municipal', 'bairro_empresa', 'cep_empresa', 'uf_empresa', 'cidade_empresa', 'bairro', 'cep', 'endereco_empresa','banco','banco_numero','agencia','conta_corrente'], 'string', 'max' => 255],
            [['uf'], 'string', 'max' => 2],
            [['telefone', 'celular'], 'string', 'max' => 15],
            [['vl_hh_tp', 'vl_hh_ej', 'vl_hh_ep', 'vl_hh_es', 'vl_hh_ee', 'vl_km', 'qtd_km_dia'], 'number'],
           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usuario_id' => 'Nome',           
            'cidade' => 'Cidade',
            'uf' => 'UF',
            'cpf' => 'Cpf',
            'telefone' => 'Telefone',
            'celular' => 'Celular',
            'criado' => 'Criado',
            'modificado' => 'Modificado',
            'endereco' => 'Endereço',
            'vl_hh_tp' => 'Valor Horas (TP)',
            'vl_hh_ej' => 'Valor Horas (EJ)',
            'vl_hh_ep' => 'Valor Horas (EP)',
            'vl_hh_es' => 'Valor Horas (ES)',
            'vl_hh_ee' => 'Valor Horas (EE)',
            'vl_km' => 'Valor KM',
            'qtd_km_dia' => 'Qtd. Km Dia',
            'tipo_id' => 'Tipos',
            'uf_empresa' => 'UF',
            'nome_empresa' => 'Nome',
            'cidade_empresa' => 'Cidade',
            'bairro_empresa' => 'Bairro',
            'cep_empresa' => 'Cep',
            'endereco_empresa' => 'Endereço',
            'banco' => 'Banco',
            'banco_numero' => 'Num. Banco',
            'agencia' => 'Agência',
            'conta_corrente' => 'Conta Corrente',
            'conta_tipo' => 'Tipo de Conta',
            'is_prestador' => 'Prestador de Serviço'

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAtividades()
    {
        return $this->hasMany(Atividade::className(), ['executante_id' => 'usuario_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'usuario_id']);
    }
   
}

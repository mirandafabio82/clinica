<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "paciente".
 *
 * @property int $id_paciente
 * @property string $nome
 * @property string $telefone
 * @property string $celular
 * @property string $nascimento
 * @property string $rg
 * @property string $cpf
 * @property string $profissao_empresa
 * @property string $cor_pele
 * @property string $indicacao
 * @property string $endereco
 * @property string $nacionalidade
 * @property string $naturalidade
 * @property string $estado_civil
 * @property string $nome_mae
 * @property string $nome_pai
 *
 */
class Paciente extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paciente';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['nascimento'], 'safe'],
            [['nome', 'indicacao', 'nome_mae', 'nome_pai'], 'string', 'max' => 150],
            [['telefone', 'celular', 'rg', 'cpf', 'cor_pele', 'estado_civil'], 'string', 'max' => 15],
            [['profissao_empresa', 'nacionalidade', 'naturalidade'], 'string', 'max' => 50],
            [['endereco'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_paciente' => 'Id Paciente',
            'nome' => 'Nome',
            'telefone' => 'Telefone',
            'celular' => 'Celular',
            'nascimento' => 'Data de nascimento',
            'rg' => 'RG',
            'cpf' => 'CPF',
            'profissao_empresa' => 'Profissao / Empresa',
            'cor_pele' => 'Cor da Pele',
            'indicacao' => 'Indicação',
            'endereco' => 'Endereço',
            'nacionalidade' => 'Nacionalidade',
            'naturalidade' => 'Naturalidade - UF',
            'estado_civil' => 'Estado Civil',
            'nome_mae' => 'Nome da Mae',
            'nome_pai' => 'Nome do Pai',
        ];
    }
}
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
            [['usuario_id'], 'integer'],
            [['criado', 'modificado'], 'safe'],
            [['cpf'], 'string', 'max' => 45],
            [['cnpj'], 'string', 'max' => 18],
            [['cidade', 'endereco', 'cnpj', 'nome_empresa', 'endereco_empresa', 'insc_municipal'], 'string', 'max' => 255],
            [['uf'], 'string', 'max' => 2],
            [['telefone', 'celular'], 'string', 'max' => 15],
           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usuario_id' => 'ID',           
            'cidade' => 'Cidade',
            'uf' => 'UF',
            'cpf' => 'Cpf',
            'telefone' => 'Telefone',
            'celular' => 'Celular',
            'criado' => 'Criado',
            'modificado' => 'Modificado',
            'endereco' => 'Endereço',
            'vl_hh' => 'Valor Horas',
            'vl_km' => 'Valor KM',
            'qtd_km_dia' => 'Qtd. Km Dia'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAtividades()
    {
        return $this->hasMany(Atividade::className(), ['executante_id' => 'usuario_id']);
    }

   
}

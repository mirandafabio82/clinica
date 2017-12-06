<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente".
 *
 * @property integer $id
 * @property string $nome
 * @property string $site
 * @property string $cnpj
 * @property string $cidade
 * @property string $uf
 * @property string $criado
 * @property string $modificado
 *
 * @property Contato[] $contatos
 * @property Projeto[] $projetos
 */
class Cliente extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cliente';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome', 'cnpj'], 'required'],
            [['criado', 'modificado'], 'safe'],
            [['nome', 'cidade'], 'string', 'max' => 255],
            [['site'], 'string', 'max' => 45],
            [['cnpj'], 'string', 'max' => 18],
            [['uf'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'site' => 'Site',
            'cnpj' => 'Cnpj',
            'cidade' => 'Cidade',
            'uf' => 'UF',
            'criado' => 'Criado',
            'modificado' => 'Modificado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContatos()
    {
        return $this->hasMany(Contato::className(), ['cliente_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjetos()
    {
        return $this->hasMany(Projeto::className(), ['cliente_id' => 'id']);
    }
}

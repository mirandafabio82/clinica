<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contato".
 *
 * @property integer $usuario_id
 * @property integer $cliente_id
 * @property string $tratamento
 * @property string $site
 * @property string $contato
 * @property string $setor
 * @property string $telefone
 * @property string $celular
 * @property string $criado
 * @property string $modificado
 *
 * @property Cliente $cliente
 * @property Projeto[] $projetos
 */
class Contato extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contato';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['cliente_id'], 'required'],
            [['cliente_id'], 'integer'],
            [['criado', 'modificado'], 'safe'],
            [['tratamento', 'site', 'contato', 'setor'], 'string', 'max' => 255],
            [['telefone', 'celular'], 'string', 'max' => 15],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['cliente_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usuario_id' => 'Nome',
            'cliente_id' => 'Cliente',
            'tratamento' => 'Tratamento',
            'site' => 'Site',
            'contato' => 'Contato',
            'setor' => 'Setor',
            'telefone' => 'Telefone',
            'celular' => 'Celular',
            'criado' => 'Criado',
            'modificado' => 'Modificado',
        ];
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
    public function getProjetos()
    {
        return $this->hasMany(Projeto::className(), ['contato_id' => 'usuario_id']);
    }
}

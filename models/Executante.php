<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "executante".
 *
 * @property integer $usuario_id
 * @property integer $tipo_executante_id
 * @property string $cidade
 * @property string $uf
 * @property string $cpf
 * @property string $telefone
 * @property string $celular
 * @property string $criado
 * @property string $modificado
 *
 * @property Atividade[] $atividades
 * @property TipoExecutante $tipoExecutante
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
            [['usuario_id', 'tipo_executante_id'], 'required'],
            [['usuario_id', 'tipo_executante_id'], 'integer'],
            [['criado', 'modificado'], 'safe'],
            [['cpf'], 'string', 'max' => 45],
            [['cidade'], 'string', 'max' => 255],
            [['uf'], 'string', 'max' => 2],
            [['telefone', 'celular'], 'string', 'max' => 15],
            [['tipo_executante_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoExecutante::className(), 'targetAttribute' => ['tipo_executante_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usuario_id' => 'ID',
            'tipo_executante_id' => 'Tipo de Executante',
            'cidade' => 'Cidade',
            'uf' => 'UF',
            'cpf' => 'Cpf',
            'telefone' => 'Telefone',
            'celular' => 'Celular',
            'criado' => 'Criado',
            'modificado' => 'Modificado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAtividades()
    {
        return $this->hasMany(Atividade::className(), ['executante_id' => 'usuario_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoExecutante()
    {
        return $this->hasOne(TipoExecutante::className(), ['id' => 'tipo_executante_id']);
    }
}

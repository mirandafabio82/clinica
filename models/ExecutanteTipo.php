<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "executante_tipo".
 *
 * @property integer $executante_id
 * @property integer $tipo_id
 *
 * @property Executante $executante
 * @property TipoExecutante $tipo
 */
class ExecutanteTipo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'executante_tipo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['executante_id', 'tipo_id'], 'required'],
            [['executante_id', 'tipo_id'], 'integer'],
            [['executante_id'], 'exist', 'skipOnError' => true, 'targetClass' => Executante::className(), 'targetAttribute' => ['executante_id' => 'usuario_id']],
            [['tipo_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoExecutante::className(), 'targetAttribute' => ['tipo_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'executante_id' => 'Executante ID',
            'tipo_id' => 'Tipo ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExecutante()
    {
        return $this->hasOne(Executante::className(), ['usuario_id' => 'executante_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipo()
    {
        return $this->hasOne(TipoExecutante::className(), ['id' => 'tipo_id']);
    }
}

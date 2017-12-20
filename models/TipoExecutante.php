<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_executante".
 *
 * @property integer $id
 * @property string $cargo
 * @property string $valor_hora
 *
 * @property Executante[] $executantes
 */
class Tipoexecutante extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_executante';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['cargo', 'valor_hora', 'valor_pago', 'codigo'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cargo' => 'Cargo',
            'valor_hora' => 'Valor Hora',
            'codigo' => 'Código'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExecutantes()
    {
        return $this->hasMany(Executante::className(), ['tipo_executante_id' => 'id']);
    }
}

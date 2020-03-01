<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tratamento_realizado".
 *
 * @property int $id_tratamento
 * @property int $id_agendamento
 * @property string $dente
 * @property string $tratamento_realizado
 *
 * @property Agendamento $agendamento
 */
class TratamentoRealizado extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tratamento_realizado';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_agendamento'], 'required'],
            [['id_agendamento'], 'integer'],
            [['dente'], 'string', 'max' => 50],
            [['tratamento_realizado'], 'string', 'max' => 500],
            [['id_agendamento'], 'exist', 'skipOnError' => true, 'targetClass' => Agendamento::className(), 'targetAttribute' => ['id_agendamento' => 'id_agendamento']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tratamento' => 'Id Tratamento',
            'id_agendamento' => 'Id Agendamento',
            'dente' => 'Dente',
            'tratamento_realizado' => 'Tratamento Realizado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgendamento()
    {
        return $this->hasOne(Agendamento::className(), ['id_agendamento' => 'id_agendamento']);
    }
}

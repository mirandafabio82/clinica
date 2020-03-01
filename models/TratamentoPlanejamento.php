<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tratamento_planejamento".
 *
 * @property int $id_tratamento_planejamento
 * @property int $id_paciente
 * @property string $primeira_opcao
 * @property string $segunda_opcao
 *
 * @property Paciente $paciente
 */
class TratamentoPlanejamento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tratamento_planejamento';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_paciente', 'primeira_opcao'], 'required'],
            [['id_paciente'], 'integer'],
            [['primeira_opcao', 'segunda_opcao'], 'string', 'max' => 255],
            [['id_paciente'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::className(), 'targetAttribute' => ['id_paciente' => 'id_paciente']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tratamento_planejamento' => 'Id Tratamento Planejamento',
            'id_paciente' => 'Id Paciente',
            'primeira_opcao' => 'Primeira Opcao',
            'segunda_opcao' => 'Segunda Opcao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaciente()
    {
        return $this->hasOne(Paciente::className(), ['id_paciente' => 'id_paciente']);
    }
}

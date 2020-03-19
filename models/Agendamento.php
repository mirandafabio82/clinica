<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agendamento".
 *
 * @property int $id_agendamento
 * @property string $nome
 * @property string $cpf
 * @property string $horario
 * @property string $horario_final
 * @property string $tipo_atendimento
 * @property string $plano_particular
 * @property int $id_status
 * @property string $descricao
 * @property string $id_responsavel
 *
 * @property AgendaStatus $status
 * @property TratamentoRealizado[] $tratamentoRealizados
 */
class Agendamento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agendamento';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome', 'horario', 'id_responsavel'], 'required'],
            [['horario', 'horario_final'], 'safe'],
            [['id_status'], 'integer'],
            [['nome', 'id_responsavel'], 'string', 'max' => 55],
            [['cpf'], 'string', 'max' => 15],
            [['tipo_atendimento', 'plano_particular'], 'string', 'max' => 50],
            [['descricao'], 'string', 'max' => 255],
            [['id_status'], 'exist', 'skipOnError' => true, 'targetClass' => AgendaStatus::className(), 'targetAttribute' => ['id_status' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_agendamento' => 'Id Agendamento',
            'nome' => 'Nome',
            'cpf' => 'Cpf',
            'horario' => 'Horario',
            'tipo_atendimento' => 'Tipo Atendimento',
            'plano_particular' => 'Plano Particular',
            'id_status' => 'Id Status',
            'descricao' => 'Descricao',
            'id_responsavel' => 'Id Responsavel',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(AgendaStatus::className(), ['id' => 'id_status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTratamentoRealizados()
    {
        return $this->hasMany(TratamentoRealizado::className(), ['id_agendamento' => 'id_agendamento']);
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tarefa".
 *
 * @property int $id
 * @property int $projeto_id
 * @property int $executante_id
 * @property string $atividade_id
 * @property string $data
 * @property string $horas_as
 * @property string $horas_executada
 * @property string $horas_acumulada
 * @property string $horas_saldo
 * @property string $descricao
 * @property string $criado
 * @property string $modificado
 */
class Tarefa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tarefa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'projeto_id', 'executante_id', 'atividade_id'], 'integer'],
            [['descricao'], 'string'],
            [['data', 'criado', 'modificado'], 'safe'],
            [['horas_as', 'horas_executada', 'horas_acumulada', 'horas_saldo'], 'number'],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'projeto_id' => 'Projeto',
            'executante_id' => 'Executante',
            'atividade_id' => 'Atividade',
            'data' => 'Data',
            'horas_as' => 'Horas AS',
            'horas_executada' => 'Horas Executadas',
            'horas_acumulada' => 'Horas Acumuladas',
            'horas_saldo' => 'Horas Saldo',
            'descricao' => 'Descricao',
            'criado' => 'Criado',
            'modificado' => 'Modificado',
        ];
    }
}

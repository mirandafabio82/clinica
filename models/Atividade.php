<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "atividade".
 *
 * @property integer $id
 * @property integer $projeto_id
 * @property integer $executante_id
 * @property string $local
 * @property string $acao
 * @property string $data
 * @property string $comentario
 * @property string $status
 * @property string $solicitante
 * @property string $hr_inicio
 * @property string $hr_final
 * @property string $hr100_inicio
 * @property string $hr100_final
 * @property string $horas
 * @property string $valor_hh
 * @property string $valor_km
 * @property string $valor_total
 * @property string $criado
 * @property string $modificado
 *
 * @property Executante $executante
 * @property Projeto $projeto
 */
class Atividade extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'atividade';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projeto_id', 'executante_id'], 'required'],
            [['projeto_id', 'executante_id'], 'integer'],
            [['data', 'hr_inicio', 'hr_final', 'hr100_inicio', 'hr100_final', 'criado', 'modificado'], 'safe'],
            [['horas', 'valor_hh', 'valor_km', 'valor_total'], 'number'],
            [['local'], 'string', 'max' => 5],
            [['acao'], 'string', 'max' => 80],
            [['comentario'], 'string', 'max' => 160],
            [['status'], 'string', 'max' => 10],
            [['solicitante'], 'string', 'max' => 12],
            [['executante_id'], 'exist', 'skipOnError' => true, 'targetClass' => Executante::className(), 'targetAttribute' => ['executante_id' => 'usuario_id']],
            [['projeto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Projeto::className(), 'targetAttribute' => ['projeto_id' => 'id']],
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
            'local' => 'Local',
            'acao' => 'Acao',
            'data' => 'Data',
            'comentario' => 'Comentario',
            'status' => 'Status',
            'solicitante' => 'Solicitante',
            'hr_inicio' => 'Hr Inicio',
            'hr_final' => 'Hr Final',
            'hr100_inicio' => 'Hr100 Inicio',
            'hr100_final' => 'Hr100 Final',
            'horas' => 'Horas',
            'valor_hh' => 'Valor Hh',
            'valor_km' => 'Valor Km',
            'valor_total' => 'Valor Total',
            'criado' => 'Criado',
            'modificado' => 'Modificado',
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
    public function getProjeto()
    {
        return $this->hasOne(Projeto::className(), ['id' => 'projeto_id']);
    }
}

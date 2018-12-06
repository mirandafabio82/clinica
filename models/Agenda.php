<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agenda".
 *
 * @property int $id
 * @property int $projeto_id
 * @property string $hr_inicio
 * @property string $hr_final
 * @property string $local
 * @property int $responsavel
 * @property int $contato
 * @property string $assunto
 * @property int $status
 * @property string $descricao
 * @property string $prazo
 * @property string $pendente
 *
 * @property AgendaStatus $status0
 */
class Agenda extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agenda';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projeto', 'responsavel', 'contato'], 'string', 'max' => 255],
            [['status'], 'integer'],
            [['hr_inicio', 'hr_final', 'status'], 'required'],
            [['hr_inicio', 'hr_final', 'prazo'], 'safe'],
            [['descricao', 'pendente'], 'string'],
            [['local'], 'string', 'max' => 80],
            [['assunto'], 'string', 'max' => 80],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => AgendaStatus::className(), 'targetAttribute' => ['status' => 'id']],
            [['cor'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'projeto' => 'Projeto',
            'hr_inicio' => 'Hr Inicio',
            'hr_final' => 'Hr Final',
            'local' => 'Local',
            'responsavel' => 'Responsável',
            'contato' => 'Contato',
            'assunto' => 'Assunto',
            'status' => 'Status',
            'descricao' => 'Descrição',
            'prazo' => 'Prazo',
            'pendente' => 'Pendente',
            'cor' => 'Cor'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(AgendaStatus::className(), ['id' => 'status']);
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agenda".
 *
 * @property integer $id
 * @property integer $projeto_id
 * @property string $data
 * @property string $local
 * @property string $quem
 * @property string $assunto
 * @property string $hr_inicio
 * @property string $hr_final
 * @property string $status
 *
 * @property Projeto $projeto
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
            [['projeto_id'], 'required'],
            [['projeto_id'], 'integer'],
            [['data', 'hr_inicio', 'hr_final'], 'safe'],
            [['local', 'quem', 'status'], 'string', 'max' => 15],
            [['assunto'], 'string', 'max' => 80],
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
            'projeto_id' => 'Projeto ID',
            'data' => 'Data',
            'local' => 'Local',
            'quem' => 'Quem',
            'assunto' => 'Assunto',
            'hr_inicio' => 'Hr Inicio',
            'hr_final' => 'Hr Final',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjeto()
    {
        return $this->hasOne(Projeto::className(), ['id' => 'projeto_id']);
    }
}

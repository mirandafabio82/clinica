<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "executante_disciplina".
 *
 * @property int $id
 * @property int $executante_id
 * @property int $disciplina_id
 */
class ExecutanteDisciplina extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'executante_disciplina';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['executante_id', 'disciplina_id'], 'required'],
            [['executante_id', 'disciplina_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'executante_id' => 'Executante ID',
            'disciplina_id' => 'Disciplina ID',
        ];
    }
}

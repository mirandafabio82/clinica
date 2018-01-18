<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "projeto_executante".
 *
 * @property int $id
 * @property int $projeto_id
 * @property int $executante_id
 */
class ProjetoExecutante extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projeto_executante';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projeto_id', 'executante_id'], 'integer'],
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
            'executante_id' => 'Executante ID',
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "atividademodelo".
 *
 * @property integer $id
 * @property string $nome
 * @property integer $isPrioritaria
 */
class Atividademodelo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'atividademodelo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isPrioritaria', 'isEntregavel', 'escopopadrao_id'], 'integer'],
            [['nome'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'isPrioritaria' => 'Is Prioritaria',
            'escopopadrao_id' => 'Escopo',
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "escopopadrao".
 *
 * @property integer $id
 * @property string $nome
 */
class Escopopadrao extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'escopopadrao';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome'], 'string', 'max' => 45],
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
        ];
    }
}

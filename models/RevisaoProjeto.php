<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "revisao_projeto".
 *
 * @property int $id
 * @property int $projeto_id
 * @property string $data
 * @property string $descricao
 * @property string $por
 */
class RevisaoProjeto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'revisao_projeto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projeto_id'], 'required'],
            [['projeto_id'], 'integer'],
            [['data'], 'safe'],
            [['descricao'], 'string', 'max' => 255],
            [['por'], 'string', 'max' => 45],
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
            'descricao' => 'Descricao',
            'por' => 'Por',
        ];
    }
}

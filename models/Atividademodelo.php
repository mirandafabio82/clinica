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
            [['isPrioritaria', 'isEntregavel', 'escopopadrao_id', 'disciplina_id', 'ordem'], 'integer'],
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
            'isPrioritaria' => 'Prioritaria',
            'isEntregavel' => 'Entregável',
            'escopopadrao_id' => 'Escopo',
            'disciplina_id' => 'Disciplina',
            'ordem' => 'Ordem',
        ];
    }
}

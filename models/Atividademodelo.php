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
            [['isPrioritaria', 'isEntregavel', 'disciplina_id', 'ordem', 'is_conceitual', 'is_basico', 'is_detalhamento', 'is_configuracao', 'is_servico'], 'integer'],
            [['nome', 'codigo'], 'string', 'max' => 255],
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
            'disciplina_id' => 'Disciplina',
            'ordem' => 'Ordem',
            'is_conceitual' => 'Conceitual',
            'is_basico' => 'Básico',
            'is_detalhamento' => 'Detalhamento',
            'is_configuracao' => 'Configuração',
            'is_servico' => 'Serviço',
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "projeto_nome".
 *
 * @property integer $id
 * @property string $nome
 *
 * @property Projeto[] $projetos
 */
class ProjetoNome extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projeto_nome';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjetos()
    {
        return $this->hasMany(Projeto::className(), ['projeto_nome_id' => 'id']);
    }
}

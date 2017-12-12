<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "escopo".
 *
 * @property integer $id
 * @property string $nome
 * @property integer $item
 * @property string $descricao
 * @property integer $horas
 * @property integer $executado
 * @property integer $interno
 * @property string $criado
 * @property string $modificado
 *
 * @property Projeto[] $projetos
 */
class Escopo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'escopo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item', 'horas', 'executado', 'interno', 'projeto_id', 'atividademodelo_id'], 'integer'],
            [['criado', 'modificado'], 'safe'],
            [['nome'], 'string', 'max' => 255],
            [['descricao'], 'string', 'max' => 160],
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
            'item' => 'Item',
            'descricao' => 'Descricao',
            'horas' => 'Horas',
            'executado' => 'Executado',
            'interno' => 'Interno',
            'criado' => 'Criado',
            'modificado' => 'Modificado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjetos()
    {
        return $this->hasMany(Projeto::className(), ['escopo_id' => 'id']);
    }
}

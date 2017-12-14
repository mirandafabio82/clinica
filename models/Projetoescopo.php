<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "projetoescopo".
 *
 * @property integer $projeto_id
 * @property integer $escopo_id
 *
 * @property Escopopadrao $escopo
 * @property Projeto $projeto
 */
class Projetoescopo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projetoescopo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projeto_id', 'escopo_id'], 'required'],
            [['projeto_id', 'escopo_id'], 'integer'],
            [['escopo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Escopopadrao::className(), 'targetAttribute' => ['escopo_id' => 'id']],
            [['projeto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Projeto::className(), 'targetAttribute' => ['projeto_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'projeto_id' => 'Projeto ID',
            'escopo_id' => 'Escopo ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEscopo()
    {
        return $this->hasOne(Escopopadrao::className(), ['id' => 'escopo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjeto()
    {
        return $this->hasOne(Projeto::className(), ['id' => 'projeto_id']);
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "projeto_contato".
 *
 * @property integer $id
 * @property integer $projeto_id
 * @property integer $contato_id
 *
 * @property Contato $contato
 * @property Projeto $projeto
 */
class ProjetoContato extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projeto_contato';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'projeto_id', 'contato_id'], 'required'],
            [['id', 'projeto_id', 'contato_id'], 'integer'],
            [['contato_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contato::className(), 'targetAttribute' => ['contato_id' => 'usuario_id']],
            [['projeto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Projeto::className(), 'targetAttribute' => ['projeto_id' => 'id']],
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
            'contato_id' => 'Contato ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContato()
    {
        return $this->hasOne(Contato::className(), ['usuario_id' => 'contato_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjeto()
    {
        return $this->hasOne(Projeto::className(), ['id' => 'projeto_id']);
    }
}

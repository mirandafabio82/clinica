<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "documento".
 *
 * @property integer $id
 * @property integer $projeto_id
 * @property integer $cliente_id
 * @property string $nome
 * @property integer $revisao
 * @property string $path
 * @property string $data
 * @property string $tipo
 * @property string $criado
 * @property string $modificado
 *
 * @property Projeto $projeto
 */
class Documento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documento';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['projeto_id'], 'required'],
            [['projeto_id', 'revisao', 'is_global'], 'integer'],
            [['data', 'criado', 'modificado'], 'safe'],
            [['nome', 'tipo'], 'string', 'max' => 255],
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
            'projeto_id' => 'Projeto',            
            'nome' => 'Nome',
            'revisao' => 'Revisao',
            'data' => 'Data',
            'tipo' => 'Tipo',
            'criado' => 'Criado',
            'modificado' => 'Modificado',
            'path' => 'Arquivo',
            'is_global' => 'Visível para executantes'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjeto()
    {
        return $this->hasOne(Projeto::className(), ['id' => 'projeto_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjeto_executante()
    {
        return $this->hasOne(ProjetoExecutante::className(), ['projeto_id' => 'projeto.id']);
    }
}

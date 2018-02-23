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
 * @property integer $qtd
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
            [['item', 'horas_tp',  'exe_tp_id', 'exe_ej_id', 'exe_ep_id', 'exe_ee_id', 'executado_tp','executado_ej','executado_ep','executado_es','executado_ee', 'qtd', 'projeto_id', 'atividademodelo_id', 'status'], 'integer'],
            [['criado', 'modificado'], 'safe'],
            [['nome', 'for', 'horas_tp', 'horas_ej', 'horas_ep', 'horas_es', 'horas_ee'], 'string', 'max' => 255],
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
            'horas_tp' => 'Horas TP',
            'horas_ej' => 'Horas EJ',
            'horas_ep' => 'Horas EP',
            'horas_es' => 'Horas ES',
            'horas_ee' => 'Horas EE',
            'executado_tp' => 'Executado TP',
            'executado_ej' => 'Executado EJ',
            'executado_ep' => 'Executado EP',
            'executado_es' => 'Executado ES',
            'executado_ee' => 'Executado EE',
            'qtd' => 'Qtd.',
            'criado' => 'Criado',
            'modificado' => 'Modificado',
            'atividademodelo_id' => 'Atividade',
            'exe_tp_id' => 'Técnico',
            'exe_ej_id' => 'Eng. Junior',
            'exe_ep_id' => 'Eng. Pleno',
            'exe_es_id' => 'Eng. Senior',
            'exe_ee_id' => 'Eng. Especialista',
            'for' => 'FOR.',
            'status' => 'status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjetos()
    {
        return $this->hasMany(Projeto::className(), ['escopo_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public function getProjeto()
    {
        return $this->hasOne(Projeto::className(), ['id' => 'projeto_id']);
    }
}

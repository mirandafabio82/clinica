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
            [['item', 'horas_tp',  'exe_tp_id', 'exe_ej_id', 'exe_ep_id', 'exe_es_id', 'exe_ee_id', 'qtd', 'projeto_id', 'atividademodelo_id', 'status',  'horas_tp', 'horas_ej', 'horas_ep', 'horas_es', 'horas_ee'], 'integer'],
            [['criado', 'modificado'], 'safe'],
            [['nome', 'for'], 'string', 'max' => 255],
            [['descricao'], 'string', 'max' => 160],
            [['horas_acumulada', 'horas_bm', 'horas_saldo','horas_tp_bm', 'horas_ej_bm', 'horas_ep_bm', 'horas_es_bm', 'horas_ee_bm', 'executado', 'executado_tp','executado_ej','executado_ep','executado_es','executado_ee', 
            'adiantadas_tp','adiantadas_ej','adiantadas_ep','adiantadas_es','adiantadas_ee'], 'number'],
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
            'executado_tp' => 'TP',
            'executado_ej' => 'EJ',
            'executado_ep' => 'EP',
            'executado_es' => 'ES',
            'executado_ee' => 'EE',
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
            'adiantadas_tp' => 'TP',
            'adiantadas_ej' => 'EJ',
            'adiantadas_ep' => 'EP',
            'adiantadas_es' => 'ES',
            'adiantadas_ee' => 'EE',
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

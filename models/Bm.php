<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bm".
 *
 * @property int $id
 * @property int $projeto_id
 * @property string $contrato
 * @property string $objeto
 * @property string $contratada
 * @property string $cnpj
 * @property string $contato
 * @property string $numero_bm
 * @property string $data
 * @property string $de
 * @property string $para
 * @property string $descricao
 */
class Bm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projeto_id', 'qtd_dias', 'numero_bm', 'num_bm_proj'], 'integer'],
            [['data', 'de', 'para'], 'safe'],
            [['descricao'], 'string'],
            [['contrato', 'objeto', 'contratada', 'cnpj', 'contato'], 'string', 'max' => 255],
            [['acumulado', 'saldo', 'km', 'executado_es', 'executado_ep', 'executado_ej', 'executado_tp', 'executado_ee'], 'number'],
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
            'contrato' => 'Contrato',
            'objeto' => 'Objeto',
            'contratada' => 'Contratada',
            'cnpj' => 'Cnpj',
            'contato' => 'Contato',
            'numero_bm' => 'Número',
            'data' => 'Data',
            'de' => 'Período de',
            'para' => 'a',
            'descricao' => 'Descricao',
            'executado_ee' => 'EE',
            'executado_es' => 'ES',
            'executado_ep' => 'EP',
            'executado_ej' => 'EJ',
            'executado_tp' => 'TP',
            'qtd_dias' => 'Dias'
        ];
    }

    public function getProjeto()
    {
        return $this->hasOne(Projeto::className(), ['id' => 'projeto_id']);
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "config".
 *
 * @property string $vl_hh
 * @property string $vl_km
 * @property integer $qtd_km_dia
 * @property string $pasta
 * @property integer $ultimo_bm
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vl_hh', 'vl_km'], 'number'],
            [['qtd_km_dia', 'ultimo_bm'], 'integer'],
            [['pasta'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vl_hh' => 'Vl Hh',
            'vl_km' => 'Vl Km',
            'qtd_km_dia' => 'Qtd Km Dia',
            'pasta' => 'Pasta',
            'ultimo_bm' => 'Ultimo Bm',
        ];
    }
}

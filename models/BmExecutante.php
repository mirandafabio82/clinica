<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bm_executante".
 *
 * @property int $id
 * @property int $bm_id
 * @property int $executante_id
 * @property string $previsao_pgt
 * @property string $data_pgt
 * @property int $pago
 */
class BmExecutante extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bm_executante';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bm_id', 'executante_id'], 'required'],
            [['bm_id', 'executante_id', 'pago'], 'integer'],
            [['previsao_pgt', 'data_pgt'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bm_id' => 'Bm ID',
            'executante_id' => 'Executante ID',
            'previsao_pgt' => 'Previsao Pgt',
            'data_pgt' => 'Data Pgt',
            'pago' => 'Pago',
        ];
    }
}

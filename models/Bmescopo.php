<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bm_escopo".
 *
 * @property int $id
 * @property int $bm_id
 * @property int $escopo_id
 * @property string $horas
 */
class Bmescopo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bm_escopo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bm_id', 'escopo_id'], 'required'],
            [['bm_id', 'escopo_id'], 'integer'],
            [['horas_tp', 'horas_ej', 'horas_ep', 'horas_es', 'horas_ee'], 'number'],
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
            'escopo_id' => 'Escopo ID',
            // 'horas_tp', 
            // 'horas_ej', 
            // 'horas_ep', 
            // 'horas_es', 
            // 'horas_ee' => 'Horas',
        ];
    }
}

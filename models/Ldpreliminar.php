<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ld_preliminar".
 *
 * @property int $id
 * @property string $hcn
 * @property string $cliente
 * @property string $titulo
 */
class Ldpreliminar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ld_preliminar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hcn', 'cliente', 'titulo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Item',
            'hcn' => 'Nº Hcn',
            'cliente' => 'Nº Cliente',
            'titulo' => 'Titulo',
        ];
    }
}

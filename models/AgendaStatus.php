<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agenda_status".
 *
 * @property int $id
 * @property string $status
 * @property string $cor
 */
class AgendaStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agenda_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'cor'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'cor' => 'Cor',
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "documento".
 *
 * @property int $id_documento
 * @property int $id_tipo_documento
 * @property int $id_paciente
 * @property string $observacao
 * @property string $path
 * @property string $data
 *
 * @property Paciente $paciente
 * @property TipoDocumento $tipoDocumento
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
            [['id_tipo_documento', 'id_paciente'], 'integer'],
            [['id_paciente'], 'required'],
            [['data'], 'safe'],
            [['observacao', 'path'], 'string', 'max' => 255],
            [['id_paciente'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::className(), 'targetAttribute' => ['id_paciente' => 'id_paciente']],
            [['id_tipo_documento'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDocumento::className(), 'targetAttribute' => ['id_tipo_documento' => 'id_tipo_documento']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_documento' => 'Id Documento',
            'id_tipo_documento' => 'Tipo Documento',
            'id_paciente' => 'Paciente',
            'observacao' => 'Observacao',
            'path' => 'Caminho',
            'data' => 'Data',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaciente()
    {
        return $this->hasOne(Paciente::className(), ['id_paciente' => 'id_paciente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoDocumento()
    {
        return $this->hasOne(TipoDocumento::className(), ['id_tipo_documento' => 'id_tipo_documento']);
    }
}

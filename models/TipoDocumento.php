<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_documento".
 *
 * @property int $id_tipo_documento
 * @property string $nome
 *
 * @property Documento[] $documentos
 */
class TipoDocumento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_documento';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['nome'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_documento' => 'Id Tipo Documento',
            'nome' => 'Nome',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentos()
    {
        return $this->hasMany(Documento::className(), ['id_tipo_documento' => 'id_tipo_documento']);
    }
}

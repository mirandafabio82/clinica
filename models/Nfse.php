<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "nfse".
 *
 * @property int $id
 * @property string $processo
 * @property string $nota_fiscal
 * @property string $serie
 * @property string $data_emissao
 * @property string $data_entrega
 * @property string $status
 * @property string $pendencia
 * @property string $nf_devolvida
 * @property string $comentario_devolucao
 * @property string $data_pagamento
 * @property string $usuario_pendencia
 * @property string $cnpj_emitente
 */
class Nfse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nfse';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data_emissao', 'data_entrega', 'data_pagamento'], 'safe'],
            [['pendencia', 'comentario_devolucao'], 'string'],
            [['processo', 'nota_fiscal', 'nf_devolvida', 'usuario_pendencia', 'cnpj_emitente', 'serie'], 'string', 'max' => 45],
            [['status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'processo' => 'Processo',
            'nota_fiscal' => 'Nota Fiscal',
            'data_emissao' => 'Data Emissao',
            'data_entrega' => 'Data Entrega',
            'status' => 'Status',
            'pendencia' => 'Pendencia',
            'nf_devolvida' => 'Nf Devolvida',
            'comentario_devolucao' => 'Comentario Devolucao',
            'data_pagamento' => 'Previsão Pagamento',
            'usuario_pendencia' => 'Usuario Pendencia',
            'cnpj_emitente' => 'Cnpj Emitente',
            'serie' => 'Série',
        ];
    }
}

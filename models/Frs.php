<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "frs".
 *
 * @property int $id
 * @property string $contrato
 * @property string $pedido
 * @property string $frs
 * @property string $criador
 * @property string $data_criacao
 * @property string $aprovador
 * @property string $data_aprovacao
 * @property string $cnpj_emitente
 * @property string $valor
 * @property string $nota_fiscal
 * @property string $referencia
 * @property string $texto_breve
 */
class Frs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'frs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data_criacao', 'data_aprovacao'], 'safe'],
            [['valor'], 'number'],
            [['texto_breve'], 'string'],
            [['contrato', 'pedido', 'frs', 'cnpj_emitente', 'nota_fiscal', 'referencia', 'cnpj_braskem'], 'string', 'max' => 45],
            [['criador', 'aprovador'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contrato' => 'Contrato',
            'pedido' => 'Pedido',
            'frs' => 'Frs',
            'bm' => 'BM',
            'criador' => 'Criador',
            'data_criacao' => 'Data Criacao',
            'aprovador' => 'Aprovador',
            'data_aprovacao' => 'Data Aprovacao',
            'cnpj_emitente' => 'CNPJ Emitente',
            'valor' => 'Valor',
            'nota_fiscal' => 'Nota Fiscal',
            'referencia' => 'Referencia',
            'texto_breve' => 'Texto Breve',
            'cnpj_braskem' => 'CNPJ Braskem'
        ];
    }
}

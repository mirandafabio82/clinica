<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pagamento".
 *
 * @property int $id
 * @property string $nota_fiscal
 * @property string $tipo_documento
 * @property string $data_emissao
 * @property string $data_lancamento
 * @property string $data_pagamento
 * @property string $valor_bruto
 * @property string $retencoes
 * @property string $abatimentos
 * @property string $valor_liquido
 * @property string $forma_pagamento
 * @property string $conta
 * @property string $documento_contabil
 * @property string $compensacao
 */
class Pagamento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pagamento';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data_emissao', 'data_lancamento', 'data_pagamento'], 'safe'],
            [['valor_bruto', 'valor_liquido'], 'number'],
            [['nota_fiscal', 'retencoes', 'abatimentos', 'forma_pagamento', 'conta', 'documento_contabil', 'compensacao'], 'string', 'max' => 45],
            [['tipo_documento'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nota_fiscal' => 'Nota Fiscal',
            'tipo_documento' => 'Tipo Documento',
            'data_emissao' => 'Data Emissao',
            'data_lancamento' => 'Data Lancamento',
            'data_pagamento' => 'Data Pagamento',
            'valor_bruto' => 'Valor Bruto',
            'retencoes' => 'Retenção',
            'abatimentos' => 'Abatimentos',
            'valor_liquido' => 'Valor Liquido',
            'forma_pagamento' => 'Forma Pagamento',
            'conta' => 'Conta',
            'documento_contabil' => 'Documento Contabil',
            'compensacao' => 'Compensacao',
        ];
    }
}

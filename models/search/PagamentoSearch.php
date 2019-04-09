<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pagamento;

/**
 * PagamentoSearch represents the model behind the search form of `app\models\Pagamento`.
 */
class PagamentoSearch extends Pagamento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nota_fiscal', 'tipo_documento', 'data_emissao', 'data_lancamento', 'data_pagamento', 'retencoes', 'abatimentos', 'forma_pagamento', 'conta', 'documento_contabil', 'compensacao'], 'safe'],
            [['valor_bruto', 'valor_liquido'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Pagamento::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'data_emissao' => $this->data_emissao,
            'data_lancamento' => $this->data_lancamento,
            'data_pagamento' => $this->data_pagamento,
            'valor_bruto' => $this->valor_bruto,
            'valor_liquido' => $this->valor_liquido,
        ]);

        $query->andFilterWhere(['like', 'nota_fiscal', $this->nota_fiscal])
            ->andFilterWhere(['like', 'tipo_documento', $this->tipo_documento])
            ->andFilterWhere(['like', 'retencoes', $this->retencoes])
            ->andFilterWhere(['like', 'abatimentos', $this->abatimentos])
            ->andFilterWhere(['like', 'forma_pagamento', $this->forma_pagamento])
            ->andFilterWhere(['like', 'conta', $this->conta])
            ->andFilterWhere(['like', 'documento_contabil', $this->documento_contabil])
            ->andFilterWhere(['like', 'compensacao', $this->compensacao]);

        return $dataProvider;
    }
}

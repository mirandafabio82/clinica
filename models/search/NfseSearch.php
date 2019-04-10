<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Nfse;

/**
 * NfseSearch represents the model behind the search form of `app\models\Nfse`.
 */
class NfseSearch extends Nfse
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['processo', 'nota_fiscal', 'data_emissao', 'data_entrega', 'status', 'pendencia', 'nf_devolvida', 'comentario_devolucao', 'data_pagamento', 'usuario_pendencia', 'cnpj_emitente', 'serie'], 'safe'],
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
        $query = Nfse::find();

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
            'data_entrega' => $this->data_entrega,
            'data_pagamento' => $this->data_pagamento,
        ]);

        $query->andFilterWhere(['like', 'processo', $this->processo])
            ->andFilterWhere(['like', 'nota_fiscal', $this->nota_fiscal])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'pendencia', $this->pendencia])
            ->andFilterWhere(['like', 'nf_devolvida', $this->nf_devolvida])
            ->andFilterWhere(['like', 'comentario_devolucao', $this->comentario_devolucao])
            ->andFilterWhere(['like', 'usuario_pendencia', $this->usuario_pendencia])
            ->andFilterWhere(['like', 'cnpj_emitente', $this->cnpj_emitente])
            ->andFilterWhere(['like', 'serie', $this->serie]);

        return $dataProvider;
    }
}

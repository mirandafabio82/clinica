<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bm;

/**
 * BmSearch represents the model behind the search form of `app\models\Bm`.
 */
class BmSearch extends Bm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'projeto_id'], 'integer'],
            [['contrato', 'objeto', 'contratada', 'cnpj', 'contato', 'numero_bm', 'data', 'de', 'para', 'descricao'], 'safe'],
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
        $query = Bm::find();

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
            'projeto_id' => $this->projeto_id,
            'data' => $this->data,
            'de' => $this->de,
            'para' => $this->para,
        ]);

        $query->andFilterWhere(['like', 'contrato', $this->contrato])
            ->andFilterWhere(['like', 'objeto', $this->objeto])
            ->andFilterWhere(['like', 'contratada', $this->contratada])
            ->andFilterWhere(['like', 'cnpj', $this->cnpj])
            ->andFilterWhere(['like', 'contato', $this->contato])
            ->andFilterWhere(['like', 'numero_bm', $this->numero_bm])
            ->andFilterWhere(['like', 'descricao', $this->descricao]);

        return $dataProvider;
    }
}

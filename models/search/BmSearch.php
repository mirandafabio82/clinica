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
    public $projeto;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'projeto_id'], 'integer'],
            [['contrato', 'objeto', 'contratada', 'cnpj', 'contato', 'numero_bm', 'data', 'de', 'para', 'descricao', 'projeto'], 'safe'],
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
        $query->joinWith(['projeto']);
        // add conditions that should always apply here
        $dataProvider->sort->attributes['projeto'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['projeto.nome' => SORT_ASC],
            'desc' => ['projeto.nome' => SORT_DESC],
        ];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
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
            ->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'projeto.nome', $this->projeto]);

        return $dataProvider;
    }
}

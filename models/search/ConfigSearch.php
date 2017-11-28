<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Config;

/**
 * ConfigSearch represents the model behind the search form about `app\models\Config`.
 */
class ConfigSearch extends Config
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'qtd_km_dia', 'ultimo_bm'], 'integer'],
            [['vl_hh', 'vl_km'], 'number'],
            [['pasta'], 'safe'],
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
        $query = Config::find();

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
            'vl_hh' => $this->vl_hh,
            'vl_km' => $this->vl_km,
            'qtd_km_dia' => $this->qtd_km_dia,
            'ultimo_bm' => $this->ultimo_bm,
        ]);

        $query->andFilterWhere(['like', 'pasta', $this->pasta]);

        return $dataProvider;
    }
}

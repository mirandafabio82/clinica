<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Agenda;

/**
 * AgendaSearch represents the model behind the search form about `app\models\Agenda`.
 */
class AgendaSearch extends Agenda
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'projeto_id'], 'integer'],
            [['data', 'local', 'quem', 'assunto', 'hr_inicio', 'hr_final', 'status'], 'safe'],
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
        $query = Agenda::find();

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
            'hr_inicio' => $this->hr_inicio,
            'hr_final' => $this->hr_final,
        ]);

        $query->andFilterWhere(['like', 'local', $this->local])
            ->andFilterWhere(['like', 'quem', $this->quem])
            ->andFilterWhere(['like', 'assunto', $this->assunto])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}

<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TratamentoRealizado;

/**
 * TratamentoRealizadoSearch represents the model behind the search form of `app\models\TratamentoRealizado`.
 */
class TratamentoRealizadoSearch extends TratamentoRealizado
{
    public $agendamento;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tratamento', 'id_agendamento'], 'integer'],
            [['agendamento'], 'safe'],
            [['dente', 'tratamento_realizado'], 'safe'],
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
        $query = TratamentoRealizado::find();

        $query->joinWith(['agendamento']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->sort->attributes['agendamento'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['agendamento.nome' => SORT_ASC],
            'desc' => ['agendamento.nome' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_tratamento' => $this->id_tratamento,
            'id_agendamento' => $this->id_agendamento,
        ]);

        $query->andFilterWhere(['like', 'dente', $this->dente])
            ->andFilterWhere(['like', 'tratamento_realizado', $this->tratamento_realizado])
            ->andFilterWhere(['like', 'agendamento.nome', $this->agendamento]);

        return $dataProvider;
    }
}

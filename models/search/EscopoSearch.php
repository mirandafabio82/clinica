<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Escopo;

/**
 * EscopoSearch represents the model behind the search form about `app\models\Escopo`.
 */
class EscopoSearch extends Escopo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item', 'horas', 'executado', 'interno'], 'integer'],
            [['nome', 'descricao', 'criado', 'modificado'], 'safe'],
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
        $query = Escopo::find();

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
            'item' => $this->item,
            'horas' => $this->horas,
            'executado' => $this->executado,
            'interno' => $this->interno,
            'criado' => $this->criado,
            'modificado' => $this->modificado,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'descricao', $this->descricao]);

        return $dataProvider;
    }
}

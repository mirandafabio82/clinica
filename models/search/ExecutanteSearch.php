<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Executante;

/**
 * ExecutanteSearch represents the model behind the search form about `app\models\Executante`.
 */
class ExecutanteSearch extends Executante
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usuario_id'], 'integer'],
            [['cidade', 'uf', 'cpf','telefone', 'celular', 'criado', 'modificado'], 'safe'],
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
        $query = Executante::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
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
            'usuario_id' => $this->usuario_id,            
            'criado' => $this->criado,
            'modificado' => $this->modificado,
        ]);

        $query->andFilterWhere(['like', 'cidade', $this->cidade])
            ->andFilterWhere(['like', 'uf', $this->uf])
            ->andFilterWhere(['like', 'cpf', $this->cpf])            
            ->andFilterWhere(['like', 'telefone', $this->telefone])
            ->andFilterWhere(['like', 'celular', $this->celular]);

        return $dataProvider;
    }
}

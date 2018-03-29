<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tarefa;

/**
 * TarefaSearch represents the model behind the search form of `app\models\Tarefa`.
 */
class TarefaSearch extends Tarefa
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'projeto_id', 'executante_id'], 'integer'],
            [['atividade_id', 'data', 'descricao', 'criado', 'modificado'], 'safe'],
            [['horas_as', 'horas_executada', 'horas_acumulada', 'horas_saldo'], 'number'],
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
        $query = Tarefa::find();

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
            'executante_id' => $this->executante_id,
            'data' => $this->data,
            'horas_as' => $this->horas_as,
            'horas_executada' => $this->horas_executada,
            'horas_acumulada' => $this->horas_acumulada,
            'horas_saldo' => $this->horas_saldo,
            'criado' => $this->criado,
            'modificado' => $this->modificado,
        ]);

        $query->andFilterWhere(['like', 'atividade_id', $this->atividade_id])
            ->andFilterWhere(['like', 'descricao', $this->descricao]);

        return $dataProvider;
    }
}

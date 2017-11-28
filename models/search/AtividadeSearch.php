<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Atividade;

/**
 * AtividadeSearch represents the model behind the search form about `app\models\Atividade`.
 */
class AtividadeSearch extends Atividade
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'projeto_id', 'executante_id'], 'integer'],
            [['local', 'acao', 'data', 'comentario', 'status', 'solicitante', 'hr_inicio', 'hr_final', 'hr100_inicio', 'hr100_final', 'criado', 'modificado'], 'safe'],
            [['horas', 'valor_hh', 'valor_km', 'valor_total'], 'number'],
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
        $query = Atividade::find();

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
            'hr_inicio' => $this->hr_inicio,
            'hr_final' => $this->hr_final,
            'hr100_inicio' => $this->hr100_inicio,
            'hr100_final' => $this->hr100_final,
            'horas' => $this->horas,
            'valor_hh' => $this->valor_hh,
            'valor_km' => $this->valor_km,
            'valor_total' => $this->valor_total,
            'criado' => $this->criado,
            'modificado' => $this->modificado,
        ]);

        $query->andFilterWhere(['like', 'local', $this->local])
            ->andFilterWhere(['like', 'acao', $this->acao])
            ->andFilterWhere(['like', 'comentario', $this->comentario])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'solicitante', $this->solicitante]);

        return $dataProvider;
    }
}

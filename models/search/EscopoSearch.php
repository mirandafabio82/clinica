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
    public $projeto;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item', 'horas_tp', 'horas_tp', 'horas_ej', 'horas_ep', 'horas_es', 'horas_ee', 'executado_tp','executado_ej','executado_ep','executado_es','executado_ee', 'qtd', 'projeto_id', 'atividademodelo_id', 'status'], 'integer'],
            [['nome', 'descricao', 'criado', 'modificado', 'projeto'], 'safe'],
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
        $query->joinWith(['projeto']);

        $dataProvider->sort->attributes['projeto'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['projeto.nome' => SORT_ASC],
            'desc' => ['projeto.nome' => SORT_DESC],
        ];

        // add conditions that should always apply here

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
            'item' => $this->item,
            'horas_tp' => $this->horas_tp,
            'horas_ej' => $this->horas_ej,
            'horas_es' => $this->horas_es,
            'horas_ep'=> $this->horas_ep,
            'horas_ee' => $this->horas_ee,
            'executado_tp' => $this->executado_tp,
            'executado_ej' => $this->executado_ej,
            'executado_ep' => $this->executado_ep,
            'executado_es' => $this->executado_es,
            'executado_ee' => $this->executado_ee,
            'qtd' => $this->qtd,
            'criado' => $this->criado,
            'modificado' => $this->modificado,
            'atividademodelo_id' => $this->atividademodelo_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'projeto.nome', $this->projeto]);

        return $dataProvider;
    }
}

<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TratamentoPlanejamento;

/**
 * TratamentoPlanejamentoSearch represents the model behind the search form of `app\models\TratamentoPlanejamento`.
 */
class TratamentoPlanejamentoSearch extends TratamentoPlanejamento
{
    public $nome;
    public $cpf;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tratamento_planejamento', 'id_paciente', 'primeira_opcao'], 'integer'],
            [['segunda_opcao'], 'safe'],
            [['nome'], 'safe'],
            [['cpf'], 'safe'],
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
        $query = TratamentoPlanejamento::find();

        $query->joinWith(['paciente']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['nome'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['paciente.nome' => SORT_ASC],
            'desc' => ['paciente.nome' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['cpf'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['paciente.cpf' => SORT_ASC],
            'desc' => ['paciente.cpf' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_tratamento_planejamento' => $this->id_tratamento_planejamento,
            'id_paciente' => $this->id_paciente,
            'primeira_opcao' => $this->primeira_opcao,
        ]);

        $query->andFilterWhere(['like', 'segunda_opcao', $this->segunda_opcao])
        ->andFilterWhere(['like', 'paciente.nome', $this->nome])
        ->andFilterWhere(['like', 'paciente.cpf', $this->cpf]);

        return $dataProvider;
    }
}

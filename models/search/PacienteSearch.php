<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Paciente;

/**
 * PacienteSearch represents the model behind the search form of `app\models\Paciente`.
 */
class PacienteSearch extends Paciente
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_paciente'], 'integer'],
            [['nome', 'telefone', 'celular', 'nascimento', 'rg', 'cpf', 'profissao_empresa', 'cor_pele', 'indicacao', 'endereco', 'nacionalidade', 'naturalidade', 'estado_civil', 'nome_mae', 'nome_pai'], 'safe'],
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
        $query = Paciente::find();

        $dataProvider->sort->attributes['nome'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['nome' => SORT_ASC],
            'desc' => ['nome' => SORT_DESC],
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
            'id_paciente' => $this->id_paciente,
            'nascimento' => $this->nascimento,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'telefone', $this->telefone])
            ->andFilterWhere(['like', 'celular', $this->celular])
            ->andFilterWhere(['like', 'rg', $this->rg])
            ->andFilterWhere(['like', 'cpf', $this->cpf])
            ->andFilterWhere(['like', 'profissao_empresa', $this->profissao_empresa])
            ->andFilterWhere(['like', 'cor_pele', $this->cor_pele])
            ->andFilterWhere(['like', 'indicacao', $this->indicacao])
            ->andFilterWhere(['like', 'endereco', $this->endereco])
            ->andFilterWhere(['like', 'nacionalidade', $this->nacionalidade])
            ->andFilterWhere(['like', 'naturalidade', $this->naturalidade])
            ->andFilterWhere(['like', 'estado_civil', $this->estado_civil])
            ->andFilterWhere(['like', 'nome_mae', $this->nome_mae])
            ->andFilterWhere(['like', 'nome_pai', $this->nome_pai]);

        return $dataProvider;
    }
}

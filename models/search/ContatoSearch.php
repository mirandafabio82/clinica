<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Contato;

/**
 * ContatoSearch represents the model behind the search form about `app\models\Contato`.
 */
class ContatoSearch extends Contato
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usuario_id', 'cliente_id'], 'integer'],
            [['nome', 'tratamento', 'site', 'contato', 'setor', 'email', 'telefone', 'celular', 'criado', 'modificado'], 'safe'],
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
        $query = Contato::find();

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
            'usuario_id' => $this->usuario_id,
            'cliente_id' => $this->cliente_id,
            'criado' => $this->criado,
            'modificado' => $this->modificado,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'tratamento', $this->tratamento])
            ->andFilterWhere(['like', 'site', $this->site])
            ->andFilterWhere(['like', 'contato', $this->contato])
            ->andFilterWhere(['like', 'setor', $this->setor])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'telefone', $this->telefone])
            ->andFilterWhere(['like', 'celular', $this->celular]);

        return $dataProvider;
    }
}

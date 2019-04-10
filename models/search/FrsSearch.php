<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Frs as FrsModel;

/**
 * Frs represents the model behind the search form of `app\models\Frs`.
 */
class FrsSearch extends FrsModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['contrato', 'pedido', 'frs', 'criador', 'data_criacao', 'aprovador', 'data_aprovacao', 'cnpj_emitente', 'nota_fiscal', 'referencia', 'texto_breve', 'cnpj_braskem'], 'safe'],
            [['valor'], 'number'],
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
        $query = FrsModel::find();

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
            'data_criacao' => $this->data_criacao,
            'data_aprovacao' => $this->data_aprovacao,
            'valor' => $this->valor,
        ]);

        $query->andFilterWhere(['like', 'contrato', $this->contrato])
            ->andFilterWhere(['like', 'pedido', $this->pedido])
            ->andFilterWhere(['like', 'frs', $this->frs])
            ->andFilterWhere(['like', 'criador', $this->criador])
            ->andFilterWhere(['like', 'aprovador', $this->aprovador])
            ->andFilterWhere(['like', 'cnpj_emitente', $this->cnpj_emitente])
            ->andFilterWhere(['like', 'nota_fiscal', $this->nota_fiscal])
            ->andFilterWhere(['like', 'referencia', $this->referencia])
            ->andFilterWhere(['like', 'texto_breve', $this->texto_breve])
            ->andFilterWhere(['like', 'cnpj_braskem', $this->cnpj_braskem]);

        return $dataProvider;
    }
}

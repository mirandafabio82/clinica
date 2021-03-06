<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Atividademodelo;

/**
 * AtividademodeloSearch represents the model behind the search form about `app\models\Atividademodelo`.
 */
class AtividademodeloSearch extends Atividademodelo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['is_conceitual', 'is_basico', 'is_detalhamento', 'is_configuracao', 'is_servico', 'disciplina_id'], 'safe'],
            [['isPrioritaria', 'isEntregavel'], 'integer'],
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
        $query = Atividademodelo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort'=>array(
              'defaultOrder'=>'nome ASC',
            ),
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
        ]);

        $query->andFilterWhere(['like', 'disciplina_id', $this->disciplina_id]);

        return $dataProvider;
    }
}

<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Agendamento;
use app\models\AgendaStatus;

/**
 * AgendaSearch represents the model behind the search form about `app\models\Agenda`.
 */
class AgendaSearch extends Agendamento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome', 'horario'], 'required'],
            [['horario'], 'safe'],
            [['id_status'], 'integer'],
            [['nome'], 'string', 'max' => 55],
            [['cpf'], 'string', 'max' => 15],
            [['tipo_atendimento', 'plano_particular'], 'string', 'max' => 50],
            [['descricao'], 'string', 'max' => 255],
            [['id_status'], 'exist', 'skipOnError' => true, 'targetClass' => AgendaStatus::className(), 'targetAttribute' => ['id_status' => 'id']],
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
        $query = Agendamento::find();

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
      /*  $query->andFilterWhere([
            'id' => $this->id,
            'projeto' => $this->projeto,
            'data' => $this->data,
            'hr_inicio' => $this->hr_inicio,
            'hr_final' => $this->hr_final,
        ]);*/

        /*$query->andFilterWhere(['like', 'local', $this->local])
            ->andFilterWhere(['like', 'quem', $this->quem])
            ->andFilterWhere(['like', 'assunto', $this->assunto])
            ->andFilterWhere(['like', 'status', $this->status]);*/

        return $dataProvider;
    }
}

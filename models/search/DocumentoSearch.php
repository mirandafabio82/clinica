<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Documento;

/**
 * DocumentoSearch represents the model behind the search form of `app\models\Documento`.
 */
class DocumentoSearch extends Documento
{
    public $pacienteNome;
    public $pacienteCPF;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_documento', 'id_tipo_documento', 'id_paciente'], 'integer'],
            [['observacao', 'path', 'data'], 'safe'],
            [['pacienteNome', 'pacienteCPF'], 'safe'],
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
        $query = Documento::find();

        $query->joinWith(['paciente']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['paciente.nome'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['paciente.nome' => SORT_ASC],
            'desc' => ['paciente.nome' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['paciente.cpf'] = [
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
            'id_documento' => $this->id_documento,
            'id_tipo_documento' => $this->id_tipo_documento,
            'id_paciente' => $this->id_paciente,
            'data' => $this->data,
        ]);

        $query->andFilterWhere(['like', 'observacao', $this->observacao])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'paciente.nome', $this->pacienteNome])
            ->andFilterWhere(['like', 'paciente.cpf', $this->pacienteCPF]);

        return $dataProvider;
    }
}

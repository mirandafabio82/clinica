<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Documento;

/**
 * DocumentoSearch represents the model behind the search form about `app\models\Documento`.
 */
class DocumentoSearch extends Documento
{
    public $projeto;
    public $projeto_executante;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'projeto_id', 'revisao'], 'integer'],
            [['nome', 'data', 'tipo', 'criado', 'modificado', 'projeto', 'projeto_executante'], 'safe'],
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
        $query->joinWith(['projeto']);

        if(!isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin'])){      
            $query->join('LEFT JOIN','projeto_executante', 'projeto.id =projeto_executante.projeto_id');              
            $query->where(['is_global' => 1, 'projeto_executante.executante_id' => Yii::$app->user->getId()]);
        }


        
        // add conditions that should always apply here
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
            'projeto_id' => $this->projeto_id,            
            'revisao' => $this->revisao,
            'data' => $this->data,
            'criado' => $this->criado,
            'modificado' => $this->modificado,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'tipo', $this->tipo])
            ->andFilterWhere(['like', 'projeto.nome', $this->projeto]);

        return $dataProvider;
    }
}

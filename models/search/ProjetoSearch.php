<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Projeto;

/**
 * ProjetoSearch represents the model behind the search form about `app\models\Projeto`.
 */
class ProjetoSearch extends Projeto
{
    public $projeto_executante;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cliente_id', 'contato_id', 'documentos', 'rev_proposta', 'qtd_hh', 'qtd_dias', 'qtd_km'], 'integer'],
            [['descricao', 'codigo', 'site', 'planta', 'municipio', 'uf', 'cnpj', 'tratamento', 'contato', 'setor', 'fone_contato', 'celular', 'email', 'proposta', 'data_proposta', /*'status',*/ 'pendencia', 'comentarios', 'data_entrega', 'cliente_fatura', 'site_fatura', 'municipio_fatura', 'uf_fatura', 'cnpj_fatura', 'criado', 'modificado', 'nome', 'projeto_executante'], 'safe'],
            [['vl_hh', 'total_horas', 'vl_km', 'total_km', 'valor_proposta', 'valor_consumido', 'valor_saldo'], 'number'],
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
        $query = Projeto::find();
        
        if(!isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin'])){      
            $query->joinWith('projeto_executante');              
            $query->where(['projeto_executante.executante_id' => Yii::$app->user->getId()]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
            'defaultOrder' => [
                'id' => SORT_DESC,
            ]]
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
            'cliente_id' => $this->cliente_id,            
            'contato_id' => $this->contato_id,
            'documentos' => $this->documentos,
            'rev_proposta' => $this->rev_proposta,
            'data_proposta' => $this->data_proposta,
            'qtd_hh' => $this->qtd_hh,
            'vl_hh' => $this->vl_hh,
            'total_horas' => $this->total_horas,
            'qtd_dias' => $this->qtd_dias,
            'qtd_km' => $this->qtd_km,
            'vl_km' => $this->vl_km,
            'total_km' => $this->total_km,
            'valor_proposta' => $this->valor_proposta,
            'valor_consumido' => $this->valor_consumido,
            'valor_saldo' => $this->valor_saldo,
            'data_entrega' => $this->data_entrega,
            'criado' => $this->criado,
            'modificado' => $this->modificado,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'codigo', $this->codigo])
            ->andFilterWhere(['like', 'site', $this->site])
            ->andFilterWhere(['like', 'planta', $this->planta])
            ->andFilterWhere(['like', 'municipio', $this->municipio])
            ->andFilterWhere(['like', 'uf', $this->uf])
            ->andFilterWhere(['like', 'cnpj', $this->cnpj])
            ->andFilterWhere(['like', 'tratamento', $this->tratamento])
            ->andFilterWhere(['like', 'contato', $this->contato])
            ->andFilterWhere(['like', 'setor', $this->setor])
            ->andFilterWhere(['like', 'fone_contato', $this->fone_contato])
            ->andFilterWhere(['like', 'celular', $this->celular])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'proposta', $this->proposta])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'pendencia', $this->pendencia])
            ->andFilterWhere(['like', 'comentarios', $this->comentarios])
            ->andFilterWhere(['like', 'cliente_fatura', $this->cliente_fatura])
            ->andFilterWhere(['like', 'site_fatura', $this->site_fatura])
            ->andFilterWhere(['like', 'municipio_fatura', $this->municipio_fatura])
            ->andFilterWhere(['like', 'uf_fatura', $this->uf_fatura])
            ->andFilterWhere(['like', 'cnpj_fatura', $this->cnpj_fatura]);

        return $dataProvider;
    }
}

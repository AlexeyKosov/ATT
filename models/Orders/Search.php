<?php

namespace app\models\Orders;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Orders;

/**
 * Search represents the model behind the search form about `app\models\Orders`.
 */
class Search extends Orders
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'stage'], 'integer'],
            [['imei', 'make', 'phone_model', 'date_create', 'request_number'], 'safe'],
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
        $query = Orders::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'stage' => $this->stage,
            'date_create' => $this->date_create,
        ]);

        $query->andFilterWhere(['like', 'imei', $this->imei])
            ->andFilterWhere(['like', 'make', $this->make])
            ->andFilterWhere(['like', 'phone_model', $this->phone_model])
            ->andFilterWhere(['like', 'request_number', $this->request_number]);

        return $dataProvider;
    }
}

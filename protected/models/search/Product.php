<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product as ProductModel;

/**
 * Product represents the model behind the search form about `app\models\Product`.
 */
class Product extends ProductModel
{

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'category_id',
                    'deal_id',
                    'type_id'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'description',
                    'code',
                    'price',
                    'actual_quantity',
                    'is_fav',
                    'state_id',
                    'created_on',
                    'updated_on',
                    'created_by_id'
                ],
                'safe'
            ]
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function beforeValidate()
    {
        return true;
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
        $query = ProductModel::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        
        if (! ($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'deal_id' => $this->deal_id,
            'type_id' => $this->type_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on
        
        ]);
        
        $query->andFilterWhere([
            'like',
            'title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'code',
            $this->code
        ])
            ->andFilterWhere([
            'like',
            'price',
            $this->price
        ])
            ->andFilterWhere([
            'like',
            'actual_quantity',
            $this->actual_quantity
        ])
            ->andFilterWhere([
            'like',
            'is_fav',
            $this->is_fav
        ])
            ->andFilterWhere([
            'like',
            'state_id',
            $this->state_id
        ]);
        if ($this->created_by_id) {
            $query->joinWith('createdBy as u')->andFilterWhere([
                'like',
                'u.first_name',
                $this->created_by_id
            ]);
        }
        return $dataProvider;
    }
}

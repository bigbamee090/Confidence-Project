<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Company as CompanyModel;

/**
 * Company represents the model behind the search form about `app\models\Company`.
 */
class Company extends CompanyModel
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
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'company_name',
                    'registration_number',
                    'vat_registration_number',
                    'country',
                    'created_on',
                    'updated_on',
                    'first_name'
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
        $query = CompanyModel::find();
        
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
            'state_id' => $this->state_id,
            'type_id' => $this->type_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_by_id' => $this->created_by_id
        ]);
        
        $query->andFilterWhere([
            'like',
            'company_name',
            $this->company_name
        ])
            ->andFilterWhere([
            'like',
            'registration_number',
            $this->registration_number
        ])
            ->andFilterWhere([
            'like',
            'first_name',
            $this->first_name
        ])
            ->andFilterWhere([
            'like',
            'vat_registration_number',
            $this->vat_registration_number
        ])
            ->andFilterWhere([
            'like',
            'country',
            $this->country
        ]);
        
        return $dataProvider;
    }
}

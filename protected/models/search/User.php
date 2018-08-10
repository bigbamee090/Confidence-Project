<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use app\models\User as UserModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CompanyAdmin;
use app\models\CompanyPrescriber;

/**
 * User represents the model behind the search form about `app\models\User`.
 */
class User extends UserModel
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
                    'gender',
                    'tos',
                    'role_id',
                    'state_id',
                    'type_id',
                    'login_error_count',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'full_name',
                    'email',
                    'password',
                    'date_of_birth',
                    'about_me',
                    'contact_no',
                    'address',
                    'latitude',
                    'longitude',
                    'city',
                    'country',
                    'zipcode',
                    'language',
                    'profile_file',
                    'last_visit_time',
                    'last_action_time',
                    'last_password_change',
                    'activation_key',
                    'timezone',
                    'created_on',
                    'updated_on'
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
    public function search($params, $role = null)
    {
        if ((UserModel::isCompanyAdmin() || UserModel::isCompanyManager() || UserModel::isCompanyPrescriber()) && UserModel::checkPermision(UserModel::PERMISSION_SUPER_USER)) {
            $query = UserModel::find()->where([
                '!=',
                'id',
                \Yii::$app->user->id
            ]);
            
            $user = \Yii::$app->user->identity;
            $company_id = 0;
            if (isset($user->companyAdmin) && isset($user->companyAdmin->company_id)) {
                $company_id = $user->companyAdmin->company_id;
            } else if (isset($user->companyAdmin) && isset($user->companyAdmin->company_id)) {
                $company_id = $user->companyAdmin->company_id;
            } else if (isset($user->companyPrescriber) && isset($user->companyPrescriber->company_id)) {
                $company_id = $user->companyPrescriber->company_id;
            }
            
            $admins = CompanyAdmin::find()->select('user_id')->where([
                'company_id' => $company_id
            ]);
            $prescribers = CompanyPrescriber::find()->select('user_id')->where([
                'company_id' => $company_id
            ]);
            
            $query->andWhere([
                'in',
                'id',
                $admins
            ])->orWhere([
                'in',
                'id',
                $prescribers
            ]);
        } else {
            $query = UserModel::find()->where([
                '!=',
                'id',
                \Yii::$app->user->id
            ]);
        }
        
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
            'role_id' => $this->role_id,
            'state_id' => $this->state_id
        ]);
        
        $query->andFilterWhere([
            'like',
            'full_name',
            $this->full_name
        ])
            ->andFilterWhere([
            'like',
            'email',
            $this->email
        ])
            ->andFilterWhere([
            'like',
            'contact_no',
            $this->contact_no
        ])
            ->andFilterWhere([
            'like',
            'created_on',
            $this->created_on
        ]);
        
        return $dataProvider;
    }
}

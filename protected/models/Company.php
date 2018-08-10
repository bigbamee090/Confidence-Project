<?php

/**
 * This is the model class for table "tbl_company".
 *
 * @property integer $id
 * @property string $company_name
 * @property string $registration_number
 * @property string $vat_registration_number
 * @property string $country
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id
 
 * === Related data ===
 * @property User $createdBy
 */
namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class Company extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->company_name;
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_BANNED = 2;

    const STATE_DELETED = 4;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "Inactive",
            self::STATE_ACTIVE => "Active",
            self::STATE_BANNED => "Banned",
            self::STATE_DELETED => "Deleted"
        ];
    }

    public function getState()
    {
        $list = self::getStateOptions();
        return isset($list[$this->state_id]) ? $list[$this->state_id] : 'Not Defined';
    }

    public function getStateBadge()
    {
        $list = [
            self::STATE_INACTIVE => "default",
            self::STATE_ACTIVE => "success",
            self::STATE_BANNED => "warning",
            self::STATE_DELETED => "danger"
        ];
        // return isset($list[$this->state_id])?\yii\helpers\Html::tag('span', $this->getState(), ['class' => 'badge bg-' . $list[$this->state_id]]):'Not Defined';
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'label label-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    const TYPE_SOLO_TRADER = 0;

    const TYPE_LIMITED_LIABILITY = 1;

    const TYPE_PARTNERSHIP = 2;

    const TYPE_OTHER = 3;

    public static function getTypeOptions()
    {
        return [
            self::TYPE_SOLO_TRADER => 'Sole Trader',
            self::TYPE_LIMITED_LIABILITY => 'Limited Liability',
            self::TYPE_PARTNERSHIP => 'Partnership',
            self::TYPE_OTHER => 'Other'
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (! isset($this->created_on))
                $this->created_on = date('Y-m-d H:i:s');
            if (! isset($this->updated_on))
                $this->updated_on = date('Y-m-d H:i:s');
            if (! isset($this->created_by_id))
                $this->created_by_id = Yii::$app->user->id;
            $this->plan_id = PlanType::checkBasicType()->id;
        } else {
            $this->updated_on = date('Y-m-d H:i:s');
        }
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%company}}';
    }

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'company_name',
                    'first_name',
                    'email',
                    'contact_no',
                    'registration_number',
                    'vat_registration_number',
                    'country',
                    'type_id',
                    'created_on'
                ],
                'required'
            ],
            [
                'email',
                'email'
            ],
            [
                [
                    'state_id',
                    'plan_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'updated_on'
                
                ],
                'safe'
            ],
            [
                [
                    'company_name',
                    'known_as',
                    'registration_number',
                    'vat_registration_number',
                    'country',
                    'last_name'
                ],
                'string',
                'max' => 256
            ],
            [
                [
                    'created_by_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => [
                    'created_by_id' => 'id'
                ]
            ],
            [
                [
                    'company_name',
                    'registration_number',
                    'vat_registration_number',
                    'country'
                ],
                'trim'
            ],
            [
                [
                    'state_id'
                ],
                'in',
                'range' => array_keys(self::getStateOptions())
            ]
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'company_name' => Yii::t('app', 'Company Name'),
            'contact_no' => Yii::t('app', 'Telephone'),
            'registration_number' => Yii::t('app', 'Registration Number'),
            'vat_registration_number' => Yii::t('app', 'Vat Registration Number'),
            'country' => Yii::t('app', 'Country of Registration'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'known_as' => Yii::t('app', 'Known As'),
            'created_by_id' => Yii::t('app', 'Created By')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), [
            'id' => 'created_by_id'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanType()
    {
        return $this->hasOne(PlanType::className(), [
            'id' => 'plan_id'
        ]);
    }

    public static function getHasManyRelations()
    {
        $relations = [];
        return $relations;
    }

    public static function getHasOneRelations()
    {
        $relations = [];
        $relations['created_by_id'] = [
            'createdBy',
            'User',
            'id'
        ];
        return $relations;
    }

    public function beforeDelete()
    {
        Cart::deleteRelatedAll([
            'company_id' => $this->id
        ]);
        CompanyAdmin::deleteRelatedAll([
            'company_id' => $this->id
        ]);
        DeliveryAddress::deleteRelatedAll([
            'company_id' => $this->id
        ]);
        InvoiceAddress::deleteRelatedAll([
            'company_id' => $this->id
        ]);
        CompanyPrescriber::deleteRelatedAll([
            'company_id' => $this->id
        ]);
        return parent::beforeDelete();
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['company_name'] = $this->company_name;
        $json['registration_number'] = $this->registration_number;
        $json['vat_registration_number'] = $this->vat_registration_number;
        $json['country'] = $this->country;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {
            // createdBy
            $list = $this->createdBy;
            
            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['createdBy'] = $relationData;
            } else {
                $json['CreatedBy'] = $list;
            }
        }
        return $json;
    }

    public static function getPlans()
    {
        return ArrayHelper::map(PlanType::findActive()->all(), 'id', 'title');
    }

    public function getCompanyPlan()
    {
        $plan = PlanType::findOne([
            'id' => $this->plan_id
        ]);
        if (empty($plan)) {
            $newPlan = PlanType::findOne([
                'type_id' => PlanType::TYPE_BASIC
            ]);
            
            $this->plan_id = $newPlan->id;
            $this->save();
            
            $plan = PlanType::findOne([
                'id' => $this->plan_id
            ]);
        }
        return $plan;
    }

    public static function getDeliveryAddress($one = true)
    {
        $company_id = null;
        $user = \Yii::$app->user->identity;
        if (User::isCompanyAdmin() && isset($user->companyAdmin)) {
            $company_id = $user->companyAdmin->company_id;
        } else if (User::isCompanyManager() && isset($user->companyAdmin)) {
            $company_id = $user->companyAdmin->company_id;
        } else if (User::isCompanyPrescriber() && isset($user->companyPrescriber)) {
            $company_id = $user->companyPrescriber->company_id;
        }
        
        if ($one)
            return DeliveryAddress::findOne([
                'company_id' => $company_id,
                'state_id' => DeliveryAddress::STATE_ACTIVE
            ]);
        else
            return DeliveryAddress::findAll([
                'company_id' => $company_id
            ]);
    }

    public static function getCompanyId()
    {
        $company_id = null;
        $user = \Yii::$app->user->identity;
        if (User::isCompanyAdmin() && isset($user->companyAdmin)) {
            $company_id = $user->companyAdmin->company_id;
        } else if (User::isCompanyManager() && isset($user->companyAdmin)) {
            $company_id = $user->companyAdmin->company_id;
        } else if (User::isCompanyPrescriber() && isset($user->companyPrescriber)) {
            $company_id = $user->companyPrescriber->company_id;
        }
        return $company_id;
    }
}

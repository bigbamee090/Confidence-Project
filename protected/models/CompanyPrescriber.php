<?php

/**
 * This is the model class for table "tbl_company_prescriber".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $contact_no
 * @property string $salutation
 * @property string $registration_number
 * @property integer $company_type
 * @property string $address_line1
 * @property string $address_line2
 * @property string $city
 * @property string $country
 * @property string $pincode
 * @property string $permission
 * @property string $passport_image
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
use app\models\User;
use yii\helpers\ArrayHelper;

class CompanyPrescriber extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->first_name;
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "New",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Archived"
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
            self::STATE_INACTIVE => "primary",
            self::STATE_ACTIVE => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'label label-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    const SALUTATION_DR = 0;

    const SALUTATION_MS = 1;

    const SALUTATION_MRS = 2;

    const SALUTATION_MISS = 3;

    public function getSalutationOptions()
    {
        return [
            self::SALUTATION_DR => "Dr.",
            self::SALUTATION_MS => "Ms.",
            self::SALUTATION_MRS => "Mrs.",
            self::SALUTATION_MISS => "Miss."
        ];
    }

    public function getSalutation()
    {
        $list = self::getSalutationOptions();
        return isset($list[$this->salutation]) ? $list[$this->salutation] : 'Not Defined';
    }

    const COMPANY_TYPE_GMC = 0;

    const COMPANY_TYPE_GDC = 1;

    const COMPANY_TYPE_RGN = 2;

    const COMPANY_TYPE_INP = 3;

    const COMPANY_TYPE_PIP = 4;

    const COMPANY_TYPE_ADMIN = 5;

    const COMPANY_TYPE_ADMIN_MANAGER = 6;

    const COMPANY_TYPE_OTHER = 7;

    public static function getCompanyTypeOptions()
    {
        return [
            self::COMPANY_TYPE_GMC => "GMC",
            self::COMPANY_TYPE_GDC => "GDC",
            self::COMPANY_TYPE_RGN => "RGN",
            self::COMPANY_TYPE_INP => "INP",
            self::COMPANY_TYPE_PIP => "PIP",
            self::COMPANY_TYPE_ADMIN => "Admin",
            self::COMPANY_TYPE_ADMIN_MANAGER => "Admin Manager",
            self::COMPANY_TYPE_OTHER => "Other"
        ];
    }

    public static function getTypeOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
        // return ArrayHelper::Map ( Type::findActive ()->all (), 'id', 'title' );
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
        return '{{%company_prescriber}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        
        $scenarios['client_user'] = [
            'first_name',
            'email',
            'last_name',
            'salutation',
            'registration_number',
            'company_type',
            'passport_image',
            'address_line1',
            'city',
            'country',
            'permission',
            'pincode',
            'created_on',
            'created_by_id'
        ];
        
        return $scenarios;
    }

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [
            // [
            // // 'first_name',
            // 'salutation',
            // 'registration_number',
            // 'company_type',
            // 'address_line1',
            // 'city',
            // 'permission',
            // 'country',
            // 'company_id',
            // 'pincode',
            // // 'passport_image',
            // 'created_on',
            // 'created_by_id'
            // ],
            // 'required'
            // ],
            [
                [
                    'first_name',
                    'salutation',
                    'registration_number',
                    'company_type',
                    'permission',
                    'address_line1',
                    'passport_image',
                    'email',
                    'city',
                    'country',
                    'pincode',
                    'created_on',
                    'created_by_id'
                ],
                'required',
                'on' => 'client_user'
            ],
            [
                'email',
                'unique'
            ],
            [
                'email',
                'checkEmail'
            ],
            [
                [
                    'company_type',
                    'state_id',
                    'user_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'permission',
                    'updated_on'
                ],
                'safe'
            ],
            [
                [
                    'first_name',
                    'last_name',
                    'email',
                    'contact_no',
                    'salutation',
                    'registration_number'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'address_line1',
                    'address_line2',
                    'city',
                    'country',
                    'pincode'
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
                    'first_name',
                    'last_name',
                    'email',
                    'contact_no',
                    'salutation',
                    'registration_number',
                    'address_line1',
                    'address_line2',
                    'city',
                    'country',
                    'pincode'
                ],
                'trim'
            ],
            [
                [
                    'passport_image'
                ],
                'file',
                'extensions' => 'jpeg, gif, png, jpg'
            ],
            [
                [
                    'first_name'
                ],
                'app\components\TNameValidator'
            ],
            [
                [
                    'last_name'
                ],
                'app\components\TNameValidator'
            ],
            [
                [
                    'email'
                ],
                'email'
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

    public function checkEmail($attribute, $params)
    {
        if ($this->email) {
            $user = User::find()->where([
                'email' => $this->email
            ])->count();
            if (! empty($user)) {
                $this->addError($attribute, Yii::t('app', "This Email '{$this->email}' Already exists."));
            }
        }
    }

    /**
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'email' => Yii::t('app', 'Email'),
            'contact_no' => Yii::t('app', 'Telephone'),
            'salutation' => Yii::t('app', 'Salutation'),
            'registration_number' => Yii::t('app', 'Registration Number'),
            'company_type' => Yii::t('app', 'Company Type'),
            'address_line1' => Yii::t('app', 'Address Line 1'),
            'address_line2' => Yii::t('app', 'Address Line 2'),
            'city' => Yii::t('app', 'City'),
            'country' => Yii::t('app', 'Country'),
            'pincode' => Yii::t('app', 'Pincode'),
            'permission' => Yii::t('app', 'Permission'),
            'passport_image' => Yii::t('app', 'Passport Image'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
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

    public function getCompany()
    {
        return $this->hasOne(Company::className(), [
            'id' => 'company_id'
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
        return parent::beforeDelete();
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['first_name'] = $this->first_name;
        $json['last_name'] = $this->last_name;
        $json['email'] = $this->email;
        $json['contact_no'] = $this->contact_no;
        $json['salutation'] = $this->salutation;
        $json['registration_number'] = $this->registration_number;
        $json['company_type'] = $this->company_type;
        $json['address_line1'] = $this->address_line1;
        $json['address_line2'] = $this->address_line2;
        $json['city'] = $this->city;
        $json['country'] = $this->country;
        $json['pincode'] = $this->pincode;
        $json['permission'] = $this->permission;
        $json['passport_image'] = $this->passport_image;
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
}

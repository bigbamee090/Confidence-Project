<?php

/**
 * This is the model class for table "tbl_company_admin".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $contact_no
 * @property string $salutation
 * @property string $registration_number
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

class CompanyAdmin extends \app\components\TActiveRecord
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

    const SALUTATION_MR = 1;

    const SALUTATION_MS = 2;

    const SALUTATION_MRS = 3;

    const SALUTATION_MISS = 4;

    public function getSalutationOptions()
    {
        return [
            self::SALUTATION_DR => "Dr.",
            self::SALUTATION_MR => "Mr.",
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
        return '{{%company_admin}}';
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
            'permission',
            'address_line1',
            'city',
            'country',
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
            /*
             * [
             * [
             * 'first_name',
             * 'salutation',
             * 'registration_number',
             * 'address_line1',
             * 'permission',
             * 'city',
             * 'country',
             * 'pincode',
             * 'company_id'
             * // 'passport_image',
             * ],
             * 'required'
             * ],
             */
            [
                [
                    'first_name',
                    'salutation',
                    'registration_number',
                    'address_line1',
                    'city',
                    'email',
                    'permission',
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
                    'state_id',
                    'type_id',
                    'user_id',
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
                'file'
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
            'address_line1' => Yii::t('app', 'Address Line 1'),
            'address_line2' => Yii::t('app', 'Address Line 2'),
            'city' => Yii::t('app', 'City'),
            'country' => Yii::t('app', 'Country'),
            'pincode' => Yii::t('app', 'Postal Code'),
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

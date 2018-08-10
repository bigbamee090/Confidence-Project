<?php

/**
 * This is the model class for table "tbl_cart".
 *
 * @property integer $id
 * @property integer $quantity
 * @property string $actual_price
 * @property string $discounted_price
 * @property integer $company_id
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id
 
 * === Related data ===
 * @property Company $company
 * @property User $createdBy
 */
namespace app\models;

use Yii;

class Cart extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->quantity;
    }

    public static function getCompanyOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
        // return ArrayHelper::Map ( Company::findActive ()->all (), 'id', 'title' );
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
            if (! isset($this->created_by_id))
                $this->created_by_id = Yii::$app->user->id;
        } else {}
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart}}';
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
                    'quantity',
                    'company_id',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'actual_price',
                    'company_id',
                    'created_on'
                ],
                'required'
            ],
            [
                [
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'actual_price',
                    'discounted_price'
                ],
                'number'
            ],
            [
                [
                    'company_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Company::className(),
                'targetAttribute' => [
                    'company_id' => 'id'
                ]
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
                    'actual_price',
                    'discounted_price'
                ],
                'trim'
            ],
            [
                [
                    'state_id'
                ],
                'in',
                'range' => array_keys(self::getStateOptions())
            ],
            [
                [
                    'type_id'
                ],
                'in',
                'range' => array_keys(self::getTypeOptions())
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
            'quantity' => Yii::t('app', 'Quantity'),
            'actual_price' => Yii::t('app', 'Actual Price'),
            'discounted_price' => Yii::t('app', 'Discounted Price'),
            'company_id' => Yii::t('app', 'Company'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Created By')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), [
            'id' => 'company_id'
        ]);
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

    public static function getHasManyRelations()
    {
        $relations = [];
        return $relations;
    }

    public static function getHasOneRelations()
    {
        $relations = [];
        $relations['company_id'] = [
            'company',
            'Company',
            'id'
        ];
        $relations['created_by_id'] = [
            'createdBy',
            'User',
            'id'
        ];
        return $relations;
    }

    public function beforeDelete()
    {
        CartItem::deleteRelatedAll([
            'cart_id' => $this->id
        ]);
        return parent::beforeDelete();
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['quantity'] = $this->quantity;
        $json['actual_price'] = $this->actual_price;
        $json['discounted_price'] = $this->discounted_price;
        $json['company_id'] = $this->company_id;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {
            // company
            $list = $this->company;
            
            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['company'] = $relationData;
            } else {
                $json['Company'] = $list;
            }
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

    public function setActualPrice($price)
    {
        if (! empty($this->actual_price)) {
            $this->actual_price = floatval($this->actual_price) + floatval($price);
        } else {
            $this->actual_price = floatval($price);
        }
        return $this->actual_price;
    }

    public function setDiscountedPrice($price)
    {
        if (! empty($this->discounted_price)) {
            $this->discounted_price = floatval($this->discounted_price) + floatval($price);
        } else {
            $this->discounted_price = floatval($price);
        }
        return $this->discounted_price;
    }

    public function setCompanyId()
    {
        $user = \Yii::$app->user->identity;
        
        if (User::isCompanyAdmin() && isset($user->companyAdmin)) {
            $this->company_id = $user->companyAdmin->company_id;
        } else if (User::isCompanyManager() && isset($user->companyAdmin)) {
            $this->company_id = $user->companyAdmin->company_id;
        } else if (User::isCompanyPrescriber() && isset($user->companyPrescriber)) {
            $this->company_id = $user->companyPrescriber->company_id;
        }
        return $this->company_id;
    }

    public static function saveCart($id)
    {
        $cart = self::findOne([
            'created_by_id' => \Yii::$app->user->id
        ]);
        if (empty($cart)) {
            $cart = new self();
        }
        $product = Product::findOne($id);
        
        if (! empty($product)) {
            $cart->setActualPrice($product->price);
            $cart->setDiscountedPrice($product->getDiscountedPrice());
            $cart->setCompanyId();
            if ($cart->save()) {
                $itme = CartItem::saveItem($cart->id, $product);
                return $itme;
            } else {
                return [
                    'errors' => $cart->errors
                ];
            }
        }
        return false;
    }
}

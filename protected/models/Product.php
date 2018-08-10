<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_product".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $code
 * @property string $price
 * @property string $actual_quantity
 * @property integer $category_id
 * @property integer $deal_id
 * @property integer $is_fav
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id === Related data ===
 * @property OrderItem[] $orderItems
 * @property Category $category
 * @property User $createdBy
 * @property Deal $deal
 */
namespace app\models;

use Yii;

class Product extends \app\components\TActiveRecord
{

    const SORT_PRICE_L = "L";

    const SORT_PRICE_H = "H";

    const SORT_TITLE_A = "A";

    const SORT_TITLE_Z = "Z";

    public function __toString()
    {
        return (string) $this->title;
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

    const CLASS_GSL = 0;

    const CLASS_POM = 1;

    const CLASS_P = 2;

    public static function getClassOptions()
    {
        return [
            self::CLASS_GSL => 'GSL',
            self::CLASS_POM => 'POM',
            self::CLASS_P => 'P'
        ];
    }

    public function getClass()
    {
        $list = self::getClassOptions();
        return isset($list[$this->class_id]) ? $list[$this->class_id] : 'Not Defined';
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
        return '{{%product}}';
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
                    'title',
                    'code',
                    'class_id',
                    'price',
                    'category_id',
                    'created_on',
                    'created_by_id',
                    'actual_quantity'
                ],
                'required'
            ],
            [
                [
                    'description'
                ],
                'string'
            ],
            [
                [
                    'category_id',
                    'deal_id',
                    'is_fav',
                    'state_id',
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
                    'title'
                ],
                'string',
                'max' => 256
            ],
            [
                [
                    'code',
                    'actual_quantity'
                ],
                'string',
                'max' => 125
            ],
            [
                [
                    'price'
                ],
                'number'
            ],
            [
                [
                    'category_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::className(),
                'targetAttribute' => [
                    'category_id' => 'id'
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
                    'deal_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Deal::className(),
                'targetAttribute' => [
                    'deal_id' => 'id'
                ]
            ],
            [
                [
                    'title',
                    'code',
                    'price',
                    'actual_quantity',
                    'is_fav',
                    'state_id'
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
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'code' => Yii::t('app', 'Code'),
            'price' => Yii::t('app', 'Price'),
            'actual_quantity' => Yii::t('app', 'Actual Quantity'),
            'category_id' => Yii::t('app', 'Category'),
            'class_id' => Yii::t('app', 'Class'),
            'deal_id' => Yii::t('app', 'Deal'),
            'is_fav' => Yii::t('app', 'Is Fav'),
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
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), [
            'product_id' => 'id'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), [
            'id' => 'category_id'
        ]);
    }

    public function getCategories()
    {
        $flag = false;
        $data = [];
        
        $models = Category::find()->where([
            'state_id' => Category::STATE_ACTIVE
        ])->all();
        
        if (! empty($models)) {
            foreach ($models as $model) {
                $data[$model->id] = $model->title;
            }
        }
        return $data;
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
    public function getDeal()
    {
        return $this->hasOne(Deal::className(), [
            'id' => 'deal_id'
        ]);
    }

    public static function getHasManyRelations()
    {
        $relations = [];
        $relations['OrderItems'] = [
            'orderItems',
            'OrderItem',
            'id',
            'product_id'
        ];
        return $relations;
    }

    public static function getHasOneRelations()
    {
        $relations = [];
        $relations['category_id'] = [
            'category',
            'Category',
            'id'
        ];
        $relations['created_by_id'] = [
            'createdBy',
            'User',
            'id'
        ];
        $relations['deal_id'] = [
            'deal',
            'Deal',
            'id'
        ];
        return $relations;
    }

    public function beforeDelete()
    {
        // OrderItem::deleteRelatedAll(['product_id'=>$this->id]);
        CartItem::deleteRelatedAll([
            'product_id' => $this->id
        ]);
        return parent::beforeDelete();
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['title'] = $this->title;
        $json['description'] = $this->description;
        $json['code'] = $this->code;
        $json['price'] = $this->price;
        $json['actual_quantity'] = $this->actual_quantity;
        $json['category_id'] = $this->category_id;
        $json['deal_id'] = $this->deal_id;
        $json['is_fav'] = $this->is_fav;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {
            // orderItems
            $list = $this->orderItems;
            
            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['orderItems'] = $relationData;
            } else {
                $json['OrderItems'] = $list;
            }
            // category
            $list = $this->category;
            
            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['category'] = $relationData;
            } else {
                $json['Category'] = $list;
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
            // deal
            $list = $this->deal;
            
            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['deal'] = $relationData;
            } else {
                $json['Deal'] = $list;
            }
        }
        return $json;
    }

    public function getDiscountedPrice()
    {
        $user = \Yii::$app->user->identity;
        $plan = PlanType::checkBasicType();
        
        if (User::isCompanyAdmin() && isset($user->companyAdmin) && isset($user->companyAdmin->company)) {
            $companyPlan = PlanType::findOne($user->companyAdmin->company->plan_id);
            
            if (! empty($companyPlan)) {
                $plan = $companyPlan;
            }
        } else if (User::isCompanyManager() && isset($user->companyAdmin) && isset($user->companyAdmin->company)) {
            $companyPlan = PlanType::findOne($user->companyAdmin->company->plan_id);
            
            if (! empty($companyPlan)) {
                $plan = $companyPlan;
            }
        } else if (User::isCompanyPrescriber() && isset($user->companyPrescriber) && isset($user->companyPrescriber->company)) {
            $companyPlan = PlanType::findOne($user->companyPrescriber->company->plan_id);
            
            if (! empty($companyPlan)) {
                $plan = $companyPlan;
            }
        }
        
        return (floatval($this->price) * (1 - (floatval($plan->percent) / 100)));
    }
    
    // public function deliveryAddress(){
    // $deliveryAddress = DeliveryAddress::find()->where
    // }
}

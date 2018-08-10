<?php

/**
 * This is the model class for table "tbl_cart_item".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $actual_price
 * @property string $discounted_price
 * @property integer $quantity
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id
 
 * === Related data ===
 * @property User $createdBy
 * @property Product $product
 */
namespace app\models;

use Yii;

class CartItem extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->product_id;
    }

    public static function getProductOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
        // return ArrayHelper::Map ( Product::findActive ()->all (), 'id', 'title' );
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
        return '{{%cart_item}}';
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
                    'product_id',
                    'actual_price',
                    'quantity',
                    'created_on'
                ],
                'required'
            ],
            [
                [
                    'product_id',
                    'quantity',
                    'cart_id',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
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
                    'product_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Product::className(),
                'targetAttribute' => [
                    'product_id' => 'id'
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
            'product_id' => Yii::t('app', 'Product'),
            'actual_price' => Yii::t('app', 'Actual Price'),
            'discounted_price' => Yii::t('app', 'Discounted Price'),
            'quantity' => Yii::t('app', 'Quantity'),
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), [
            'id' => 'product_id'
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
        $relations['product_id'] = [
            'product',
            'Product',
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
        $json['product_id'] = $this->product_id;
        $json['actual_price'] = $this->actual_price;
        $json['discounted_price'] = $this->discounted_price;
        $json['quantity'] = $this->quantity;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {
            // product
            $list = $this->product;
            
            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['product'] = $relationData;
            } else {
                $json['Product'] = $list;
            }
        }
        return $json;
    }

    public function setActualPrice($price)
    {
        if (! empty($this->actual_price)) {
            return $this->actual_price;
        } else {
            $this->actual_price = floatval($price);
        }
        return $this->actual_price;
    }

    public function setDiscountedPrice($price)
    {
        if (! empty($this->discounted_price)) {
            return $this->discounted_price;
        } else {
            $this->discounted_price = floatval($price);
        }
        return $this->discounted_price;
    }

    public function setQuantity($q = 1)
    {
        if (! empty($this->quantity)) {
            $this->quantity = ($this->quantity) + $q;
        } else {
            $this->quantity = $q;
        }
        return $this->quantity;
    }

    public static function saveItem($cartId, $product)
    {
        $cartItem = self::findOne([
            'cart_id' => $cartId,
            'product_id' => $product->id,
            'created_by_id' => \Yii::$app->user->id
        ]);
        if (empty($cartItem)) {
            $cartItem = new self();
            $cartItem->product_id = $product->id;
            $cartItem->cart_id = $cartId;
        }
        
        $cartItem->setActualPrice($product->price);
        $cartItem->setDiscountedPrice($product->getDiscountedPrice());
        $cartItem->setQuantity();
        
        if ($cartItem->save()) {
            return $cartItem->asJson(true);
        } else {
            return [
                'errors' => $cartItem->errors
            ];
        }
    }
}

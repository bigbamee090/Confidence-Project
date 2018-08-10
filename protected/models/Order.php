<?php

/**
 * This is the model class for table "tbl_order".
 *
 * @property integer $id
 * @property integer $quantity
 * @property string $price
 * @property integer $delivery_status
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id
 
 * === Related data ===
 * @property User $createdBy
 * @property Orderitem[] $orderitems
 */
namespace app\models;

use Yii;

class Order extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->quantity;
    }

    const STATE_PENDING = 0;

    const STATE_CONFIRM = 1;

    const STATE_DELETED = 2;

    public static function getDeliveryStatusOptions()
    {
        return [
            self::STATE_PENDING => "Pending",
            self::STATE_CONFIRM => "Confirm",
            self::STATE_DELETED => "Archived"
        ];
    }

    public function getDeliveryStatus()
    {
        $list = self::getDeliveryStatusOptions();
        return isset($list[$this->delivery_status]) ? $list[$this->delivery_status] : 'Not Defined';
    }

    public function getDeliveryStatusBadge()
    {
        $list = [
            self::STATE_PENDING => "primary",
            self::STATE_CONFIRM => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->delivery_status]) ? \yii\helpers\Html::tag('span', $this->getDeliveryStatus(), [
            'class' => 'label label-' . $list[$this->delivery_status]
        ]) : 'Not Defined';
    }

    const TYPE_CASH = 0;

    const TYPE_PAYPAL = 1;

    public static function getTypeOptions()
    {
        return [
            self::TYPE_CASH => "Cash",
            self::TYPE_PAYPAL => "Paypal"
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
        return '{{%order}}';
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
                    // 'quantity',
                    'delivery_address',
                    'price',
                    'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'quantity',
                    'delivery_status',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'delivery_address'
                ],
                'safe'
            ],
            [
                [
                    'price'
                ],
                'string',
                'max' => 125
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
                    'price'
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
            'price' => Yii::t('app', 'Price'),
            'delivery_status' => Yii::t('app', 'Delivery Status'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Delivery Type'),
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
    public function getOrderitems()
    {
        return $this->hasMany(OrderItem::className(), [
            'order_id' => 'id'
        ]);
    }

    public static function getHasManyRelations()
    {
        $relations = [];
        $relations['Orderitems'] = [
            'orderitems',
            'Orderitem',
            'id',
            'order_id'
        ];
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
        // Orderitem::deleteRelatedAll(['order_id'=>$this->id]);
        return parent::beforeDelete();
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['quantity'] = $this->quantity;
        $json['price'] = $this->price;
        $json['delivery_status'] = $this->delivery_status;
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
            // orderitems
            $list = $this->orderitems;
            
            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['orderitems'] = $relationData;
            } else {
                $json['Orderitems'] = $list;
            }
        }
        return $json;
    }
}

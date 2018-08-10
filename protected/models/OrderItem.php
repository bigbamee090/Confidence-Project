<?php

 

/**
* This is the model class for table "tbl_order_item".
*
    * @property integer $id
    * @property integer $quantity
    * @property string $price
    * @property integer $product_id
    * @property integer $order_id
    * @property integer $delivery_status
    * @property integer $state_id
    * @property integer $type_id
    * @property string $created_on
    * @property integer $created_by_id

* === Related data ===
    * @property User $createdBy
    * @property Order $order
    * @property Product $product
    */

namespace app\models;

use Yii;
use app\models\User;
use app\models\Order;
use app\models\Product;

use yii\helpers\ArrayHelper;

class OrderItem extends \app\components\TActiveRecord
{
	public  function __toString()
	{
		return (string)$this->quantity;
	}
public static function getProductOptions()
	{
		return ["TYPE1","TYPE2","TYPE3"];
		//return ArrayHelper::Map ( Product::findActive ()->all (), 'id', 'title' );

	}

			public static function getOrderOptions()
	{
		return ["TYPE1","TYPE2","TYPE3"];
		//return ArrayHelper::Map ( Order::findActive ()->all (), 'id', 'title' );

	}

				const STATE_INACTIVE 	= 0;
	const STATE_ACTIVE	 	= 1;
	const STATE_DELETED 	= 2;

	public static function getStateOptions()
	{
		return [
				self::STATE_INACTIVE		=> "New",
				self::STATE_ACTIVE 			=> "Active" ,
				self::STATE_DELETED 		=> "Archived",
		];
	}
	public function getState()
	{
		$list = self::getStateOptions();
		return isset($list [$this->state_id])?$list [$this->state_id]:'Not Defined';

	}
	public function getStateBadge()
	{
		$list = [
				self::STATE_INACTIVE 		=> "primary",
				self::STATE_ACTIVE 			=> "success" ,
				self::STATE_DELETED 		=> "danger",
		];
		return isset($list[$this->state_id])?\yii\helpers\Html::tag('span', $this->getState(), ['class' => 'label label-' . $list[$this->state_id]]):'Not Defined';
	}


		public static function getTypeOptions()
	{
		return ["TYPE1","TYPE2","TYPE3"];
		//return ArrayHelper::Map ( Type::findActive ()->all (), 'id', 'title' );

	}

	 	public function getType()
	{
		$list = self::getTypeOptions();
		return isset($list [$this->type_id])?$list [$this->type_id]:'Not Defined';

	}
				public function beforeValidate()
	{
		if($this->isNewRecord)
		{
				if ( !isset( $this->created_on )) $this->created_on = date( 'Y-m-d H:i:s');
				if ( !isset( $this->created_by_id )) $this->created_by_id = Yii::$app->user->id;
			}else{
					}
		return parent::beforeValidate();
	}


	/**
	* @inheritdoc
	*/
	public static function tableName()
	{
		return '{{%order_item}}';
	}

	/**
	* @inheritdoc
	*/
	public function rules()
	{
		return [
            [['quantity', 'product_id', 'order_id', 'created_on', 'created_by_id'], 'required'],
            [['quantity', 'product_id', 'order_id', 'delivery_status', 'state_id', 'type_id', 'created_by_id'], 'integer'],
            [['created_on'], 'safe'],
            [['price'], 'string', 'max' => 125],
            [['created_by_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['price'], 'trim'],
            [['state_id'], 'in', 'range' => array_keys(self::getStateOptions())],
            [['type_id'], 'in', 'range' => array_keys (self::getTypeOptions())]
        ];
	}

	/**
	* @inheritdoc
	*/


	public function attributeLabels()
	{
		return [
				    'id' => Yii::t('app', 'ID'),
				    'quantity' => Yii::t('app', 'Quantity'),
				    'price' => Yii::t('app', 'Price'),
				    'product_id' => Yii::t('app', 'Product'),
				    'order_id' => Yii::t('app', 'Order'),
				    'delivery_status' => Yii::t('app', 'Delivery Status'),
				    'state_id' => Yii::t('app', 'State'),
				    'type_id' => Yii::t('app', 'Type'),
				    'created_on' => Yii::t('app', 'Created On'),
				    'created_by_id' => Yii::t('app', 'Created By'),
				];
	}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCreatedBy()
    {
    	return $this->hasOne(User::className(), ['id' => 'created_by_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOrder()
    {
    	return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getProduct()
    {
    	return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
    public static function getHasManyRelations()
    {
    	$relations = [];
		return $relations;
	}
    public static function getHasOneRelations()
    {
    	$relations = [];
		$relations['created_by_id'] = ['createdBy','User','id'];
		$relations['order_id'] = ['order','Order','id'];
		$relations['product_id'] = ['product','Product','id'];
		return $relations;
	}

	public function beforeDelete() {
		return parent::beforeDelete ();
	}

    public function asJson($with_relations=false)
	{
		$json = [];
			$json['id'] 	= $this->id;
			$json['quantity'] 	= $this->quantity;
			$json['price'] 	= $this->price;
			$json['product_id'] 	= $this->product_id;
			$json['order_id'] 	= $this->order_id;
			$json['delivery_status'] 	= $this->delivery_status;
			$json['state_id'] 	= $this->state_id;
			$json['type_id'] 	= $this->type_id;
			$json['created_on'] 	= $this->created_on;
			$json['created_by_id'] 	= $this->created_by_id;
			if ($with_relations)
		    {
				// createdBy
				$list = $this->createdBy;

				if ( is_array($list))
				{
					$relationData = [];
					foreach( $list as $item)
					{
						$relationData [] 	= $item->asJson();
					}
					$json['createdBy'] 	= $relationData;
				}
				else
				{
					$json['CreatedBy'] 	= $list;
				}
				// order
				$list = $this->order;

				if ( is_array($list))
				{
					$relationData = [];
					foreach( $list as $item)
					{
						$relationData [] 	= $item->asJson();
					}
					$json['order'] 	= $relationData;
				}
				else
				{
					$json['Order'] 	= $list;
				}
				// product
				$list = $this->product;

				if ( is_array($list))
				{
					$relationData = [];
					foreach( $list as $item)
					{
						$relationData [] 	= $item->asJson();
					}
					$json['product'] 	= $relationData;
				}
				else
				{
					$json['Product'] 	= $list;
				}
			}
		return $json;
	}
	
	
}

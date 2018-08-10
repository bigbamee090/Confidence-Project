<?php
use app\components\useraction\UserAction;
use app\models\Company;
use app\models\OrderItem;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
/* @var $this yii\web\View */
/* @var $model app\models\Order */

/* $this->title = $model->label() .' : ' . $model->id; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Orders'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">

	<div class=" panel ">
		<div class=" panel-body ">
    <?php
    
    echo \app\components\TDetailView::widget([
        'id' => 'order-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'quantity',
            'price',
            [
                'attribute' => 'delivery_status',
                'format' => 'raw',
                'value' => $model->getDeliveryStatusBadge()
            ],
            [
                'attribute' => 'type_id',
                'value' => $model->getType()
            ],
            'created_on:datetime'
        ]
    ])?>

		</div>
	</div>



	<div class=" panel ">
		<div class=" panel-body ">
			<div class="order-panel">
				<h4>
					<strong>Delivery Address</strong>
				</h4>
				<?php
    $address = json_decode($model->delivery_address, true);
    
    ?>
    
    			<div id="default-address" class="checkout-address">
									<?php
        
        if (! empty($address)) {
            ?>
        
        	<div class="price-info product-border-bt ">
						<span class="pull-left"> Name </span> <span
							class="text-red pull-right"> <b> <?= $address['name'] ?> </b></span>
						<div class="clearfix"></div>
					</div>
					<div class="price-info product-border-bt ">
						<span class="pull-left"> Email </span> <span
							class="text-red pull-right"> <b> <?= $address['email'] ?> </b></span>
						<div class="clearfix"></div>
					</div>
					<div class="price-info product-border-bt ">
						<span class="pull-left"> Telephone </span> <span
							class="text-red pull-right"> <b> <?= $address['phone'] ?> </b></span>
						<div class="clearfix"></div>
					</div>
					<div class="price-info product-border-bt ">
						<span class="pull-left"> Address Line1 </span> <span
							class="text-red pull-right"> <b> <?= $address['address_line1'] ?> </b></span>
						<div class="clearfix"></div>
					</div>



					<div class="price-info product-border-bt ">
						<span class="pull-left"> Address Line2 </span> <span
							class="text-red pull-right"> <b> <?= $address['address_line2'] ?> </b></span>
						<div class="clearfix"></div>
					</div>
					<div class="price-info product-border-bt ">
						<span class="pull-left"> City </span> <span
							class="text-red pull-right"> <b> <?= $address['city'] ?> </b></span>
						<div class="clearfix"></div>
					</div>
					<div class="price-info product-border-bt ">
						<span class="pull-left"> Country </span> <span
							class="text-red pull-right"> <b> <?= in_array($address['country'], Company::getCountryOptions()) ? Company::getCountry() : $address['country'] ?> </b></span>
						<div class="clearfix"></div>
					</div>
					<div class="price-info product-border-bt ">
						<span class="pull-left"> Postal Code </span> <span
							class="text-red pull-right"> <b> <?= $address['pincode'] ?> </b></span>
						<div class="clearfix"></div>
					</div>
        
        <?php
        }
        ?>
							</div>


			</div>
		</div>
	</div>

	<div class=" panel ">
		<div class=" panel-body ">
			<div class="order-panel">
				<h4>
					<strong>Order Items</strong>
				</h4>
				<?php
    $query = OrderItem::find()->where([
        'order_id' => $model->id
    ]);
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => [
            'defaultOrder' => [
                'id' => SORT_DESC
            ]
        ]
    ]);
    ?>
    			<div id="default-address" class="checkout-address">
					<div class="product-list  white-background">
        				<?= ListView::widget(['dataProvider' => $dataProvider,'itemView' => '_list'])?>
        			</div>
				</div>
			</div>
		</div>
	</div>

</div>

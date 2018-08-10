<?php
use app\models\CartItem;
use app\modules\media\models\File;
use yii\helpers\Url;
?>
<?php

if (empty($model)) {
    echo '<p>No item find in your cart..</p>';
    return;
}
?>
<?php
$items = CartItem::findAll([
    'cart_id' => $model->id
]);
?>
<div class="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<ul>
					<li><a href="<?= Url::home() ?>">Home</a></li>
					<li class="active">Cart</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="shopping-cart m-t-40">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class=" m-b-30">
					<div class="shopping-cart-block panel-inner">
						<h4 class="filter-title">shopping cart</h4>
					</div>
					<div class="cart-table-view m-t-30">
						<table class="table table-bordered">
							<thead>
								<tr>

									<th><span>Item</span></th>
									<th><span>Price</span></th>
									<th><span>Quantity</span></th>
									<th><span>Total</span></th>
									<th><span>Action</span></th>
								</tr>
							</thead>
							<tbody>
								<?php
        foreach ($items as $item) {
            if (isset($item->product)) {
                ?>
								    <tr>
									<td><div class="item-img">
											<a
												href="<?= $item->product->getUrl('detail') ?>">
    												<?= File::getImage($item->product, ['alt' => 'Product Image'])?>
    											<p>
													<span><?= $item->product->title ?></span>
												</p>
											</a>
										</div></td>
									<td><span class="text-red product-price">$<?= $item->discounted_price ?></span></td>
									<td><input class="number product_quantity"
										value="<?= $item->quantity ?>" type="number"></td>
									<td><span class="text-red product-price">$<?= floatval($item->discounted_price) * floatval($item->quantity) ?></span></td>


									<td><a
										data-confirm="Are you sure you want to delete this item?"
										href="<?= Url::toRoute(['product/delete-cart-item', 'cartId' => $model->id, 'itemId' => $item->id]) ?>"
										class="dlt-btn"><i class="fa fa-times"></i></a></td>
								</tr>
								<?php } } ?>
							</tbody>
						</table>

					</div>
				</div>
				<div class="sub-total-block">
					<div class="m-b-30">
						<div class="panel">
							<div class="panel-heading">
								<h4>Total</h4>
							</div>
							<div class="panel-body">
								<div class="price-info product-border-bt ">
									<span class="pull-left"> Subtotal </span> <span
										class="text-red pull-right"> <b> $<?= $model->actual_price ?> </b></span>
									<div class="clearfix"></div>
								</div>
								<div class="price-info product-border-bt ">
									<span class="pull-left"> Discount </span> <span
										class="text-red pull-right"> <b> $<?= floatval( $model->actual_price ) - floatval( $model->discounted_price) ?> </b></span>
									<div class="clearfix"></div>
								</div>
								<div class="price-info product-border-bt ">
									<span class="pull-left"> Total price </span> <span
										class="text-red pull-right"> <b> $<?= $model->discounted_price ?> </b></span>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel">
					<div class="panel-body">
						<div class="checkout pull-right">
							<a href="<?= Url::toRoute(['site/checkout']) ?>"
								class="btn btn-default btn-lg round">Process checkout</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
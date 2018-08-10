<?php
use app\models\CartItem;
use app\modules\media\models\File;
use yii\helpers\Url;
use app\models\Company;
use app\components\TActiveForm;
use yii\helpers\Html;
use app\models\User;
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
<div class="">
	<div class="wrapper">
		<div class="shopping-cart clearfix">
			<div class="panel">
				<div class="panel-body">
					<div class="col-md-8">
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
													<a href="javascript:;">
    												<?=File::getImage($item->product, ['alt' => 'Product Image'])?>
    											<p>
															<span><a href="<?=$item->product->getUrl('detail')?>"><?=$item->product->title?></a></span>
														</p>
													</a>
												</div></td>
											<td><span class="text-red product-price">$<?=$item->discounted_price?></span></td>

											<td>

												<div class="text-center number">
													<a href="javascript:;"
														onclick="quantity('m', '<?=$item->id?>')"
														id="remove-quantity"> <i class="fa fa-minus"></i></a> <span>	<?=$item->quantity?></span>
													<a href="javascript:;"
														onclick="quantity('p', '<?=$item->id?>')"
														id="add-quantity"><i class="fa fa-plus"></i></a>
												</div>
											</td>


											<td><span class="text-red product-price">$<?=floatval($item->discounted_price) * floatval($item->quantity)?></span></td>


											<td><a
												data-confirm="Are you sure you want to delete this item?"
												href="<?=Url::toRoute(['product/delete-cart-item','cartId' => $model->id,'itemId' => $item->id])?>"
												class="dlt-btn"><i class="fa fa-times"></i></a></td>
										</tr>
								<?php
            }
        }
        ?>
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
												class="text-red pull-right"> <b> $<?=round($model->actual_price, 2)?> </b></span>
											<div class="clearfix"></div>
										</div>
										<div class="price-info product-border-bt ">
											<span class="pull-left"> Discount </span> <span
												class="text-red pull-right"> <b> $<?=round(floatval($model->actual_price) - floatval($model->discounted_price), 2)?> </b></span>
											<div class="clearfix"></div>
										</div>
										<div class="price-info product-border-bt ">
											<span class="pull-left"> Total price </span> <span
												class="text-red pull-right"> <b> $<?=round($model->discounted_price, 2)?> </b></span>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="m-b-30">
							<div class="shopping-cart-block panel-inner">
								<h4 class="filter-title">check out</h4>
							</div>
						</div>
						<div class="panel">
							<div class="panel-heading">
								<h4>Choose Your Payment Method</h4>
							</div>
							<div class="panel-body">
								<div class="checkout m-t-10">
									<div id="default-address" class="checkout-address">
									<?php
        $address = Company::getDeliveryAddress(true);
        
        if (! empty($address)) {
            ?>
        
        	<div class="price-info product-border-bt ">
											<span class="pull-left"> Name </span> <span
												class="text-red pull-right"> <b> <?=$address->name?> </b></span>
											<div class="clearfix"></div>
										</div>
										<div class="price-info product-border-bt ">
											<span class="pull-left"> Email </span> <span
												class="text-red pull-right"> <b> <?=$address->email?> </b></span>
											<div class="clearfix"></div>
										</div>
										<div class="price-info product-border-bt ">
											<span class="pull-left"> Telephone </span> <span
												class="text-red pull-right"> <b> <?=$address->phone?> </b></span>
											<div class="clearfix"></div>
										</div>
										<div class="price-info product-border-bt ">
											<span class="pull-left"> Address Line1 </span> <span
												class="text-red pull-right"> <b> <?=$address->address_line1?> </b></span>
											<div class="clearfix"></div>
										</div>



										<div class="price-info product-border-bt ">
											<span class="pull-left"> Address Line2 </span> <span
												class="text-red pull-right"> <b> <?=$address->address_line2?> </b></span>
											<div class="clearfix"></div>
										</div>
										<div class="price-info product-border-bt ">
											<span class="pull-left"> City </span> <span
												class="text-red pull-right"> <b> <?=$address->city?> </b></span>
											<div class="clearfix"></div>
										</div>
										<div class="price-info product-border-bt ">
											<span class="pull-left"> Country </span> <span
												class="text-red pull-right"> <b> <?=in_array($address->country, Company::getCountryOptions()) ? Company::getCountry() : $address->country?> </b></span>
											<div class="clearfix"></div>
										</div>
										<div class="price-info product-border-bt ">
											<span class="pull-left"> Postal Code </span> <span
												class="text-red pull-right"> <b> <?=$address->pincode?> </b></span>
											<div class="clearfix"></div>
										</div>
        
        <?php
        }
        ?>
							</div>
									<div id="select-new-address" class="clearfix checkout-address"
										style="display: none">
										<div class="form-group">
											<select id="address-dropdown" class="form-control mb-20">
								<?php
        
        $address = Company::getDeliveryAddress(false);
        if (! empty($address)) {
            foreach ($address as $add) {
                ?>
								    <option value="<?=$add->id?>"><?=$add->address_line1 . " ," . $add->city . " ," . $add->country . " ," . $add->pincode . " (" . $add->name . " - $add->email )"?></option>
								    <?php
            }
        }
        ?>
        </select> <a href="javascript:;"
												class="add-cart mt-10 pull-left"
												id="change-default-address">Change Address</a> <a
												href="javascript:;"
												class="add-cart mt-10 pull-right cancel-address">
												Cancel </a>
										</div>
									</div>

									<div id="add-new-address" class="clearfix checkout-address"
										style="display: none">
										<div class="form-group">
									<?php
        
        ?>
									<?php
        
        $form = TActiveForm::begin([
            'enableAjaxValidation' => true,
            'enableClientValidation' => true
        ]);
        ?>
									   
									<?=$form->field($deliveryAddress, 'name')->textInput()?>
									
									<?=$form->field($deliveryAddress, 'email')->textInput()?>
									
									<?=$form->field($deliveryAddress, 'phone')->textInput()?>
									
									<?=$form->field($deliveryAddress, 'address_line1')->textInput()?>
									
									<?=$form->field($deliveryAddress, 'address_line2')->textInput()?>
									
									<?=$form->field($deliveryAddress, 'city')->textInput()?>
									
									<?=$form->field($deliveryAddress, 'country')->dropDownList(Company::getCountryOptions())?>
									
									<?=$form->field($deliveryAddress, 'pincode')->textInput()?>
									  
									<?=Html::submitButton(Yii::t('app', 'Save New Address'), ['id' => 'delivery-form-submit','class' => 'add-cart pull-left'])?>
									   
									   <a href="javascript:;"
												class="add-cart pull-right cancel-address"> Cancel </a>
									   
									<?php
        
TActiveForm::end();
        ?>
								</div>
									</div>

									<hr>
									<div class="clearfix">
										<a href="javascript:;" id="change-address"><span
											class="pull-left add-cart"><i class="fa fa-edit"></i>Change Address</span></a>
										<a href="javascript:;" id="add-address"><span
											class="pull-right add-cart"><i class="fa fa-plus"></i>Add New Address</span></a>
									</div>
									<hr>

								</div>
							</div>
						</div>
						<!-- <div class="panel">
							<div class="panel-heading">
								<h4>Personal information</h4>
							</div>
							<div class="panel-body">
								<div class="checkout m-t-10">
									<form>
										<div class="form-group">
											<input class="form-control" placeholder="Name" type="email">
										</div>
										<div class="form-group">
											<input class="form-control" placeholder="Contact Number"
												type="password">
										</div>
										<div class="form-group">
											<input class="form-control" placeholder="Address" type="text">
										</div>
										<div class="form-group">
											<input class="form-control" placeholder="City/Town"
												type="text">
										</div>
										<div class="form-group">
											<input class="form-control" placeholder="State" type="text">
										</div>
									</form>
								</div>
							</div>
						</div> -->
						<div class="panel">
							<div class="panel-heading">
								<h4>Choose Your Payment Method</h4>
							</div>
							<div class="panel-body">
								<div class="checkout">
							<?php
    $form = TActiveForm::begin([
        'action' => [
            '/order/proceed'
        ],
        'enableAjaxValidation' => false,
        'enableClientValidation' => false
    ]);
    ?>
								<div class="checkout-radio m-b-10">
										<div class="radio m-r-5">
											<label> <input name="Order[type_id]" value="option1"
												checked="" type="radio"> Cash on Delivery
											</label>
										</div>

										<div class="radio">
											<label> <input name="Order[type_id]" value="option2"
												type="radio"> Paypal
											</label>
										</div>
									</div>

									
								
							<?php TActiveForm::end(); ?>
						</div>

										<button type="button" class="add-cart">Process
											checkout</button>
									</form>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
//Numeric only control handler
jQuery.fn.ForceNumericOnly =
function()
{
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
            // home, end, period, and numpad decimal
            return (
                key == 8 || 
                key == 9 ||
                key == 13 ||
                key == 46 ||
                key == 110 ||
                key == 190 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105));
        });
    });
};

$('.product_quantity').ForceNumericOnly();

$("#change-address").on("click", function () {
	$(".checkout-address").hide();
	$("#select-new-address").show();
});

$("#add-address").on("click", function () {
	$(".checkout-address").hide();
	$("#add-new-address").show();
});

$(".cancel-address").on("click", function () {
	$(".checkout-address").hide();
	$("#default-address").show();
});

$("#change-default-address").on("click", function () {
	var id = $("#address-dropdown").val();
	window.location.href = "<?=Url::toRoute(['/company/change-address'])?>?id="+id;
});

function quantity(q, itemId) {
	$.ajax({
		url : "<?=Url::toRoute(['product/quantity'])?>?q="+q+"&itemId="+itemId,
		type : "GET",
		success : function (response) {
			window.location.reload();
		}
	});
}
</script>
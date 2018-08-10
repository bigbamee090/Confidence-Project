<?php
use app\models\CartItem;
use app\models\Product;
use yii\helpers\Url;
use yii\widgets\ListView;

$params = \Yii::$app->request->get();
$count = CartItem::find()->where([
    'created_by_id' => \Yii::$app->user->id
])->count();
?>


<!-- start section -->
<section class="grey-background panel">
	<div class="panel-body">
		<div class="toolbar clearfix">
			<div class="row">
				<div class="col-sm-1 text-center">
					<input type="checkbox" class="all-product-checkbox">
				</div>
				<div class="col-sm-2">
					<a href="javascript:;" class="product-button" id="add-to-cart"> <i
						class="fa fa-shopping-cart"></i> Add to Cart <span>(<?= $count ?>)</span>
					</a>
				</div>
				<div class="col-sm-2">
					<a href="javascript:;" class="product-button" id="checkoutPage"> <i
						class="fa fa-sign-out-alt"></i> Checkout
					</a>
				</div>
				<div class="col-md-7">
				<form method="GET" id="product__Search">
					<div class="col-md-4 col-sm-4 col-xs-4">
						<div class="shop-short-by text-left">
							<div class="input-rule select">
								<div class="form-group clearfix">
									<label class="control-label col-md-4">Show:</label>
									<div class="col-md-8">
										<div class="icon-absolute">
											<i class="fa fa-angle-down"></i>
										</div>
										<div class="field-search-pagesize">
											<select id="search-pagesize"
												class="form-control search-tyre-dropdown" name="pageSize">
												<option value="10"
													<?= (isset($params['pageSize']) && ($params['pageSize'] == 10) ) ? 'selected' : '' ?>>10</option>
												<option value="20"
													<?= (isset($params['pageSize']) && ($params['pageSize'] == 20) ) ? 'selected' : '' ?>>20</option>
												<option value="50"
													<?= (isset($params['pageSize']) && ($params['pageSize'] == 50) ) ? 'selected' : '' ?>>50</option>
												<option value="100"
													<?= (isset($params['pageSize']) && ($params['pageSize'] == 100) ) ? 'selected' : '' ?>>100</option>
												<option value="200"
													<?= (isset($params['pageSize']) && ($params['pageSize'] == 200) ) ? 'selected' : '' ?>>200</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-8 col-sm-8 col-xs-8">
						<div class="shop-short-by text-right">
							<div class="input-rule select">
								<div class="form-group clearfix">
									<label class="control-label col-md-4">Sort by:</label>
									<div class="col-md-8">
										<div class="icon-absolute">
											<i class="fa fa-angle-down"></i>
										</div>
										<div class="field-search-sort_by has-success">
											<select id="search-sort_by"
												class="form-control search-tyre-dropdown" name="sortBy"
												aria-invalid="false">
												<option value="">----</option>
												<option value="<?= Product::SORT_PRICE_L ?>"
													<?= (isset($params['sortBy']) && ($params['sortBy'] == Product::SORT_PRICE_L) ) ? 'selected' : '' ?>>Price:
													Lowest first</option>
												<option value="<?= Product::SORT_PRICE_H ?>"
													<?= (isset($params['sortBy']) && ($params['sortBy'] == Product::SORT_PRICE_H) ) ? 'selected' : '' ?>>Price:
													Highest first</option>
												<option value="<?= Product::SORT_TITLE_A ?>"
													<?= (isset($params['sortBy']) && ($params['sortBy'] == Product::SORT_TITLE_A) ) ? 'selected' : '' ?>>Product
													Name: A to Z</option>
												<option value="<?= Product::SORT_TITLE_Z ?>"
													<?= (isset($params['sortBy']) && ($params['sortBy'] == Product::SORT_TITLE_Z) ) ? 'selected' : '' ?>>Product
													Name: Z to A</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
				</div>
			</div>

		</div>
		<div class="product-list  white-background">
			<?=ListView::widget(['dataProvider' => $dataProvider,'itemView' => '_list_view'])?>
		</div>
	</div>
</section>

<script>
$(".all-product-checkbox").on('click', function () {
	if( $(this).hasClass('checked') ) {
		$(this).removeClass('checked')
		$(".product-checkbox").prop("checked", false);
	} else {
		$(this).addClass('checked')
		$(".product-checkbox").prop("checked", true);
	}
});

$("#search-pagesize").on("change", function () {
	$("#product__Search").submit();
});

$("#search-sort_by").on("change", function () {
	$("#product__Search").submit();
});

$("#add-to-cart").on("click", function () {
	var ids = [];
	$('.product-list .product-checkbox').each(function () {
		  if( this.checked ) {
			  ids.push($(this).val());
		  }
	});
	if( ids.length > 0 ) {
		addToCart(ids);
	}
});

$(".add-cart").on("click", function () {
	addToCart($(this).attr('data-id'));
});

function addToCart(id) {
	$.ajax({
		url : "<?= Url::toROute(['product/add-cart']) ?>",
		type : "POST",
		dataType: 'json',
		data : {
			id : id,
			_csrf: yii.getCsrfToken()
		},
		success : function (response) {
			window.location.reload();
		}
	});
}

$("#checkoutPage").on("click", function () {
	if( <?= $count ?> <= 0 ) {
		alert("Please add product in your cart first.");
	} else {
		window.location.href = "<?= Url::toRoute(['product/checkout']) ?>";
	}
});
</script>
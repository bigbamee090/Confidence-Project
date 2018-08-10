<?php
use yii\helpers\Url;
use app\modules\media\models\File;
use yii\helpers\HtmlPurifier;
use app\models\User;
?>
<div class="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<ul>
					<li><a href="<?= Url::home() ?>">Home</a></li>
					<li><a
						href="<?= Url::toRoute(['product/list', 'id' => $model->category_id]) ?>">Products</a></li>
					<li class="active"><?= $model->title ?></li>
				</ul>
				<!-- end breadcrumb -->
			</div>
			<!-- end col -->
		</div>
		<!-- end row -->
	</div>
	<!-- end container -->
</div>

<section class="section white-backgorund">
	<div class="container">
		<div class="row">

			<div class="col-sm-3 ">
				<figure style="margin-top: 30px; padding: 30px;" class="treatments-img ">
					<?=File::getImage($model, ["style" => ""])?>
				</figure>
			</div>

			<div class="col-sm-9">
				<div class="row">
					<div class="col-sm-12">
						<h2 class="title"><?= $model->title ?></h2>
						<div class="badges">
							<p class="text-gray alt-font">In Stock</p>
						</div>

						<ul class="list list-inline">
							<li><h5 style="margin: 0px;" class="text-primary">$<?= $model->price ?></h5></li>


						</ul>
					</div>
				</div>

				<hr class="spacer-5">
				<hr class="spacer-10 no-border">

				<div class="row">
					<div class="col-sm-12">
						<p style="font-size: 14px;">
							<?= HtmlPurifier::process($model->description) ?>
						</p>

						<div class="row">
							<div class="col-sm-12 col-md-6">
								<dl class="dl-horizontal">
									<dt>Formulation</dt>
									<dd>Legal Category</dd>
									<dt>Cold Chain</dt>
									<dd>Yes</dd>



								</dl>
							</div>
							<div class="col-sm-12 col-md-6">
								<dl class="dl-horizontal">

									<dt>Inject</dt>
									<dd><?= $model->getClass() ?></dd>
									<dt>Avalability</dt>
									<dd>Instock</dd>


								</dl>
							</div>
						</div>

						<hr class="spacer-15">

						<ul class="list list-inline">
							<li><button type="button" id="add-to-cart"
									data-id="<?= $model->id ?>"
									class="btn btn-default btn-lg round">
									<i class="fa fa-shopping-basket mr-5"></i>Add to Cart
								</button></li>
							<li><button type="button" data-id="<?= $model->id ?>"
									id="add-to-wishlist" class="btn btn-gray btn-lg round">
									<i class="fa fa-heart mr-5"></i>Add to Wishlist
								</button></li>


						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
$("#add-to-cart").on("click", function () {

	<?php
if (User::isGuest()) {
    ?>
	    window.location.href = "<?=Url::toRoute(['user/login'])?>";
	<?php } else { ?>
	    
	addToCart($(this).attr("data-id"));
	<?php
}
?>
	
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

$("#add-to-wishlist").on("click", function () {
	<?php
if (User::isGuest()) {
    ?>
				    window.location.href = "<?=Url::toRoute(['user/login'])?>";
				<?php } else { ?>
				    
				addToWishList($(this).attr("data-id"));
				<?php
}
?>
});

function addToWishList(id) {
	$.ajax({
		url : "<?= Url::toROute(['product/add-wishlist']) ?>?id="+id,
		type : "GET",
		dataType: 'json',
		success : function (response) {
			window.location.reload();
		}
	});
}
</script>

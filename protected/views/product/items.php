<?php
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use app\models\User;
?>
<div class="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<ul>
					<li><a href="<?= Url::home() ?>">Home</a></li>
					<li class="active">Products</li>
				</ul>
				<!-- end breadcrumb -->
			</div>
			<!-- end col -->
		</div>
		<!-- end row -->
	</div>
	<!-- end container -->
</div>

<section class="section white-backgorund treatments products">
	<div class="container padding">
		<div class="row">
		
		<?php
Pjax::begin([
    'id' => 'status-list-view',
    'enablePushState' => true, // to disable push state
    'enableReplaceState' => true
]);
?>

			<div class="col-sm-12 mb-20">
				<h6 class="ml-5 mb-20 text-uppercase heading">
					<span class="text-primary hr-heading"><?= !empty($category) ? $category->title : '' ?> </span>
				</h6>
			</div>
 <?php
if (isset($dataProvider) && ! empty($dataProvider)) {
    echo ListView::widget([
        'id' => 'my-listview-id',
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        'layout' => '<div class="items clearfix">{items}</div><div class="clearfix">{pager}</div>'
    
    ]);
}
?>   
<?php

Pjax::end();
?>  
		
		
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

$(".add-to-wishlist").on("click", function () {
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
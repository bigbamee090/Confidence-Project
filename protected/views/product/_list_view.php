<?php
use yii\helpers\HtmlPurifier;
use app\modules\media\models\File;
?>
<div class="row pt-15">
	<div class="col-sm-1 text-center">
		<input type="checkbox" class="product-checkbox"
			value="<?=$model->id?>">
	</div>
	<div class="col-sm-2 text-center">
		<figure>
			<?=File::getImage($model, ['alt' => 'Product Image','width' => 50])?>
		</figure>
	</div>
	<div class="col-sm-9">
		<div class="row" style="margin-left: 10px;">
			<div class="col-sm-12">
				<h5 class="heading-para"><a href="<?=$model->getUrl('detail')?>"> <?=$model->title?></a> </h5>
				<div class="badges">
					<?=HtmlPurifier::process($model->description)?>
				</div>
				<ul class="list list-inline">
					<li><h5 style="margin: 0px;" class="text-primary">$<?=$model->getDiscountedPrice()?></h5></li>
				</ul>
				<a href="javascript:;" class="add-cart" data-id="<?=$model->id?>">
					<i class="fa fa-shopping-cart"></i> Add to Cart
				</a>
			</div>
		</div>
	</div>
</div>
<hr class="spacer-5">
<?php
use yii\helpers\HtmlPurifier;
use app\modules\media\models\File;
?>

<?php if( isset($model->product) ) { ?>

<div class="row pt-15">
	<div class="col-sm-2 text-center">
		<figure>
			<a href="<?= $model->product->getUrl('detail') ?>">	<?=File::getImage($model->product, ['alt' => 'Product Image'])?></a>
		</figure>
	</div>
	<div class="col-sm-9">
		<div class="row" style="margin-left: 10px;">
			<div class="col-sm-12">
				<a href="<?= $model->product->getUrl('detail') ?>"><h5> <?=$model->product->title?> </h5></a>
				<div class="badges">
					<?=HtmlPurifier::process($model->product->description)?>
				</div>
				<ul class="list list-inline">
					<li><h5 style="margin: 0px;" class="text-primary">$<?=$model->product->getDiscountedPrice()?></h5></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<hr class="spacer-5">
<?php } ?>
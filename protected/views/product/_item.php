<?php
use app\modules\media\models\File;
?>
<div class="col-sm-6 col-md-4 col-xs-12">
	<div class="thumbnail store style2">
		<div class="header">
			<div class="badges">
				<span
					class="product-badge top left danger-background text-white semi-circle">
					In Stock</span>
			</div>
			<figure class="layer">
				<a href="<?= $model->getUrl('detail') ?>"> 
					<?=File::getImage($model)?>
				</a>
			</figure>
			<div class="icons">
				<a class="icon add-to-wishlist" data-id="<?= $model->id ?>"
					href="javascript:;" data-toggle="tooltip" title="Add to wishlist">
					<i class="fa fa-heart-o"></i>
				</a> <a class="icon" href="<?= $model->getUrl('detail') ?>"
					data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>

			</div>
		</div>
		<div class="caption text-center">
			<h6 class="regular">
				<a href="<?= $model->getUrl('detail') ?>"><?= $model->title ?></a>
			</h6>
			<div class="price">
				<span class="amount text-primary">$<?= $model->price ?></span>
			</div>
			<a href="<?= $model->getUrl('detail') ?>"
				class="btn btn-default btn-md round">Buy</a>
		</div>
	</div>
</div>
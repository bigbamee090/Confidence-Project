<?php
use app\modules\media\models\File;
?>
<div class="col-md-4 mb-20">
	<div class="item">
		<div class="box">
			<div class="heding">
				<h3><?=$model->title?></h3>
			</div>
			<div class="product_img">

				<a href="<?= $model->getUrl('detail') ?>"> 
				
				<?=File::getImage($model, ["style" => "max-height: 252px; max-width: 370px;"])?>
    

				</a> <a href="<?= $model->getUrl('detail') ?>" class="Read">Read
					More <i class="fa fa-angle-double-right" aria-hidden="true"></i>
				</a>
			</div>
		</div>
	</div>
</div>
<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
	<a href="<?=Url::toRoute(['product/list','id' => $model->id])?>">
		<div class="icon-box">
			<div class="icon">
						<?php
    if (! empty($model->image_file)) {
        
        ?>
            		<?php
        echo Html::img([
            'category/thumbnail',
            'filename' => $model->image_file
        ])?><br /> <br />
				<p></p>
                   			 <?php
    } else {
        ?>
						
							<img
					src="<?=$this->theme->getUrl('frontend/')?>img/pharmacist1.jpg">
								<?php
    }
    ?>
						</div>
			<h4><?=$model->title?></h4>
		</div>
	</a>
</div>
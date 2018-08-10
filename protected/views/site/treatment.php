<?php
use yii\helpers\Url;
?>
<div class="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<ul>
					<li><a href="<?= Url::home() ?>">Home</a></li>
					<li class="active">Treatment</li>
				</ul>

			</div>

		</div>

	</div>
</div>
<section class="categories-icon section-padding bg-drack">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
				<a href="<?= Url::toRoute(['site/treatment-detail']) ?>">
					<div class="treatment-div">
						<img src="<?= $this->theme->getUrl('frontend/')?>img/Botox Treatments.jpeg">
						<h4>Botox Treatments</h4>
					</div>
				</a>
			</div>

			<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
				<a href="<?= Url::toRoute(['site/treatment-detail']) ?>">
					<div class="treatment-div">
						<img src="<?= $this->theme->getUrl('frontend/')?>img/Dermal-Filler.jpg">
						<h4>Dermal Filler Treatments</h4>
					</div>
				</a>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
				<a href="<?= Url::toRoute(['site/treatment-detail']) ?>">
					<div class="treatment-div">
						<img src="<?= $this->theme->getUrl('frontend/')?>img/c700x420.jpg">
						<h4>Botox Treatments</h4>
					</div>
				</a>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
				<a href="<?= Url::toRoute(['site/treatment-detail']) ?>">
					<div class="treatment-div">
						<img src="<?= $this->theme->getUrl('frontend/')?>img/image-24-lipo-and-correct-diet.jpg">
						<h4>Non surgical liposuction</h4>
					</div>
				</a>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
				<a href="<?= Url::toRoute(['site/treatment-detail']) ?>">
					<div class="treatment-div">
						<img src="<?= $this->theme->getUrl('frontend/')?>img/VV_Treatment-Images_01.jpg">
						<h4>Silhouette Soft Treatment</h4>
					</div>
				</a>

			</div>

		</div>
	</div>
</section>
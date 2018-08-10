<?php
use Yii\helpers\Url;
?>
<div class="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<ul>
					<li><a href="<?= Url::home() ?>">Home</a></li>
					<li class="active">About Us</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<section class="section bg-gray treats">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 mb-20">
				<h6 class="ml-5 mb-20 text-uppercase heading">
					<span class="text-primary hr-heading"> About </span> Us
				</h6>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-7 ">
				<h2 class="title  heading-text">Who We are</h2>
				<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem
					accusantium doloremque laudantium, totam rem aperiam, eaque ipsa
					quae ab illo inventore veritatis et quasi architecto beatae vitae
					dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit
					aspernatur aut odit aut fugit, sed quia consequuntur magni dolores
					eos qui ratione voluptatem sequi nesciunt.</p>
				<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem
					accusantium doloremque laudantium, totam rem aperiam, eaque ipsa
					quae ab illo inventore veritatis et quasi architecto beatae vitae
					dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit
					aspernatur aut odit aut fugit, sed quia consequuntur magni dolores
					eos qui ratione voluptatem sequi nesciunt.</p>
				<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem
					accusantium doloremque laudantium, totam rem aperiam, eaque ipsa
					quae ab illo inventore veritatis et quasi architecto beatae vitae
					dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit
					aspernatur aut odit aut fugit, sed quia consequuntur magni dolores
					eos qui ratione voluptatem sequi nesciunt.</p>
			</div>
			<div class="col-sm-5 mrg-top">
				<figure class="zoom-in">
					<img src="<?= $this->theme->getUrl('frontend/')?>img/about.jpg" alt="">
				</figure>
			</div>
		</div>
	</div>
</section>
<section class="section white-backgorund">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="title-wrap">
					<h2 class="title">Our Team</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-md-4 col-lg-3">
				<div class="member zoom-in">
					<figure>
						<img src="<?= $this->theme->getUrl('frontend/')?>img/team/team_01.jpg" alt="">
						<ul class="social-icons style1">
							<li class="facebook"><a href="#" data-toggle="tooltip"
								data-placement="top" title="" data-original-title="Facebook"><i
									class="fa fa-facebook"></i></a></li>
							<li class="twitter"><a href="#" data-toggle="tooltip"
								data-placement="top" title="" data-original-title="Twitter"><i
									class="fa fa-twitter"></i></a></li>
							<li class="instagram"><a href="#" data-toggle="tooltip"
								data-placement="top" title="" data-original-title="Instagram"><i
									class="fa fa-instagram"></i></a></li>
						</ul>
					</figure>
					<div class="member-content">
						<h5 class="name regular">John Doe</h5>
						<h6 class="position thin">Co-Founder, CEO</h6>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4 col-lg-3">
				<div class="member zoom-in">
					<figure>
						<img src="<?= $this->theme->getUrl('frontend/')?>img/team/team_02.jpg" alt="">
						<ul class="social-icons style1">
							<li class="facebook"><a href="#" data-toggle="tooltip"
								data-placement="top" title="" data-original-title="Facebook"><i
									class="fa fa-facebook"></i></a></li>
							<li class="twitter"><a href="#" data-toggle="tooltip"
								data-placement="top" title="" data-original-title="Twitter"><i
									class="fa fa-twitter"></i></a></li>
							<li class="instagram"><a href="#" data-toggle="tooltip"
								data-placement="top" title="" data-original-title="Instagram"><i
									class="fa fa-instagram"></i></a></li>
						</ul>
					</figure>
					<div class="member-content">
						<h5 class="name regular">John Doe</h5>
						<h6 class="position thin">Marketing Director</h6>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4 col-lg-3">
				<div class="member zoom-in">
					<figure>
						<img src="<?= $this->theme->getUrl('frontend/')?>img/team/team_03.jpg" alt="">
						<ul class="social-icons style1">
							<li class="facebook"><a href="#" data-toggle="tooltip"
								data-placement="top" title="" data-original-title="Facebook"><i
									class="fa fa-facebook"></i></a></li>
							<li class="twitter"><a href="#" data-toggle="tooltip"
								data-placement="top" title="" data-original-title="Twitter"><i
									class="fa fa-twitter"></i></a></li>
							<li class="instagram"><a href="#" data-toggle="tooltip"
								data-placement="top" title="" data-original-title="Instagram"><i
									class="fa fa-instagram"></i></a></li>
						</ul>
					</figure>
					<div class="member-content">
						<h5 class="name regular">Jane Doe</h5>
						<h6 class="position thin">Sales Director</h6>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4 col-lg-3">
				<div class="member zoom-in">
					<figure>
						<img src="<?= $this->theme->getUrl('frontend/')?>img/team/team_04.jpg" alt="">
						<ul class="social-icons style1">
							<li class="facebook"><a href="#" data-toggle="tooltip"
								data-placement="top" title="" data-original-title="Facebook"><i
									class="fa fa-facebook"></i></a></li>
							<li class="twitter"><a href="#" data-toggle="tooltip"
								data-placement="top" title="" data-original-title="Twitter"><i
									class="fa fa-twitter"></i></a></li>
							<li class="instagram"><a href="#" data-toggle="tooltip"
								data-placement="top" title="" data-original-title="Instagram"><i
									class="fa fa-instagram"></i></a></li>
						</ul>
					</figure>
					<div class="member-content">
						<h5 class="name regular">John Doe</h5>
						<h6 class="position thin">Director</h6>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
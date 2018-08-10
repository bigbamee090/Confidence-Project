<?php
use Yii\helpers\Url;
?>
<footer class="footer">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<h5 class="title">Pharmacy</h5>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin
					suscipit, libero a molestie consectetur, sapien elit lacinia mi.</p>

				<hr class="spacer-10 no-border">

				<ul class="social-icons">
					<li class="facebook"><a href="javascript:void(0);"><i
							class="fa fa-facebook"></i></a></li>
					<li class="twitter"><a href="javascript:void(0);"><i
							class="fa fa-twitter"></i></a></li>
					<li class="dribbble"><a href="javascript:void(0);"><i
							class="fa fa-dribbble"></i></a></li>
					<li class="linkedin"><a href="javascript:void(0);"><i
							class="fa fa-linkedin"></i></a></li>
					<li class="youtube"><a href="javascript:void(0);"><i
							class="fa fa-youtube"></i></a></li>
					<li class="behance"><a href="javascript:void(0);"><i
							class="fa fa-behance"></i></a></li>
				</ul>
			</div>
			<div class="col-sm-3">
				<h5 class="title">My Account</h5>
				<ul class="list alt-list">
					<li><a href="<?= Url::toRoute(['site/myaccount']) ?>"><i class="fa fa-angle-right"></i>My Account</a></li>
					<li><a href="<?= Url::toRoute(['site/wishlist']) ?>"><i class="fa fa-angle-right"></i>Wishlist</a></li>
					<li><a href="<?= Url::toRoute(['site/cart']) ?>"><i class="fa fa-angle-right"></i>My Cart</a></li>
					<li><a href="<?= Url::toRoute(['site/checkout']) ?>"><i class="fa fa-angle-right"></i>Checkout</a></li>
				</ul>
			</div>
			<div class="col-sm-3">
				<h5 class="title">Information</h5>
				<ul class="list alt-list">
					<li><a href="<?= Url::toRoute(['site/about']) ?>"><i class="fa fa-angle-right"></i>About Us</a></li>
					<li><a href="<?= Url::toRoute(['/faq']) ?>"><i class="fa fa-angle-right"></i>FAQ</a></li>
					<li><a href="<?= Url::toRoute(['site/policy']) ?>"><i class="fa fa-angle-right"></i>Privacy Policy</a></li>
					<li><a href="<?= Url::toRoute(['site/contact']) ?>"><i class="fa fa-angle-right"></i>Contact Us</a></li>
				</ul>
			</div>
			<div class="col-sm-3">
				<h5 class="title">Payment Methods</h5>
				<p>Lorem Ipsum is simply dummy text of the printing and typesetting
					industry.</p>
				<ul class="list list-inline">
					<li class="text-white"><i class="fa fa-cc-visa fa-2x"></i></li>
					<li class="text-white"><i class="fa fa-cc-paypal fa-2x"></i></li>
					<li class="text-white"><i class="fa fa-cc-mastercard fa-2x"></i></li>
					<li class="text-white"><i class="fa fa-cc-discover fa-2x"></i></li>
				</ul>
			</div>
		</div>
		<hr class="spacer-30">
		<div class="row text-center">
			<div class="col-sm-12">
				<p class="text-sm">
					© 2018 Confidence Pharmacy All Rights Reserved </a>
				</p>
			</div>
		</div>
	</div>
</footer>
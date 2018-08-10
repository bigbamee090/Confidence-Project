<?php
use Yii\helpers\Url;
use app\models\CartItem;
use app\models\User;
use app\modules\media\models\File;
use app\models\Wishlist;
?>
<div class="topBar inverse">
	<div class="container">
		<ul class="list-inline pull-left hidden-sm hidden-xs">
			<li><i class="fa fa-phone mr-5"></i>+123 4567 8910</li>
			<li><i class="fa fa-envelope mr-5"></i>ConfidencePharmacy@domain.com</li>
		</ul>

		<ul class="topBarNav pull-right">

			<li class="linkdown"><a href="javascript:void(0);"> <img
					src="<?= $this->theme->getUrl('frontend/')?>img/flags/flag-french.jpg"
					class="mr-5" alt=""> <span class="hidden-xs"> French <i
						class="fa fa-angle-down ml-5"></i>
				</span>
			</a>
				<ul class="w-100">
					<li><a href="javascript:void(0);"><img
							src="<?= $this->theme->getUrl('frontend/')?>img/flags/flag-english.jpg"
							class="mr-5" alt="">English</a></li>
					<li class="active"><a href="javascript:void(0);"><img
							src="<?= $this->theme->getUrl('frontend/')?>img/flags/flag-french.jpg"
							class="mr-5" alt="">French</a></li>
					<li><a href="javascript:void(0);"><img
							src="<?= $this->theme->getUrl('frontend/')?>img/flags/flag-german.jpg"
							class="mr-5" alt="">German</a></li>
					<li><a href="javascript:void(0);"><img
							src="<?= $this->theme->getUrl('frontend/')?>img/flags/flag-spain.jpg"
							class="mr-5" alt="">Spain</a></li>
				</ul></li>
			<li class="linkdown"><a href="javascript:void(0);"> <i
					class="fa fa-user mr-5"></i> <span class="hidden-xs"> My Account <i
						class="fa fa-angle-down ml-5"></i>
				</span>
			</a>
				<ul class="w-150">
					
					<?php if ( User::isGuest() ) { ?>
						<li><a href="<?= Url::toRoute(['/site/register']) ?>">Create
							Account</a></li>
					<?php } else { ?>
						<li><a href="<?= Url::toRoute(['/site/index']) ?>">Profile</a></li>
					<li><a href="<?= Url::toRoute(['/site/checkout']) ?>">Checkout</a></li>
					<?php } ?>
				</ul></li>
				
				
				<?php if ( !User::isGuest() ) { ?>
				
				
			<li class="linkdown"><a href="javascript:void(0);"> <i
					class="fa fa-shopping-basket mr-5"></i> <span class="hidden-xs">
						Cart<sup class="text-primary">(<?=CartItem::find()->where(['created_by_id' => \Yii::$app->user->id])->count();?>)</sup>
						<i class="fa fa-angle-down ml-5"></i>
				</span>
			</a>
				<ul class="cart w-250">
					<li>
						<div class="cart-items">
							<ol class="items">
						<?php
        $items = CartItem::find()->where([
            'created_by_id' => \Yii::$app->user->id
        ])->all();
        
        if (! empty($items)) {
            foreach ($items as $item) {
                if (isset($item->product)) {
                    ?>
                <li><a href="<?=$item->product->getUrl('detail') ?>"
									class="product-image">
										<?= File::getImage($item->product, ['alt' => 'Product Image']) ?>
										
								</a>
									<div class="product-details">
										<div class="close-icon">
											<a
												href="<?= Url::toRoute(['product/delete-cart-item', 'cartId' => $item->cart_id, 'itemId' => $item->id]) ?>"><i
												class="fa fa-close"></i></a>
										</div>
										<p class="product-name">
											<a href="<?=$item->product->getUrl('detail') ?>"><?= $item->product->title ?></a>
										</p>
										<strong class="black"><?= $item->quantity ?></strong> <span
											class="black"> x </span> <span class="price text-primary">$<?= $item->discounted_price ?></span>
									</div> <!-- end product-details --></li>
                
           <?php
                }
            }
        }
        ?>
							</ol>
						</div>
					</li>
					<li>
						<div class="cart-footer">
							<a href="<?= Url::toRoute(['site/cart']) ?>" class="pull-left"><i
								class="fa fa-cart-plus mr-5"></i>View Cart</a> <a
								href="<?= Url::toRoute(['site/checkout']) ?>" class="pull-right"><i
								class="fa fa-shopping-basket mr-5"></i>Checkout</a>
						</div>
					</li>
				</ul></li>
				
				<?php } ?>
				
		</ul>
	</div>
	<!-- end container -->
</div>
<!-- end topBar -->

<div class="middleBar">
	<div class="container">
		<div class="row display-table">
			<div class="col-sm-3 vertical-align text-left hidden-xs">
				<a href="<?= Url::home() ?>"> <img width="160"
					src="<?= $this->theme->getUrl('frontend/')?>img/logo.jpg" alt="" />
				</a>
			</div>
			<div class="col-sm-7 vertical-align text-center hidden-xs">
				<form>
					<div class="row grid-space-1">
						<div class="col-sm-8 hidden-xs">
							<div class="search-field">
								<form>
									<input placeholder="search ...." type="text">
									<button type="submit">
										<i class="fa fa-search"></i>
									</button>
								</form>
							</div>
						</div>
						<?php if ( User::isGuest() ) { ?>
    						<div class="col-sm-2">
    							<a href="<?= Url::toRoute(['site/register']) ?>"
    								class="btn btn-light semi-circle btn-md">Register</a>
    						</div>
    						<div class="col-sm-2">
    							<a href="<?= Url::toRoute(['user/login']) ?>"
    								class="btn btn-light semi-circle btn-md">Login</a>
    						</div>
						<?php } ?>
					</div>
				</form>
			</div>
			<div class="col-sm-2 vertical-align header-items hidden-xs">
    			<?php if ( !User::isGuest() ) { ?>
    				<div class="header-item mr-5">
    					<a href="<?= Url::toRoute(['site/wishlist']) ?>"
    						data-toggle="tooltip" data-placement="top" title="Wishlist"> <i
    						class="fa fa-heart-o"></i> <sub><?= Wishlist::find()->where(['created_by_id' => \Yii::$app->user->id])->count() ?></sub>
    					</a>
    				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>


<div class="navbar yamm navbar-default">
	<div class="container">
		<div class="navbar-header">
			<button type="button" data-toggle="collapse"
				data-target="#navbar-collapse-3" class="navbar-toggle">
				<span class="icon-bar"></span> <span class="icon-bar"></span> <span
					class="icon-bar"></span>
			</button>
			<a href="<?= Url::home() ?>" class="navbar-brand visible-xs"> <img
				src="<?= $this->theme->getUrl('frontend/')?>img/logo.jpg" alt="logo"
				style="width: 150px;">
			</a>
		</div>
		<div id="navbar-collapse-3" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li><a href="<?= Url::home() ?>">Home</a></li>
				<li><a href="<?= Url::toRoute(['site/shop']) ?>">Shop</a></li>
				<!-- 	<li class="dropdown left"><a href="#" data-toggle="dropdown"
						class="dropdown-toggle">e-Pharmacy<i
							class="fa fa-angle-down ml-5"></i></a>
						<ul class="dropdown-menu">
							<li><a href="#">Welcome to your e-Pharmacy</a></li>
							<li><a href="#">FAQs</a></li>
							<li><a href="resources.html">Resources</a></li>
						</ul> 
					</li> -->
				<li><a href="<?= Url::toRoute(['site/about']) ?>">About Us</a></li>
				<li><a href="<?= Url::toRoute(['product/items']) ?>">Products</a></li>
				<li><a href="<?= Url::toRoute(['site/treatment']) ?>">Treatments</a></li>
				<li><a href="<?= Url::toRoute(['site/traning']) ?>">Trainings</a></li>
				<li><a href="<?= Url::toRoute(['site/delivery']) ?>">Delivery</a></li>
				<li class="hidden-lg hidden-sm hidden-md"><a target="_blank"
					href="<?= Url::toRoute(['site/register']) ?>"
					class="btn btn-light semi-circle btn-md r-button">Register</a></li>
				<li class="hidden-lg hidden-sm hidden-md"><a target="_blank"
					href="<?= Url::toRoute(['user/login']) ?>"
					class="btn btn-light semi-circle btn-md r-button">Login</a></li>
			</ul>
		</div>
	</div>
</div>
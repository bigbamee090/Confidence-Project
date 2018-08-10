<?php
use app\assets\AppAsset;
use app\components\FlashMessage;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\Menu;
use app\modules\shadow\models\Shadow;
use app\models\CartItem;
use app\modules\media\models\File;

$user = Yii::$app->user->identity;

/* @var $this \yii\web\View */
/* @var $content string */
// $this->title = yii::$app->name;

AppAsset::register($this);
?>
<?php

$this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
<head>
<meta charset="<?=Yii::$app->charset?>" />
    <?=Html::csrfMetaTags()?>
    <title><?=Html::encode($this->title)?></title>
    <?php
    
    $this->head()?>
    <meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


<!--common style-->
<!--common style-->

<link href="<?php

echo $this->theme->getUrl('css/style-admin.css')?>"
	rel="stylesheet">
<link
	href="<?php

echo $this->theme->getUrl('css/style-responsive.css')?>"
	rel="stylesheet">
<link rel="stylesheet" type="text/css"
	href="<?=$this->theme->getUrl('frontend/')?>css/font-awesome.min.css" />
<link
	href="<?php

echo $this->theme->getUrl('css/layout-theme-blue.css')?>"
	rel="stylesheet">


<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
<body class="sticky-header theme-<?=Yii::$app->view->theme->style?>">
<?php

$this->beginBody();

if (! User::isGuest()) :
    ?>

    <section>
		<!-- sidebar left start-->
		<div class="sidebar-left">
			<!--responsive view logo start-->
			<div class="logo theme-logo-bg visible-xs-* visible-sm-*">
				<a href="<?php
    
    echo Url::home();
    ?>"> <span class="brand-name"><?=Html::encode(\yii::$app->name)?></span>
				</a>
			</div>
			<!--responsive view logo end-->

			<div class="sidebar-left-info">
				<!-- visible small devices start-->
				<div class=" search-field"></div>
				<!-- visible small devices start-->

				<!--sidebar nav start-->
		<?php
    
    if (method_exists($this->context, 'renderNav')) {
        ?>
			<?=Menu::widget(['encodeLabels' => false,'activateParents' => true,'items' => $this->context->renderCompanyNav(),'options' => ['class' => 'nav  nav-stacked side-navigation'],'submenuTemplate' => "\n<ul class='child-list'>\n{items}\n</ul>\n"]);?>
	<?php
    }
    ?>
		<!--sidebar nav end-->

			</div>
		</div>
		<!-- sidebar left end-->

		<!-- body content start-->
		<div class="body-content">

			<!-- header section start-->
			<div class="header-section light-color">

				<!--logo and logo icon start-->
				<div class="logo theme-logo-bg hidden-xs hidden-sm">
					<a href="<?=Url::toRoute(['/site/company-index']);?>"> <span
						class="brand-name">
						<?php
    $user = \Yii::$app->user->identity;
    if (User::isCompanyAdmin() && isset($user->companyAdmin) && isset($user->companyAdmin->company)) {
        echo $user->companyAdmin->company->company_name;
    } else if (User::isCompanyManager() && isset($user->companyAdmin) && isset($user->companyAdmin->company)) {
        echo $user->companyAdmin->company->company_name;
    } elseif (User::isCompanyPrescriber() && isset($user->companyPrescriber) && isset($user->companyPrescriber->company)) {
        echo $user->companyPrescriber->company->company_name;
    } else {
        echo Html::encode(\yii::$app->name);
    }
    ?>
					</span>
					</a>
				</div>


				<!--logo and logo icon end-->

				<!--toggle button start-->
				<a class="toggle-btn"><i class="fa fa-outdent"></i></a>
				<!--toggle button end-->

				<!--mega menu start-->

				<!--mega menu end-->
				<div class="notification-wrap">



					<!--right notification start-->
					<div class="right-notification">
						<ul class="notification-menu">

							<li><a href="javascript:;"
								class="btn btn-default dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-shopping-basket mr-5"></i><span>Cart</span> <span
									class=" fa fa-angle-down"></span>
									(<?=CartItem::find()->where(['created_by_id' => \Yii::$app->user->id])->count();?>)
								</a>
								<ul class="dropdown-menu dropdown-usermenu cart w-250">
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
                <li><a href="<?=$item->product->getUrl('detail')?>"
													class="product-image">
										<?=File::getImage($item->product, ['alt' => 'Product Image'])?>
										
								</a>
													<div class="product-details">
														<div class="close-icon">
															<a
																href="<?=Url::toRoute(['product/delete-cart-item','cartId' => $item->cart_id,'itemId' => $item->id])?>"><i
																class="fa fa-close"></i></a>
														</div>
														<p class="product-name">
															<a href="<?=$item->product->getUrl('detail')?>"><?=$item->product->title?></a>
														</p>
														<strong class="black"><?=$item->quantity?></strong> <span
															class="black"> x </span> <span class="price text-primary">$<?=$item->discounted_price?></span>
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
											<a href="<?=Url::toRoute(['site/cart'])?>"
												class="pull-left"><i class="fa fa-cart-plus mr-5"></i>View
												Cart</a> <a href="<?=Url::toRoute(['product/checkout'])?>"
												class="pull-right"><i class="fa fa-shopping-basket mr-5"></i>Checkout</a>
										</div>
									</li>
								</ul></li>

							<li><a><span class="label label-primary" role="version"> Version <?=VERSION?></span></a></li>

							<!-- <div class="navbar-text">
							  /* echo \lajax\languagepicker\widgets\LanguagePicker::widget ( [
							 		'skin' => \lajax\languagepicker\widgets\LanguagePicker::SKIN_BUTTON,
							 		'size' => \lajax\languagepicker\widgets\LanguagePicker::SIZE_LARGE

							 ]);
							 </div> -->
							<li><a href="javascript:;"
								class="btn btn-default dropdown-toggle" data-toggle="dropdown">

								<?php
    $url = $this->theme->getUrl('/');
    
    if (! empty(\Yii::$app->user->identity->profile_file)) {
        
        ?>       
        
				<?php
        echo Html::img([
            'profile-image'
        
        ], [
            'width' => 50
        ])?>
				
   			 	
   			 	<?php
    } else {
        
        ?>
  
    <img
    src="<?php
        
        echo $url?>img/default.png">
  
  
<?php
    }
    ?>		
								
                                <?php
    
    echo Yii::$app->user->identity->full_name;
    ?>
                                <span class=" fa fa-angle-down"></span>
							</a>
								<ul class="dropdown-menu dropdown-usermenu purple pull-right">
									<li><a
										href="<?php
    
    echo Url::toRoute([
        '/user/view',
        'id' => Yii::$app->user->id
    ]);
    ?>"> <i class="fa fa-user pull-right"></i> Profile
									</a></li>
									<li><a
										href="<?php
    
    echo Url::toRoute([
        '/user/changepassword',
        'id' => Yii::$app->user->id
    ]);
    ?>"> <span class="fa fa-key pull-right"></span> <span>Change
												Password</span>
									</a></li>
									<li><a
										href="<?php
    
    echo Url::toRoute([
        '/user/update',
        'id' => Yii::$app->user->id
    ]);
    ?>"> <span class="fa fa-pencil pull-right"></span> Update
									</a></li>
									<li><a
										href="<?php
    
    echo Url::toRoute([
        '/user/logout'
    ]);
    ?>"> <i class="fa fa-sign-out pull-right"></i> Log Out
									</a></li>
								</ul></li>


						</ul>

					</div>
					<!--right notification end-->
				</div>


			</div>

			<!-- header section end-->

			<!-- page head start-->
			 <?=Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []])?>
			<!--body wrapper start-->
			<section class="main_wrapper">
					<?php
    if (yii::$app->hasModule('shadow')) {
        echo app\modules\shadow\components\ShadowWidget::widget();
    }
    ?>
			   <?=FlashMessage::widget(['type' => 'default' /* 'position' => 'bottom-right' */])?>
			   			   
               <?=$content;?>



			</section>

			<footer>

				<div class="text-center">
					<p target="_blank">
					<?php
    
    echo ' &copy; ' . date('Y') . ' ' . Yii::$app->name . ' | All Rights Reserved ';
    ?></p>

				</div>


			</footer>
			<!--footer section start-->
			<!--footer section end-->
			<!--body wrapper end-->
		</div>


		<!-- body content end-->

	</section>


	<!--common scripts for all pages-->
	<script
		src="<?php
    
    echo $this->theme->getUrl('js/scripts.js')?>"></script>

	<script
		src="<?php
    
    echo $this->theme->getUrl('js/custom-modal.js')?>"></script>




	<script type="text/javascript">
$(document).ready(function(){
	$(".child-list").find('span').contents().unwrap();
});
</script>



<?php
endif;

$this->endBody();
?>


</body>

<?php

$this->endPage()?>

</html>


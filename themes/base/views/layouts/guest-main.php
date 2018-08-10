<?php
use app\assets\AppAsset;
use app\components\FlashMessage;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$this->title = yii::$app->name;
AppAsset::register($this);
?>
<?php

$this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport"
	content="width=device-width,initial-scale=1,maximum-scale=1">
<meta charset="<?=Yii::$app->charset?>" />
    <?=Html::csrfMetaTags()?>
    <title><?=Html::encode($this->title)?></title>
    <?php
    
$this->head()?>

<link rel="stylesheet" type="text/css"
	href="<?=$this->theme->getUrl('frontend/')?>css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css"
	href="<?=$this->theme->getUrl('frontend/')?>css/owl.carousel.min.css" />

<link rel="stylesheet" type="text/css"
	href="<?=$this->theme->getUrl('frontend/')?>css/animate.css" />

<link rel="stylesheet" type="text/css"
	href="<?=$this->theme->getUrl('frontend/')?>css/responsive.css" />
<link id="pagestyle" rel="stylesheet" type="text/css"
	href="<?=$this->theme->getUrl('frontend/')?>css/default.css" />

<!-- Google fonts -->
<link
	href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i&amp;subset=cyrillic,cyrillic-ext,latin-ext"
	rel="stylesheet">

<link
	href="https://fonts.googleapis.com/css?family=Dosis:200,300,400,500,600,700,800&amp;subset=latin-ext"
	rel="stylesheet">

<!-- JS -->
<script type="text/javascript"
	src="<?=$this->theme->getUrl('frontend/')?>js/owl.carousel.min.js"></script>

<script type="text/javascript"
	src="<?=$this->theme->getUrl('frontend/')?>js/jquery.sticky.js"></script>

<script type="text/javascript"
	src="<?=$this->theme->getUrl('frontend/')?>js/main.js"></script>
	<script type="text/javascript"
	src="<?=$this->theme->getUrl('frontend/')?>js/jquery.downCount.js"></script>
	<script type="text/javascript"
	src="<?=$this->theme->getUrl('frontend/')?>js/nouislider.min.js"></script>
		<script type="text/javascript"
	src="<?=$this->theme->getUrl('frontend/')?>js/jquery.sticky.js"></script>

</head>
<body class="sticky-header theme-<?=Yii::$app->view->theme->style?>">
<?php

$this->beginBody()?>

 	<?=$this->render('header');?>

	<!-- body content start-->

	<div class="main_wrapper">
				 <?=FlashMessage::widget()?>
                 <?=$content?>
          </div>

	<!--body wrapper end-->
	<?=$this->render('footer');?>


	<!-- body content end-->


	
<?php

$this->endBody()?>


</body>


<?php

$this->endPage()?>

</html>

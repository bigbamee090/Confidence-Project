<?php
use yii\widgets\ListView;
use yii\widgets\Pjax;
?>

<?php
use Yii\helpers\Url;
?>
<div class="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<ul>
					<li><a href="<?=Url::home()?>">Home</a></li>
					<li><a href="shop.html">Shop</a></li>
					<li class="active"> <?= !empty($category) ? $category->title : '' ?> </li>
				</ul>
			</div>
		</div>
	</div>
</div>



<?php
Pjax::begin([
    'id' => 'status-list-view',
    'enablePushState' => true, // to disable push state
    'enableReplaceState' => true
]);
?>

<section class="section light-backgorund prodecut_box">
	<div class="container">
		<div class="row">

			<div class="col-sm-12 mb-20">
				<h6 class="ml-5 mb-20 text-uppercase heading">
					<span class="text-primary hr-heading"><?= !empty($category) ? $category->title : '' ?> </span>
				</h6>
			</div>
 <?php
if (isset($dataProvider) && ! empty($dataProvider)) {
    echo ListView::widget([
        
        'id' => 'my-listview-id',
        'dataProvider' => $dataProvider,
        'itemView' => '_list',
        'layout' => '<div class="items clearfix">{items}</div><div class="clearfix">{pager}</div>'
    
    ]);
}
?>   
</div>
	</div>
</section>
<?php

Pjax::end();
?>  
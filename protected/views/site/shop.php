<?php
use Yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ListView;
?>
<div class="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<ul>
					<li><a href="<?=Url::home()?>">Home</a></li>
					<li class="active">Shop</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<section class="categories-icon section-padding bg-drack">
	<div class="container">
		<div class="row">
		
			
			<?php

Pjax::begin([
    'id' => 'status-list-view',
    'enablePushState' => true, // to disable push state
    'enableReplaceState' => true
]);
?>
 <?php
if (isset($dataProvider) && ! empty($dataProvider)) {
    echo ListView::widget([
        'id' => 'my-listview-id',
        'dataProvider' => $dataProvider,
        'itemView' => '_category',
        'layout' => "{summary}\n<div class=\"items\">{items}</div>\n{pager}"
    
    ]);
}
?>   
 <?php

Pjax::end();
?>  
			
			
			
		</div>
	</div>
</section>
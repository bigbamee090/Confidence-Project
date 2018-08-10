<div class="wrapper">
	<div class="panel widget notice-view">
		<div class="panel-heading ">
			<h3 class="panel-title">
				<span> </span> Notices
			</h3>
		</div> 	<?php //Pjax::begin(['id'=>'notices']); ?>
	<div id='notices' class="panel-body-list">

			<div class="content-list content-image menu-action-right">
				<ul class="list-wrapper">

<?php
echo \yii\widgets\ListView::widget([
    'dataProvider' => $notices,
    
    // 'summary' => false,
    
    'itemOptions' => [
        'class' => 'item'
    ],
    'itemView' => '_view',
    'options' => [
        'class' => 'list-view notice-list'
    ]
]);
?>
</ul>

			</div>
		</div><?php //Pjax::end(); ?>

</div>
</div>

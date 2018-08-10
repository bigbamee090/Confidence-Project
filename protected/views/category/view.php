<?php
use app\components\useraction\UserAction;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Category */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Categories'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class=" panel ">

		<div class="category-view panel-body">
			<?php

echo \app\components\PageHeader::widget([
    'model' => $model
]);
?>



		</div>
	</div>
	<div class="panel panel-body">
		<div class="col-md-2">
	
		<?php

if (! empty($model->image_file)) {
    
    ?>
            		<?php
    echo Html::img([
        'category/thumbnail',
        'filename' => $model->image_file
    
    ])?>
            				<p></p>
                   			 <?php
} else {
    ?>
						
							<img
				src="<?=$this->theme->getUrl('frontend/')?>img/pharmacist1.jpg"
				width='100'>
								<?php
}
?>
	</div>
		<div class="col-md-10">
	
    <?php
    
    echo \app\components\TDetailView::widget([
        'id' => 'category-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            'title',
            /*'description:html',*/
            'image_file',
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'value' => $model->getStateBadge()
            ],
            // [
            // 'attribute' => 'type_id',
            // 'value' => $model->getType()
            // ],
            'created_on:datetime',
            'updated_on:datetime',
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => $model->getRelatedDataLink('created_by_id')
            ]
        ]
    ])?>

</div>
<?php

echo $model->description;
?>

		<?php

echo UserAction::widget([
    'model' => $model,
    'attribute' => 'state_id',
    'states' => $model->getStateOptions()
]);
?>
		</div>


	<div class=" panel ">
		<div class=" panel-body ">
			<div class="category-panel">
<?php
$this->context->startPanel();
$this->context->addPanel('Products', 'products', 'Product', $model);
$this->context->endPanel();
?>
				</div>
		</div>
	</div>
</div>
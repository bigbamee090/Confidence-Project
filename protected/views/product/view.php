<?php
use app\components\useraction\UserAction;
use app\modules\media\widgets\FileUploaderWidget;
use app\modules\media\widgets\Gallery;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Products'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class=" panel ">

		<div class="product-view panel-body">
			<?php

echo \app\components\PageHeader::widget([
    'model' => $model
]);
?>
		</div>
	</div>

	<div class=" panel ">
		<div class=" panel-body ">
    <?php
    
    echo \app\components\TDetailView::widget([
        'id' => 'product-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            'title',
            /*'description:html',*/
            'code',
            'price',
            'actual_quantity',
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => $model->getRelatedDataLink('category_id')
            ],
            // [
            // 'attribute' => 'deal_id',
            // 'format' => 'raw',
            // 'value' => $model->getRelatedDataLink('deal_id')
            // ],
            // 'is_fav',
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
	</div>



	<div class="panel panel-default">
		<div class="panel-heading">Product Images :</div>
		<div class="panel-body">
			<?= FileUploaderWidget::widget(['model' => $model]) ?>
			
			<?= Gallery::widget(['model' => $model]) ?>
		</div>
	</div>
</div>

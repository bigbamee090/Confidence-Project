<?php
use app\components\useraction\UserAction;

/* @var $this yii\web\View */
/* @var $model app\models\PlanType */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Plan Types'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class=" panel ">

		<div class="plan-type-view panel-body">
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
        'id' => 'plan-type-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            'title',
            'percent',
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'value' => $model->getStateBadge()
            ]
        ]
    ])?>


<?php

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

</div>

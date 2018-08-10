<?php

use yii\helpers\Html;
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Log */

/*$this->title =  $model->label() .' : ' . $model->id; */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>

<div class="wrapper">
	<div class=" panel ">

		<div
			class="log-view panel-body">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>



		</div>
	</div>

	<div class=" panel ">
		<div class=" panel-body ">
    <?php echo \app\components\TDetailView::widget([
    	'id'	=> 'log-detail-view',
        'model' => $model,
        'options'=>['class'=>'table table-bordered'],
        'attributes' => [
            'id',
            'error',
            'api:html',
            /*'description:html',*/
            [
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],
            'link',
            [
			'attribute' => 'type_id',
			'value' => $model->getType(),
			],
            'created_on:datetime',
            /* [
			'attribute' => 'create_user_id',
			'format'=>'raw',
			'value' => $model->getRelatedDataLink('create_user_id'),
			], */
        ],
    ]) ?>


<?php  echo $model->description;?>

 			<div>
		<?php				echo UserAction::widget ( [
						'model' => $model,
						'attribute' => 'state_id',
						'states' => $model->getStateOptions ()
				] );
				?>

		</div>
		</div>
	</div>
	<div class=" panel ">
		<div class=" panel-body ">

<?php echo CommentsWidget::widget(['model'=>$model]); ?>
			</div>
	</div>
</div>
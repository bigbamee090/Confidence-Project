<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\User */
/* @var $dataProvider yii\data\ActiveDataProvider */
// $this->title = Yii::t ( 'app', 'Users' );
$this->params['breadcrumbs'][] = [
    'label' => 'Users'
];

?>
<div class="wrapper">
	<div class="user-index">
		<div class=" panel ">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>

		<div class="panel panel-margin">
			<div class="panel-body">
				<div class="content-section clearfix">
					<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
				</div>
			</div>
		</div>
	</div>
</div>


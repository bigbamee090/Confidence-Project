<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\notice\Notices;
use app\controllers\DashboardController;
use app\models\User;
use app\models\search\User as UserSearch;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;

/* @var $this yii\web\View */
// $this->title = Yii::t ( 'app', 'Dashboard' );

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Dashboard')
];

?>

<?=Notices::widget();?>

<div class="wrapper">
	<?php
$isConfig = \Yii::$app->settings->isConfig;
if (! $isConfig) {
    ?>
		<div>
		<div class="alert alert-info">
			<strong> Info !! </strong> Your app is not configure properly <b><a
				href="<?=Url::toRoute(['/setting/index'])?>"> Click Here </a></b>
			To configure..
		</div>
	</div>
	<?php
}
?>
	
	<!--state overview start-->
	<div class="row state-overview">
		<a href="<?php

echo Url::toRoute([
    'user/company-index'
]);
?>">
			<div class="col-lg-3 col-sm-6">
				<section class="panel ">
					<div class="symbol">
						<div class="icon-wrap">
							<i class="fa fa-building" aria-hidden="true"></i>
						</div>
					</div>
					<div class="value white">
					
					<?php
    
    $perscriberCount = User::find()->where([
        'role_id' => User::ROLE_PRESCRIBERS,
        'created_by_id' => Yii::$app->user->id
    ])->count();
    
    ?>
						<h1 data-speed="1000" data-to="320" data-from="0" class="timer"><?=$perscriberCount?></h1>
						<p>PRESCRIBERS</p>
					</div>
				</section>
			</div>
		</a>

	</div>



	<div class="panel">
		<div class="panel-body">
			Welcome <strong>
         <?php
        
        echo Yii::$app->user->identity->full_name;
        ?></strong>


			<!-- <div class="text-right">
				<a class="btn btn-danger"
					href="< ?= Url::toRoute(['dashboard/default-data']) ?>"
					data-confirm="< ?= \Yii::t("app", "Are you sure you want to Reset all settings?") ?>"> Reset
					Settings </a>
			</div> -->

		</div>
	</div>
</div>
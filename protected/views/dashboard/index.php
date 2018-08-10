<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\notice\Notices;
use app\controllers\DashboardController;
use app\models\LoginHistory;
use app\models\Page;
use app\models\User;
use app\models\search\User as UserSearch;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
use app\models\Company;

/* @var $this yii\web\View */
// $this->title = Yii::t ( 'app', 'Dashboard' );
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Dashboard')
];
?>
<?php
// Notices::widget(); ?>

<div class="wrapper">
	<?php
$isConfig = \Yii::$app->settings->isConfig;
if (! $isConfig) {
    ?>
	<div class="alert alert-info">
		<strong> Info !! </strong> Your app is not configure properly <b><a
			href="<?=Url::toRoute(['/setting/index'])?>"> Click Here </a></b>
		To configure..
	</div>
<?php
}
?>
	
	<!--state overview start-->
	<div class="row state-overview">
		<a href="<?php

echo Url::toRoute([
    'company/index'
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
						<h1 data-speed="1000" data-to="320" data-from="0" class="timer"><?php
    
    echo Company::find()->count();
    ?></h1>
						<p>Total Companies</p>
					</div>
				</section>
			</div>
		</a> <a href="<?php

echo Url::toRoute([
    'page/index'
]);
?>">
			<div class="col-lg-3 col-sm-6">
				<section class="panel ">
					<div class="symbol ">
						<div class="icon-wrap">
							<i class="fa fa-file"></i>
						</div>
					</div>
					<div class="value white">
						<h1 data-speed="1000" data-to="432" data-from="0" class="timer"><?php
    
    echo Page::find()->count();
    ?></h1>
						<p>Pages</p>
					</div>
				</section>
			</div>
		</a> <a href="<?php

echo Url::toRoute([
    'login-history/index'
]);
?>">
			<div class="col-lg-3 col-sm-6">
				<section class="panel ">
					<div class="symbol ">
						<div class="icon-wrap">
							<i class="fa fa-file"></i>
						</div>
					</div>
					<div class="value white">
						<h1 data-speed="1000" data-to="123" data-from="0" class="timer"><?php
    
    echo LoginHistory::find()->count();
    ?></h1>
						<p>Login-History</p>
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

	<div class="panel">

		<div class="panel-body">
			<div class="panel-heading">
				<span class="tools pull-right"> </span>
			</div>

			<div class="col-md-6">
						<?php
    $data = DashboardController::MonthlySignups();
    
    echo Highcharts::widget([
        'options' => [
            'credits' => array(
                'enabled' => false
            ),
            
            'title' => [
                'text' => 'Monthly Users Registered'
            ],
            'chart' => [
                'type' => 'column'
            ],
            'xAxis' => [
                'categories' => array_keys($data)
            ],
            'yAxis' => [
                'title' => [
                    'text' => 'Count'
                ]
            ],
            'series' => [
                [
                    'name' => 'Users',
                    'data' => array_values($data)
                ]
            ]
        ]
    
    ]);
    ?>
	</div>

			<div class="col-md-6">
	<?php
$data = DashboardController::MonthlySignups();

?>
					<?php
    echo Highcharts::widget([
        'scripts' => [
            'highcharts-3d',
            'modules/exporting'
        ],
        
        'options' => [
            'credits' => array(
                'enabled' => false
            ),
            'chart' => [
                'plotBackgroundColor' => null,
                'plotBorderWidth' => null,
                'plotShadow' => false,
                'type' => 'pie'
            ],
            'title' => [
                'text' => 'Statistics'
            ],
            'tooltip' => [
                'valueSuffix' => ''
            ],
            'plotOptions' => [
                'pie' => [
                    'allowPointSelect' => true,
                    'cursor' => 'pointer',
                    'dataLabels' => [
                        'enabled' => true
                    ],
                    // 'format' => '<b>{point.name}</b>: {point.percentage:.1f} %'
                    'showInLegend' => true
                ]
            ],
            
            'htmlOptions' => [
                'style' => 'min-width: 100%; height: 400px; margin: 0 auto'
            ],
            'series' => [
                [
                    'name' => 'Total Count',
                    'colorByPoint' => true,
                    
                    'data' => [
                        [
                            'name' => 'Inactive User',
                            'color' => '#0096FF',
                            'y' => (int) User::findActive(0)->count(),
                            'sliced' => true,
                            'selected' => true
                        ],
                        [
                            'name' => 'Active User',
                            'color' => '#1FAE66',
                            'y' => (int) User::findActive()->count(),
                            'sliced' => true,
                            'selected' => true
                        ]
                    ]
                ]
            ]
        ]
    ]);
    ?>
							</div>
		</div>

	</div>
	<div class="clearfix"></div>
</div>


<?php
use yii\helpers\Inflector;
use yii\helpers\Url;
use app\models\Setting;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Setting */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* $this->title = Yii::t('app', 'Index'); */
$this->params ['breadcrumbs'] [] = [ 
		'label' => Yii::t ( 'app', 'Settings' ),
		'url' => [ 
				'index' 
		] 
];
$this->params ['breadcrumbs'] [] = Yii::t ( 'app', 'Index' );
$coreConfig = (Setting::getDefaultConfig ()) ['core'];
$customConfig = (Setting::getDefaultConfig ()) ['custom'];
?>
<div class="wrapper">
	<div class="user-index">
		<div class=" panel ">
			<div class="setting-index">
				<?= \app\components\PageHeader::widget(); ?>
  			</div>
		</div>
		<div class="panel panel-margin">
			<div class="panel-body">
				<div class="content-section clearfix">
					<div class="panel-group" id="accordion" role="tablist"
						aria-multiselectable="true">
					<?php
					if (! empty ( $model )) {
						foreach ( $model as $config ) {
							$key = $config->key;
							$setConfig = \Yii::$app->settings->$key;
							?>
							<div class="panel panel-default">
							<div class="panel-heading" role="tab"
								id="headingOne_<?= $config->key?>">
								<strong class="panel-title"> <a role="button"
									data-toggle="collapse" data-parent="#accordion"
									href="#collapseOne_<?= $config->key ?>" aria-expanded="true"
									aria-controls="collapseOne"> <?= $setConfig->title ?> </a>
								</strong>
							</div>
							<div id="collapseOne_<?= $config->key ?>"
								class="panel-collapse collapse in" role="tabpanel"
								aria-labelledby="headingOne_<?= $config->key ?>">
								<div class="panel-body">
								<?php
							$defaultSetting = Setting::getDefault ( $key );
							$defaultConfig = array_merge ( $defaultSetting ['value'], \Yii::$app->settings->$key->asArray );
							foreach ( $defaultConfig as $configKey => $configDetail ) {
								?>
									<div class="col-md-12">
										<div class="col-md-6">
											<h5><?= Inflector::titleize($configKey) ?></h5>
										</div>
										<div class="col-md-6">
											<h5><?= Setting::checkKeyType($configDetail['type'], $configDetail['value']) ?></h5>
										</div>
									</div>
									<?php
							}
							?>
									<div class="pull-right">
										<a href="javascript:;" class="btn btn-primary showModalButton"
											data-target="<?= Url::toRoute ( ['/setting/ajax-update','key' => $key])?>">
											Update </a>
									</div>
								</div>
							</div>
						</div>
					<?php
						}
					}
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
yii\bootstrap\Modal::begin ( [ 
		'id' => 'modal',
		'size' => 'modal-lg' 
] );
echo "<div id='modalContent'></div>";
yii\bootstrap\Modal::end ();
?>
<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\PageHeader;
use app\components\useraction\UserAction;
use app\models\User;
use app\components\TDetailView;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = [
    'label' => 'Users'
    /*
 * 'url' => [
 * 'index'
 * ]
 */
];
$this->params['breadcrumbs'][] = [
    'label' => $model->getFullName()
];

?>
<div class="wrapper">

	<div class=" panel ">
		<?php

echo PageHeader::widget([
    'title' => $model->getFullName()
]);
?>
	</div>
	<div class="panel">
		<div class=" panel-body">
			<div class="col-md-2">
			<?php

if (! empty($model->profile_file)) {
    ?>
				<?php
    echo Html::img([
        'profile-image'
    ], [
        'width' => 100
    ])?><br /> <br />
				
   			 	
   			 	<?php
} else {
    ?>
   
    <img
    src="<?php
    
    echo $url?>img/default.jpeg">
  
  
<?php
}
?>
			</div>
			<div class="col-md-10">
			<?php
echo TDetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        // 'full_name',
        'first_name',
        'email:email',
        'contact_no',
        'created_on:datetime'
    ]
])?>
			</div>
		</div>
		<div class="panel-body">
				<?php
    if ((User::isAdmin()) && (\Yii::$app->user->id != $model->id)) {
        $actions = $model->getStateOptions();
        array_shift($actions);
        echo UserAction::widget([
            'model' => $model,
            'attribute' => 'state_id',
            'states' => $model->getStateOptions(),
            'allowed' => $actions
        ]);
    }
    ?>
			</div>



	</div>
</div>
<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\TActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$url = $this->theme->getUrl('/');
?>

<div class="panel-body">

    <?php
    
    $form = TActiveForm::begin([
        'id' => 'user-form',
        'enableClientValidation' => true
    ]);
    ?>

	<div class="col-md-6">
	<?=$form->field($model, 'first_name')->textInput(['maxlength' => 55])?>


    <?=$form->field($model, 'email')->textInput(['maxlength' => 128])?>

   <?php

if (Yii::$app->controller->action->id != 'update') {
    ?>
		<?=$form->field($model, 'password')->passwordInput(['maxlength' => true])?>
		<?=$form->field($model, 'confirm_password')->passwordInput(['maxlength' => true])?>

     <?php
}
?>
	</div>

	<div class="col-md-6">
	
	 <?=$form->field($model, 'contact_no')->textInput(['maxlength' => 11])?>

   <?=$form->field($model, 'profile_file')->fileInput()?>
	<?php

if (! empty($model->profile_file)) {
    ?>
				<?php
    echo Html::img([
        'profile-image',
        'id' => $model->id
    ], [
        'width' => 50
    ])?><br /> <br />
				
   			 	
   			 	<?php
}
?>
   
	</div>

	<div
		class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom">
		<div class="form-group text-center">
        <?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['id' => 'user-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success'])?>
    </div>
	</div>

    <?php
    
    TActiveForm::end();
    ?>

</div>



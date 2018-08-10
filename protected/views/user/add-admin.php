<?php
use app\models\User;
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = 'Signup';
?>

<section class="login-form">
  <div class="container">
   <div class="row">
    <div class="col-md-offset-4 col-md-4">


			<?php

$form = TActiveForm::begin([
    'id' => 'form-add-admin',
    'options' => [
        'class' => 'form-horizontal'
    ]
]);
?>


    <div class="panel panel-default">
      
      <div class="panel-body">
	   <div class="text-center">
		<h3>Admin Registration Form</h3>
	   </div>
			


					
						
						<?=$form->field ( $model, 'full_name', [ 'template' => '<div class="col-sm-12">{input}{error}</div>' ] )->textInput ( [ 'maxlength' => true,'placeholder' => 'Full Name' ] )->label ( false )?>



						<?=$form->field ( $model, 'email', [ 'template' => '<div class="col-sm-12">{input}{error}</div>' ] )->textInput ( [ 'maxlength' => true,'placeholder' => 'Email' ] )->label ( false )?>


							<?=$form->field ( $model, 'password', [ 'template' => '<div class="col-sm-12">{input}{error}</div>' ] )->passwordInput ( [ 'maxlength' => true,'placeholder' => 'Password' ] )->label ( false )?>


							<?=$form->field ( $model, 'confirm_password', [ 'template' => '<div class="col-sm-12">{input}{error}</div>' ] )->passwordInput ( [ 'maxlength' => true,'placeholder' => 'Confirm Password' ] )->label ( false )?>
						
                    <?=Html::submitButton ( 'Signup', [ 'class' => 'btn btn-primary','name' => 'signup-button' ] )?>
               



								<!-- driver form ends -->



					

				</div>
			
			
			<div class="panel-footer text-center">
			    <div class="registration m-t-20 m-b-20">
							Already have an account ?<a href="<?php echo Url::toRoute(['user/login']);?>">
								Login </a>
						</div>
			   </div>
			   
			   	<?php TActiveForm::end(); ?>
		</div>
	</div>
</div>
</div>
</section>
<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Sign In';

$fieldOptions1 = [
    'options' => [
        'class' => 'form-group has-feedback'
    ],
    
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => [
        'class' => 'form-group has-feedback'
    ],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="alert alert-success">
    <?php echo Yii::$app->session->getFlash('success')?>
</div>
<?php endif; ?>
<section class="login-form">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-3 col-md-6">
			 <?php
    
    $form = TActiveForm::begin([
        'id' => 'login-form',
        'enableClientValidation' => false,
        'enableAjaxValidation' => false,
        'options' => [
            'class' => 'login-form form'
        ]
    ]);
    ?>
    <div class="panel panel-default">

					<div class="panel-body">
						<div class="text-center">
							<h3>Log In</h3>
						</div>

						<div class="row">
							<div class="col-md-12">
		    <?=$form->field ( $model, 'username', $fieldOptions1 )->label ( false )->textInput ( [ 'placeholder' => $model->getAttributeLabel ( 'email' ) ] )?>
		</div>
						</div>
						<div class="row">
							<div class="col-md-12">
			<?=$form->field ( $model, 'password', $fieldOptions2 )->label ( false )->passwordInput ( [ 'placeholder' => $model->getAttributeLabel ( 'password' ) ] )?>
         </div>
						</div>
						<div class="row">
							<div class="col-md-6 padd-0">
								<div class="checkbox remember">
					<?php echo $form->field($model, 'rememberMe')->checkbox();?>

					</div>
							</div>
							<div class="col-md-6 text-right">
					<?=Html::submitButton ( 'Login', [ 'class' => 'btn btn-primary','id' => 'login','name' => 'login-button' ] )?>
					
				</div>

						</div>


					</div>
					<div class="panel-footer text-center">
						<a href="<?php echo Url::toRoute(['user/recover'])?>">Forgot
							Password? </a>
					</div>
				</div>
	<?php TActiveForm::end()?>
</div>
		</div>
	</div>
</section>
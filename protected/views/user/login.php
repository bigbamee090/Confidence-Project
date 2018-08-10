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
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
<div class="alert alert-success">
    <?php echo Yii::$app->session->getFlash('success')?>
</div>
<?php endif; ?>
<div class="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<ul>
					<li><a href="<?= Url::home() ?>">Home</a></li>
					<li class="active">Login</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<section class="section white-backgorund">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="row">
					<div class="col-sm-12 mb-30">
						<h6 class="ml-5 mb-20 text-uppercase heading">
							<span class="text-primary hr-heading"> Existing </span> Customer
						</h6>
					</div>
				</div>
				<?php
    $form = TActiveForm::begin([
        'id' => 'login-form',
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'options' => [
            'class' => 'form-horizontal'
        ]
    ]);
    ?>
				<div class="form-group">
					<div class="col-sm-12">
						<?=$form->field ( $model, 'username', ['template' => '{input}{error}', 'options' => ['tag' => false]] )->label ( false )->textInput ( [ 'placeholder' => $model->getAttributeLabel ( 'email' ), 'class'=>"form-control input-md" ] )?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<?=$form->field ( $model, 'password', ['template' => '{input}{error}', 'options' => ['tag' => false]] )->label ( false )->passwordInput( [ 'placeholder' => $model->getAttributeLabel ( 'password' ), 'class'=>"form-control input-md" ] )?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-6">
						
							<?php echo $form->field($model, 'rememberMe', ['template' => '{input}{error}', 'options' => ['tag' => false]] )->checkbox(['class' => 'styled', 'id' => 'remember']);?>
						
					</div>
					<div class="col-sm-6 text-right">
						<label><a href="<?= Url::toRoute(['user/recover'])?>">Forgot
								Password</a></label>
					</div>
				</div>
				<div class="form-group">
					<div class=" col-sm-12">
						<?=Html::submitButton ( '<i class="fa fa-lock mr-5"></i> Login</a>', [ 'class' => 'btn btn-default round btn-md','id' => 'login','name' => 'login-button' ] )?>
					</div>
				</div>
				<?php TActiveForm::end()?>
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="row">
					<div class="col-sm-12 mb-20">
						<h6 class="ml-5 mb-20 text-uppercase heading">
							<span class="text-primary hr-heading">New</span> Customer
						</h6>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 login-para">
						<p>
							By creating an account with our store, you will be able to move
							through the checkout process faster,store multiple shipping
							addresses, view and track your orders in your account and more.<br>
							<a style="color: #27aee2;"
								href="<?= Url::toRoute(['site/register']) ?>">Create an account</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
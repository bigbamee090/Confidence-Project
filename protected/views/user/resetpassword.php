<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Inflector;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

$this->params['breadcrumbs'][] = [
    'label' => 'Users',
    'url' => [
        'user/index'
    ]
];

$this->params['breadcrumbs'][] = Inflector::humanize(Yii::$app->controller->action->id);

?>
<section class="login-form">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-4 col-md-4">
        <?php
        
        $form = TActiveForm::begin([
            'id' => 'changepassword-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => false
        ]);
        ?>
            <div class="panel panel-default">

					<div class="panel-body">
						<div class="text-center">
							<h3>Reset password</h3>
							<p> <?= \Yii::t("app", "Please fill out your email. A link to reset password will be sent there.") ?></p>
						</div>
						
           
               <?=$form->field ( $model, 'password', [ 'inputOptions' => [ 'placeholder' => '' ] ] )->passwordInput ()?>
               <?=$form->field ( $model, 'confirm_password', [ 'inputOptions' => [ 'placeholder' => '' ] ] )->passwordInput ()?>
                
                <?=Html::submitButton ( 'Change password', [ 'class' => 'btn btn-primary' ] )?>
                
           <?php TActiveForm::end(); ?>
        </div>
				</div>
			</div>
		</div>
	</div>


</section>
</div>
</div>
</div>
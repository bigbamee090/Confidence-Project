<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->params ['breadcrumbs'] [] = [
		'label' => 'Users',
		'url' => [
				'user/index'
		]
];

$this->params ['breadcrumbs'] [] = [
		'label' => Yii::t ( 'app', 'Change Password' )
];


?>
<div class="page-head">
	<h3><?= Html::encode($this->title)?>
	</h3>

</div>
<div class="wrapper">
	<div class="panel clearfix">
		<header class="panel-heading"> Please fill out the following fields to

			change password </header>
		<div class="col-md-6 col-md-offset-3 panel-body">
    		<?php

						$form = ActiveForm::begin ( [
								'id' => 'changepassword-form',
								'enableAjaxValidation' => true,
								'options' => [
										'class' => 'form-horizontal'
								],
								'fieldConfig' => [
										'template' => "{label}\n<div class=\"col-lg-8\">
                        {input}</div>\n<div class=\"col-md-6 col-md-offset-4\">
                        {error}</div>",
										'labelOptions' => [
												'class' => 'col-lg-4 control-label'
										]
								]
						] );
						// 'action'=>['api/user/change-password'],

						?>

     			<?php //$form->field ( $model, 'oldPassword', [ 'inputOptions' => [ 'placeholder' => '','value' => '' ] ] )->label ()->passwordInput ()?>
            	<?=$form->field ( $model, 'newPassword', [ 'inputOptions' => [ 'placeholder' => '','value' => '' ] ] )->label ()->passwordInput ()?>
        		<?=$form->field ( $model, 'confirm_password', [ 'inputOptions' => [ 'placeholder' => '' ] ] )->label ()->passwordInput ()?>
        	<div class="form-group">
				<div class="col-lg-12 text-center">
					      <?=Html::submitButton ( 'Change password', [ 'class' => 'btn btn-success','name' => 'changepassword-button' ] )?>
            	</div>

    <?php ActiveForm::end(); ?>
    <div class="gap-20"></div>

			</div>

		</div>
	</div>
</div>
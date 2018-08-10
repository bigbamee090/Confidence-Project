<?php
use app\components\TActiveForm;
use yii\helpers\Inflector;

/**
 * * @copyright : ToXSL Technologies Pvt.
 * Ltd. < www.toxsl.com >
 *
 * @author : Shiv Charan Panjeta < shiv@toxsl.com >
 */
$tile = 'Company Users';
/* @var $this yii\web\View */
/* @var $user app\models\User */
$this->params['breadcrumbs'][] = [
    'label' => $tile,
    'url' => [
        'user/admin'
    ]
];
$this->params['breadcrumbs'][] = Inflector::humanize(Yii::$app->controller->action->id);
?>

<div class="wrapper">
	<div class="panel">
		<div class="user-create">
			<?=\app\components\PageHeader::widget(['title' => $tile]);?>
        </div>
	</div>

	<div class="content-section clearfix panel">

		<div class="panel-body">
        
            <?php
            $form = TActiveForm::begin([
                'id' => 'user-form',
                'enableClientValidation' => true,
                'enableAjaxValidation' => true
            ]);
            ?>        
        	<div class="">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">Admin User Details</div>
					</div>
					<div class="panel-body">
						<p class="text-right">At least one User is required.</p>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<?=$form->field($user, 'first_name')->textInput(['placeholder' => "Enter First Name"]);?>
							</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?=$form->field($user, 'last_name')->textInput(['placeholder' => "Enter Last Name"]);?>
							</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyAdmin, 'salutation')->dropDownList($companyAdmin->getSalutationOptions());?>
							</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyAdmin, 'registration_number')->textInput(['placeholder' => "Enter Registration Number"]);?>
							</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyAdmin, 'address_line1')->textInput(['placeholder' => "Enter Address Line 1"]);?>
							</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyAdmin, 'address_line2')->textInput(['placeholder' => "Enter Address Line 2"]);?>
							</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyAdmin, 'city')->textInput(['placeholder' => "Enter City"]);?>
							</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyAdmin, 'pincode')->textInput(['placeholder' => "Enter Postal Code"]);?>	
							</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyAdmin, 'country')->dropDownList($companyAdmin->getCountryOptions(), ['prompt' => "Select Country"]);?>
							</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<p>If you wish to order from outside the British Isles please
									call or chat.</p>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($user, 'email')->textInput(['placeholder' => "E-mail address should be personal to you, avoid using a generic address"]);?>	
							</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?=$form->field($user, 'contact_no')->textInput(['placeholder' => "Enter Telephone Number"]);?>	
							</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="">
									<label>What do you want this person to do on this account</label>
								</div>
							</div>
						
						<?php
    echo $form->field($companyAdmin, 'permission')
        ->checkboxList($companyAdmin->getPermissionOptions(), [
        'item' => function ($index, $label, $name, $checked, $value) {
            $checked = $checked ? 'checked' : '';
            
            $html = "<div class='col-md-6'>";
            $html .= "<div class='form-group'>";
            $html .= "<label class='checkbox-inline'>";
            $html .= "<input type='checkbox' {$checked} name='{$name}' value='{$value}'>{$label}</label>";
            $html .= "</div></div>";
            return $html;
        }
    ])
        ->label(false)?>
						

					</div>
					</div>
				</div>

			</div>

			<div class="">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">Login Details</div>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($user, 'username')->textInput(['placeholder' => "Enter Username"]);?>
							</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($user, 'password')->passwordInput(['placeholder' => "Enter Password"]);?>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="row">
				<div class="col-md-12">

					<div style="margin-top: 10px; margin-left: 0px" class="form-group">
					
					<?=\yii\helpers\Html::submitButton('Submit', ['class' => 'btn btn-primary'])?>

					</div>
				</div>
			</div>
        
            <?php
            
            TActiveForm::end();
            ?>
        
        </div>


	</div>
</div>
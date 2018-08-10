<?php
use app\components\TActiveForm;
use yii\helpers\Inflector;
use app\models\User;

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

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">Select User Role</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">

						<div class="form-group">
							<div class="form-group"">
								<select id="change_user-role_id">
									<option value="">--- Select User Role ---</option>
							<?php
    $roles = $user->getCompanyRoleOptions();
    foreach ($roles as $key => $value) {
        ?>
							         <option value="<?= $key ?>"><?= $value ?></option>
							     <?php
    }
    ?>
						</select>
							</div>
						</div>


					</div>
				</div>
			</div>
		</div>


		<div class="panel-body company_panel" id="company_admin_panel"
			style="display: none">
            <?php
            $form = TActiveForm::begin([
                'id' => 'user-form',
                'enableClientValidation' => true,
                'enableAjaxValidation' => true
            ]);
            ?>        
			<div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">Admin User Details</div>
					</div>
					<div class="panel-body">
						<p class="text-right">At least one User is required.</p>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<?=$form->field($companyAdmin, 'first_name')->textInput(['placeholder' => "Enter First Name"]);?>
							</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?=$form->field($companyAdmin, 'last_name')->textInput(['placeholder' => "Enter Last Name"]);?>
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
								<?=$form->field($companyAdmin, 'email')->textInput(['placeholder' => "E-mail address should be personal to you, avoid using a generic address"]);?>	
							</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?=$form->field($companyAdmin, 'contact_no')->textInput(['placeholder' => "Enter Telephone Number"]);?>	
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


			<div>
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
            <?php TActiveForm::end(); ?>
        </div>



		<div class="panel-body company_panel" id="company_prescriber_panel"
			style="display: none">
        
            <?php
            $form = TActiveForm::begin([
                'id' => 'company_prescriber-form',
                'action' => [
                    'user/add-company-prescriber'
                ],
                'enableClientValidation' => true,
                'enableAjaxValidation' => true
            ]);
            ?>        
            
			<div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">Prescriber Details</div>
					</div>
					<div class="panel-body">
						<p class="text-right">At least one Prescriber is required.</p>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyPrescriber, 'first_name')->textInput(['placeholder' => "Enter First Name"]);?>
							</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyPrescriber, 'last_name')->textInput(['placeholder' => "Enter Last Name"]);?>
							</div>
							</div>

						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyPrescriber, 'salutation')->dropDownList($companyPrescriber->getSalutationOptions());?>
							</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyPrescriber, 'registration_number')->textInput(['placeholder' => "Enter Registration Number"])->label('Registration Number (GMC Number, PIN Number etc)');?>
							</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-7">
								<div class="form-group">
									<label class="custom-label">Company Type</label> 
								
								<?php
        
        echo $form->field($companyPrescriber, 'company_type')
            ->radioList($companyPrescriber->getCompanyTypeOptions(), [
            'item' => function ($index, $label, $name, $checked, $value) {
                $return = "<label class='radio-inline'>";
                $return .= "<input type='radio' name='{$name}' value='{$value}' tabindex='3'>";
                $return .= ucwords($label);
                $return .= "</label>";
                return $return;
            }
        ])
            ->label(false);
        ?>
							</div>
							</div>

						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyPrescriber, 'address_line1')->textInput(['placeholder' => "Enter Address Line 1"]);?>
							</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyPrescriber, 'address_line2')->textInput(['placeholder' => "Enter Address Line 2"]);?>
							</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyPrescriber, 'city')->textInput(['placeholder' => "Enter City"]);?>
							</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyPrescriber, 'pincode')->textInput(['placeholder' => "Enter Postal Code"]);?>
							</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								<?=$form->field($companyPrescriber, 'country')->dropDownList($companyPrescriber->getCountryOptions(), ['prompt' => "Select Country"]);?>
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
								<?=$form->field($companyPrescriber, 'email')->textInput(['placeholder' => "E-mail address should be personal to you, avoid using a generic address"]);?>
							</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?=$form->field($companyPrescriber, 'contact_no')->textInput(['placeholder' => "Enter Telephone Number"]);?>
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
    echo $form->field($companyPrescriber, 'permission')
        ->checkboxList($companyPrescriber->getPermissionOptions(), [
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
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="exampleInputFile">Passport Image (Choose scanned
										copy of your passport in image format.)</label> 
									<?=$form->field($companyPrescriber, 'passport_image', ['enableAjaxValidation' => false, 'enableClientValidation' => true])->fileInput()->label(false);?>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div>
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
            <?php TActiveForm::end(); ?>
        </div>

	</div>
</div>


<script>
$(document).ready(function () {
	var v = $("#user-form #user-role_id").val();
	
	var role = $("#change_user-role_id").val(v);
	$(".company_panel").hide();

	if( role == "<?= User::ROLE_CLINIC_ADMIN ?>" ) {
		$("#company_admin_panel").show();
	} else if ( role == "<?= User::ROLE_CLINIC_MANAGER ?>" ) {
		$("#company_admin_panel").show();
	} else if ( role == "<?= User::ROLE_PRESCRIBERS ?>" ) {
		$("#company_prescriber_panel").show();
	}
});

$("#change_user-role_id").on("change", function () {
	var role = $(this).val();
	$(".company_panel").hide();

	if( role == "<?= User::ROLE_CLINIC_ADMIN ?>" ) {
		$("#company_admin_panel").show();
	} else if ( role == "<?= User::ROLE_CLINIC_MANAGER ?>" ) {
		$("#company_admin_panel").show();
	} else if ( role == "<?= User::ROLE_PRESCRIBERS ?>" ) {
		$("#company_prescriber_panel").show();
	}
	$(".user__ROLE").val(role);
	var v = $(".user__ROLE").val();
});
</script>

<?php
use app\components\TActiveForm;
?>
<?php
$existPermission = [];
$existCompanyType = '';
if ($companyAdmin->isNewRecord) {
    $buttonText = "Submit";
} else {
    $buttonText = "Update";
    $existPermission = $companyAdmin->permission;
    $existPermission = json_decode($existPermission, TRUE);
    $existCompanyType = $company->type_id;
}

?>

<section class="section white-backgorund contact">	
		<?php
$form = TActiveForm::begin();
?>

		<div class="">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">Account Contact Details</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
								<?=$form->field($company, 'first_name')->textInput(['placeholder' => "Enter First Name"]);?>
							</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
								<?=$form->field($company, 'last_name')->textInput(['placeholder' => "Enter Last Name"]);?>
							</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
								<?=$form->field($company, 'email')->textInput(['placeholder' => "E-mail address should be personal to you, avoid using a generic address"]);?>	
							</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
								<?=$form->field($company, 'contact_no')->textInput(['placeholder' => "Enter Telephone Number"])->label('Telephone');?>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">Company Details</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
								<?=$form->field($company, 'company_name')->textInput(['placeholder' => "Enter Company Number"]);?>
							</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
								<?=$form->field($company, 'known_as')->textInput(['placeholder' => "Enter Known As"]);?>	
							</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label class="custom-label">Company Type</label>
								<?php
        echo $form->field($company, 'type_id')
            ->radioList($company->getTypeOptions(), [
            'item' => function ($index, $label, $name, $checked, $value) use ($existCompanyType) {
                if ($existCompanyType == $value) {
                    $checked = "checked";
                } else {
                    $checked = '';
                }
                $return = '<label class="radio-inline">';
                $return .= "<input type='radio' {$checked} name='{$name}' value='{$value}' tabindex='3'>";
                $return .= '<i></i>';
                $return .= '<span>' . ucwords($label) . '</span>';
                $return .= '</label>';
                return $return;
            }
        ])
            ->label(false);
        ?>
							</div>
					</div>

				</div>
				<div class="row">
					<div class="col-md-7">
						<div class="form-group">
								<?=$form->field($company, 'country')->dropDownList($company->getCountryOptions(), ['prompt' => "Select Country"]);?>
								<p>If you wish to order from outside the British Isles please
								call or chat.</p>
						</div>

					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
									<?=$form->field($company, 'registration_number')->textInput(['placeholder' => "Enter Registration Number"]);?>
							</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
									<?=$form->field($company, 'vat_registration_number')->textInput(['placeholder' => "Enter Company VAT Registration Number"]);?>
							</div>
					</div>
				</div>
				<div class="row"></div>
			</div>
		</div>

	</div>
	<!--  Invoice address section / start -->
<?php

if ($companyAdmin->isNewRecord) {
    ?>
	<div class="">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">Invoice Address</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
									<?=$form->field($invoiceAddress, 'address_line1')->textInput(['placeholder' => "Enter Address Line 1"]);?>
							</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
									<?=$form->field($invoiceAddress, 'address_line2')->textInput(['placeholder' => "Enter Address Line 2"]);?>
							</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
									<?=$form->field($invoiceAddress, 'city')->textInput(['placeholder' => "Enter City"]);?>
							</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
									<?=$form->field($invoiceAddress, 'pincode')->textInput(['placeholder' => "Enter Postal Code"]);?>
							</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
								<?=$form->field($invoiceAddress, 'country')->dropDownList($company->getCountryOptions(), ['prompt' => "Select Country"]);?>
							</div>
					</div>
				</div>

			</div>
		</div>

	</div>
	<?php
}
?>
	<!--  Invoice address section / end -->
	<!--  Delivery address section / Start -->
	<?php

if ($companyAdmin->isNewRecord) {
    ?>
	<div class="">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">Delivery Address</div>
			</div>
			<div class="panel-body">
				<p class="text-right">At least one Delivery Address is required.</p>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
									<?=$form->field($deliveryAddress, 'name')->textInput(['placeholder' => "Enter Contact Name"]);?>
							</div>
					</div>

				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
									<?=$form->field($deliveryAddress, 'email')->textInput(['placeholder' => "E-mail address should be personal to you, avoid using a generic address"]);?>
							</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
									<?=$form->field($deliveryAddress, 'phone')->textInput(['placeholder' => "Enter Telephone Number"]);?>
							</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
									<?=$form->field($deliveryAddress, 'address_line1')->textInput(['placeholder' => "Enter Address Line 1"]);?>
							</div>
					</div>

				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
								<?=$form->field($deliveryAddress, 'address_line2')->textInput(['placeholder' => "Enter Address Line 2"]);?>
							</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
									<?=$form->field($deliveryAddress, 'city')->textInput(['placeholder' => "Enter City"]);?>
							</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
									<?=$form->field($deliveryAddress, 'pincode')->textInput(['placeholder' => "Enter Postal Code"]);?>
							</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
								<?=$form->field($deliveryAddress, 'country')->dropDownList($company->getCountryOptions(), ['prompt' => "Select Country"]);?>
							</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<p>If you wish to order from outside the British Isles please call
							or chat.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php

}
?>
<!--  delivery address section / End  -->

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
								<?=$form->field($companyAdmin, 'country')->dropDownList($company->getCountryOptions(), ['prompt' => "Select Country"]);?>
							</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<p>If you wish to order from outside the British Isles please call
							or chat.</p>
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
        'item' => function ($index, $label, $name, $checked, $value) use ($existPermission) {
            // $checked = $checked ? 'checked' : '';
            if (in_array($value, $existPermission)) {
                $checked = 'checked';
            } else {
                $checked = '';
            }
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
<?php

if ($companyAdmin->isNewRecord) {
    ?>
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
<?php
}
?>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="checkbox">
						<?=$form->field($user, "tos", ['options' => ['tag' => 'span'],'template' => "{input}"])->checkbox(['checked' => false,'required' => true])->label('I agree to the Terms and Conditions');?>
					</div>
				</div>
				<div style="margin-top: 10px; margin-left: 0px" class="form-group">					
					<?=\yii\helpers\Html::submitButton($buttonText, ['class' => 'btn btn-default round btn-md'])?>
					<a class="btn btn-default round btn-md" href="javascript:;">Cancel</a>
				</div>
			</div>
		</div>
	</div>	
		<?php

TActiveForm::end();
?>
</section>
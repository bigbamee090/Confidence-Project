<?php
use yii\helpers\Inflector;
use app\components\TActiveForm;
use yii\helpers\Html;

/**
 *
 * @copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * @author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/* @var $this yii\web\View */
/* @var $model app\models\User */
$this->params['breadcrumbs'][] = [
    'label' => 'Users',
    /* 'url' => [
        'user/index'
    ] */
];

$this->params['breadcrumbs'][] = Inflector::humanize(Yii::$app->controller->action->id);
?>

<div class="wrapper">
	<div class="panel">

		<div class="user-create">
			<?=\app\components\PageHeader::widget(['title'=>'Add Prescriber']);?>
        </div>

	</div>

	<div class="content-section clearfix panel">

		<div class="panel-body">
            <?php
            $form = TActiveForm::begin();
            ?>
        
        	<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">Prescriber Details</div>
				</div>
				<div class="panel-body">
					<p class="text-right">At least one Prescriber is required.</p>
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
									<?=$form->field($companyPrescriber, 'passport_image')->fileInput()->label(false);?>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

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
								<?=$form->field($user, 'password')->textInput(['placeholder' => "Enter Password"]);?>
							</div>
					</div>
				</div>
			</div>
		</div>




		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom">
			<div class="form-group text-center">
                <?=Html::submitButton(Yii::t('app', 'Create'), ['id' => 'user-form-submit','class' => 'btn btn-primary'])?>
            </div>
		</div>
        
            <?php
            
            TActiveForm::end();
            ?>
        
        </div>


</div>

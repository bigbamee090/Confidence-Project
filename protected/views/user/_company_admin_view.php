<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\PageHeader;
use app\components\TActiveForm;
use app\components\TDetailView;
use app\models\CompanyAdmin;
use yii\helpers\Html;
use app\models\User;
use app\modules\feed\widgets\FeedWidget;

$this->params['breadcrumbs'][] = [
    'label' => 'Users'
    /*
 * 'url' => [
 * 'index'
 * ]
 */
];
$this->params['breadcrumbs'][] = [
    'label' => $model->getFullName()
];
?>
<div class="wrapper">

	<div class=" panel ">
		<?php

echo PageHeader::widget([
    'title' => $model->getFullName()
]);
?>
	</div>

	<div class="row">
		<div class="col-md-9">
			<div class="panel">
				<div class=" panel-body">
					<div class="col-md-12">
			<?php
echo TDetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        [
            'attribute' => 'full_name',
            'value' => function ($model) {
                $name = isset($model->companyAdmin) ? $model->companyAdmin->getSalutation() : '';
                return $name . ' ' . $model->getFullName();
            }
        ],
        'email:email',
        'contact_no',
        [
            'attribute' => 'state_id',
            'format' => 'raw',
            'label' => 'Company Name',
            'value' => function ($model) {
                return isset($model->company) ? $model->company->company_name : 'Not Set';
            }
        ],
        [
            'attribute' => 'state_id',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->getStateBadge();
            }
        ],
        [
            'label' => 'Registration Number',
            'value' => function ($model) {
                return isset($model->company) ? $model->company->registration_number : 'Not Set';
            }
        ],
        [
            'label' => 'Address Line 1',
            'value' => function ($model) {
                return isset($model->companyAdmin) ? $model->companyAdmin->address_line1 : 'Not Set';
            }
        ],
        [
            'label' => 'Address Line 2',
            'value' => function ($model) {
                return isset($model->companyAdmin) ? $model->companyAdmin->address_line2 : 'Not Set';
            }
        ],
        [
            'label' => 'City',
            'value' => function ($model) {
                return isset($model->companyAdmin) ? $model->companyAdmin->city : 'Not Set';
            }
        ],
        
        [
            'label' => 'Postal Code',
            'value' => function ($model) {
                return isset($model->companyAdmin) ? $model->companyAdmin->pincode : 'Not Set';
            }
        ],
        [
            'label' => 'Country',
            'value' => function ($model) {
                return isset($model->companyAdmin) ? $model->companyAdmin->getCountry() : 'Not Set';
            }
        ],
        'created_on:datetime'
    ]
])?>
			</div>
				</div>
			</div>
			<?php
// echo FeedWidget::widget([
// 'user_id' => $model->id
// ]);
?>

		</div>
		<div class="col-md-3">

			<div class="panel-group">
				<div class="panel panel-default">
					<div class="panel-heading">Permissions</div>
					<div class="panel-body">
						<?php
    
    $form = TActiveForm::begin([
        'id' => 'permission-form',
        'enableClientValidation' => true
    ]);
    ?>
								<?php
        
        $permissions = CompanyAdmin::getPermissionOptions();
        $addPer = [];
        if (isset($model->companyAdmin) && ! empty($model->companyAdmin->permission)) {
            $addPer = json_decode($model->companyAdmin->permission);
        }
        foreach ($permissions as $key => $val) {
            $checked = in_array($key, $addPer) ? "checked = 'true'" : '';
            ?>
								    <input class="checkbox-align" type="checkbox"
							name="CompanyAdmin[permission][]" <?=$checked?>
							<?=User::isDisabled($model->id)?> value="<?=$key?>"><?=$val?> </br>
								<?php
        }
        
        ?>
        		<?php
        
        if (\Yii::$app->user->id != $model->id) {
            ?>
        
                <?=Html::submitButton(Yii::t('app', 'Update Permission'), ['id' => 'permission-form-submit','class' => 'btn btn-primary btn-xs pull-right'])?>
				
				<?php
        }
        TActiveForm::end();
        ?>
					</div>
				</div>
				
				<?php
    
    if (\Yii::$app->user->id != $model->id) {
        ?>

				<div class="panel panel-default">
					<div class="panel-heading">
						Status <span class="pull-right"><?=$model->getStateBadge()?></span>
					</div>
					<div class="panel-body"> 
						<?php
        $form = TActiveForm::begin([
            'id' => 'permission-form',
            'enableClientValidation' => true
        ]);
        ?>
                        
                        <?=$form->field($model, 'state_id', ['template' => '{input}'])->dropDownList($model->getStateOptions());?>
                        
                        
           				
                
                        <?=Html::submitButton(Yii::t('app', 'Update Status'), ['id' => 'state-form-submit','class' => 'btn btn-primary btn-xs pull-right'])?>
        				
        				<?php
        
        TActiveForm::end();
        ?>
    
					</div>
				</div>
				
				<?php
    }
    ?>
				
			</div>
		</div>
	</div>


</div>
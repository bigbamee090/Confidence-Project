<?php
use app\models\User;
use yii\helpers\Url;
use app\components\TActiveForm;
use yii\helpers\Html;
use app\models\CompanyAdmin;
use app\models\Company;
/* @var $this yii\web\View */
/* @var $model app\models\Company */

/* $this->title = $model->label() .' : ' . $model->id; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Companies'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="panel">
		<div class="company-view panel-body">


			<div class="page-head">
				
				   <?php 
// $model->getStateBadge() ?>
				<?php
    
    echo \app\components\PageHeader::widget([
        'model' => $model
    ]);
    ?>
			</div>
			<!-- panel-menu -->
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="col-md-9">
				<div class="panel">
					<div class="panel-body">
    <?php
    
    echo \app\components\TDetailView::widget([
        'id' => 'company-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            'first_name',
            'last_name',
            'email',
            'contact_no',
            'company_name',
            'registration_number',
            'vat_registration_number',
            [
                'attribute' => 'country',
                'value' => function ($data) {
                    return $data->getCountry();
                }
            ],
            [
                'attribute' => 'plan_id',
                'label' => 'Plan Name',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->planType->title;
                }
            ],
            [
                'attribute' => 'plan_id',
                'label' => 'Discount',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->planType->percent;
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
                'attribute' => 'type_id',
                'value' => $model->getType()
            ],
            'created_on:datetime',
            'updated_on:datetime'
        ]
    
    ])?>
    


		</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="panel-group">


					<div class="panel panel-default">
						<div class="panel-heading">
							Plans <span class="pull-right">Current Plan : <span
								class="label label-info"><?=$model->getCompanyPlan()->title?></span></span>
						</div>
						<div class="panel-body"> 
						<?php
    $form = TActiveForm::begin([
        'id' => 'plan-form',
        'enableAjaxValidation' => false
    ]);
    ?>
                        
                        <?=$form->field($model, 'plan_id', ['template' => '{input}'])->dropDownList($model->getPlans());?>
                        
                        <?=Html::submitButton(Yii::t('app', 'Update Plan'), ['id' => 'plan-form-submit','class' => 'btn btn-primary btn-xs pull-right'])?>
        				
        				<?php
            
            TActiveForm::end();
            ?>
    
					</div>
					</div>


					<div class="panel panel-default">
						<div class="panel-heading">
							Status <span class="pull-right"><?=$model->getStateBadge()?></span>
						</div>
						<div class="panel-body">
						<?php
    $form = TActiveForm::begin([
        'id' => 'company-form',
        'enableAjaxValidation' => false
    ]);
    ?>
								<?php
        $states = Company::getStateOptions();
        foreach ($states as $key => $val) {
            $checked = ($model->state_id == $key) ? "checked = 'true'" : '';
            ?>
								    <input class="checkbox-align" type="radio"
								name="Company[state_id]" <?=$checked?>
								<?=User::isDisabled($model->createdBy->id)?>
								value="<?=$key?>"><?=$val?> </br>
								<?php
        }
        
        ?>
                <?=Html::submitButton(Yii::t('app', 'Update'), ['id' => 'company-form-submit','class' => 'btn btn-primary btn-xs pull-right'])?>
				
				<?php
    
    TActiveForm::end();
    ?>
					</div>
					</div>




				</div>
			</div>
		</div>
	</div>



</div>

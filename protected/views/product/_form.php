<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use kartik\select2\Select2;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="panel-heading">
                            <?php
                            
                            echo strtoupper(Yii::$app->controller->action->id);
                            ?>
                        </header>
<div class="panel-body">

    <?php
    $form = TActiveForm::begin([
        'id' => 'product-form'
    ]);
    ?>





<div class="col-md-6">

	
		 <?php

echo $form->field($model, 'title')
    ->textInput([
    'maxlength' => 256
])
    ->label('Product Name')?>
	 		


		 <?php
/* echo $form->field($model, 'description')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'description')->textarea(['rows' => 6]); */
?>
	 		


		 <?php

echo $form->field($model, 'code')->textInput([
    'maxlength' => 125
])?>
	 		


		 <?php

echo $form->field($model, 'price')->textInput([
    'maxlength' => 125
])?>
	 		


		 <?php
echo $form->field($model, 'actual_quantity')->textInput([
    'maxlength' => 125
])?>
	 		

	</div>
	<div class="col-md-6">

		 <?php

echo $form->field($model, 'category_id')
    ->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::Map(Category::find()->select('id,title')
        ->where([
        'state_id' => Category::STATE_ACTIVE
    ])
        ->all(), 'id', 'title'),
    'language' => 'en',
    'showToggleAll' => false,
    'options' => [
        'placeholder' => 'Select Category'
    ],
    'pluginOptions' => [
        'allowClear' => true,
        'multiple' => false
    
    ]
])
    ->label('Category');
?>

		 <?php
echo $form->field($model, 'class_id')->dropDownList($model->getClassOptions(), [
    'prompt' => '-------'
])?>
	 			</div>




	<div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'product-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>
	</div>

    <?php
    
    TActiveForm::end();
    ?>

</div>

<?php
use app\components\TActiveForm;
use yii\helpers\Html;
?>
<header class="panel-heading">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="panel-body">

    <?php
    $form = TActiveForm::begin([
        'layout' => 'horizontal',
        'id' => 'page-form'
    ]);
    ?>





		 <?php echo $form->field($model, 'title')->textInput() ?>



		 <?php echo  $form->field($model, 'description')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'full' ] ); //$form->field($model, 'description')->textarea(['rows' => 6]); //$form->field($model, 'description')->widget(kartik\widgets\Html5Input::className(),[]); ?>

<div class="form-group"></div>
		 <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions()) ?>



		 <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions()) ?>



	   <div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['name'=>'page-button','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>

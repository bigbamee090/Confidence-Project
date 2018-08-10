<?php
use app\components\TActiveForm;
use yii\helpers\Html;
use app\models\Setting;

/* @var $this yii\web\View */
/* @var $model app\models\BulkMessage */
/* @var $form yii\widgets\ActiveForm */
$i = 1;
$defaultConfig = (Setting::getDefaultConfig())['core'];
?>
<div class="panel-body">
    <?php
    $form = TActiveForm::begin([
        'layout' => 'horizontal',
        'id' => 'setting-update-form',
        'action' => [
            '/setting/update',
            'id' => $model->id
        ],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false
    ]);
    ?>	
		
		<div class="col-md-12">
		<div class="form-group">
			<div class="col-md-4">
				<h4>Configration Title</h4>
			</div>
			<div class="col-md-4">
				<input type="text" id="setting-title" class="form-control"
					name="Setting[title]" placeholder="Value"
					value="<?= \Yii::$app->settings->$key->title ?>">
			</div>
		</div>
	</div>

	<div id="configValueContainer"> 
	   <?php
    
    foreach (\Yii::$app->settings->$key->asArray as $configKey => $configDetail) {
        ?>
				<div class="col-md-12 config-value single-config-<?= $i ?>">
			<div class="form-group">
				<div class="col-md-4">
							<?php if( (YII_ENV == "dev") && ( !array_key_exists($key, $defaultConfig)  ) ) { ?>
								<input type="text" required id="setting-keyName-<?= $i ?>"
						class="form-control settingKey" name="Setting[keyName][<?= $i ?>]"
						placeholder="Name" value="<?= $configKey ?>">
					<p class="text-danger"><?= \Yii::t("app", "Only Character or Underscore is allowed.") ?></p>
							<?php } else { ?>
								<h4><?= $configKey ?></h4>
							<?php } ?>
						</div>
				<div class="col-md-4">
							<?php
        
        if (array_key_exists($key, $defaultConfig)) {
            echo Setting::generateField($configKey, $configDetail);
            ?>
								
									<?php } else { ?>
								<input type="text" id="setting-keyValue-<?= $i ?>" desabled
						class="form-control" name="Setting[keyValue][<?= $i ?>]"
						placeholder="Value" value="<?= $configDetail['value']?>">
							<?php } ?>
						</div>
								<?php if( (YII_ENV == "dev") && ( !array_key_exists($key, $defaultConfig)  ) ): ?>
											<?php if( $configKey != "title" ): ?>
											<div class="col-md-3">
					<input type="checkbox" id="setting-keyRequired-<?= $i ?>" class=""
						name="Setting[keyRequired][<?= $i ?>]"> Required
				</div>
				<div class="col-md-1">
					<a class="btn btn-primary delete-field" data-id="<?= $i ?>"
						href="javascript:;"> <i class="fa fa-minus"></i></a>
				</div>
						<?php endif; ?>
						<?php endif; ?>
					</div>
		</div>					
	  <?php $i++; } ?>
	  </div>

	<div class="form-group">
		<div class="col-md-12 text-right btn-space-bottom">
       	 	<?= Html::submitButton( Yii::t('app', 'Update'), ['id'=> 'bulk-message-form-submit','class' => 'btn btn-primary']) ?>
    	</div>
	</div>
    <?php TActiveForm::end(); ?>
</div>

<script type="text/javascript">
$(document).on("change", ".setKeyType", function (e) {
	var type = $(this).val();
	var dataId = $(this).attr("data-id");
	var html = setKeyType(type, dataId);
	$("#setting-keyValue-container-"+dataId).html(html);
});
$(".add-more").on("click", function () {
	var id = $(".config-value").length;
	$("#configValueContainer").append(renderHtml (parseInt(id) +1));
});
$(document).on("click", ".delete-field",function () {
	var id = $(this).attr("data-id");
	$(".single-config-"+id).remove();
});
$(document).on("keyup", ".settingKey",function (e) {
	var check = /^[_a-zA-Z]*$/;
	if (!check.test($(this).val())) {
		alert("<?= \Yii::t('app', 'Only Character or Underscore is allowed.'); ?>");
		
		$(this).val("");
		e.preventDefault();
	}
});
function renderHtml (id) {
	var html = '<div class="col-md-12 config-value single-config-'+id+'"><div class="form-group">';
	html += '<div class="col-md-3">';
	html += '<input type="text" required id="setting-keyName-'+id+'" class="form-control settingKey" name="Setting[keyName]['+id+']" placeholder="Name">';
	html += '<p class="text-danger"><?= \Yii::t("app", "Only Character or Underscore is allowed.") ?></p></div>';
	
	
	html += '<div class="col-md-3"><div class="form-group field-setting-keyType-'+id+' has-success">';
	html += '<select id="setting-keyType-'+id+'" class="setKeyType" name="Setting[keyType]['+id+']" data-id="'+id+'" aria-invalid="false">';
		<?php foreach ( $model->getTypeOptions() as $key => $value) { ?>
	html += '<option value="<?= $key ?>"><?= $value ?></option>';
		<?php } ?>
	html += '</select></div></div>';
	
	html += '<div class="col-md-3"  id="setting-keyValue-container-'+id+'">';
	html += '<input type="text" required id="setting-keyValue-'+id+'" class="form-control" name="Setting[keyValue]['+id+']" placeholder="Value"></div>';
	html += '<div class="col-md-2"><input type="checkbox" id="setting-keyRequired-'+id+'" class="" name="Setting[keyRequired]['+id+']"> Required</div>';
	html += '<div class="col-md-1 text-right"><a class="btn btn-primary delete-field" data-id="'+id+'" href="javascript:;"> <i class="fa fa-minus"></i></a></div>';
	html += '</div></div>';
	return html;
}

function setKeyType(type, dataId) {
	var html = "";
	switch(type) {
		case "<?= Setting::KEY_TYPE_STRING ?>":
				html += '<input type="text" required="" id="setting-keyValue-'+dataId+'" class="form-control" name="Setting[keyValue]['+dataId+']" placeholder="Value">';
			break;
		case "<?= Setting::KEY_TYPE_BOOL ?>":
				html += '<input type="checkbox" id="setting-keyValue-'+dataId+'" class="form-control" name="Setting[keyValue]['+dataId+']" placeholder="Value">';
			break;
		case "<?= Setting::KEY_TYPE_INT ?>":
				html += '<input type="number" id="setting-keyValue-'+dataId+'" class="form-control" name="Setting[keyValue]['+dataId+']" placeholder="Value">';
			break;
		case "<?= Setting::KEY_TYPE_EMAIL ?>":
			html += '<input type="email" id="setting-keyValue-'+dataId+'" class="form-control" name="Setting[keyValue]['+dataId+']" placeholder="Value">';
		break;
	}	
	return html;
}
</script>

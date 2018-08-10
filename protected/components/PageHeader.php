<?php

namespace app\components;

use \yii\helpers\Inflector;
use Yii;
use app\models\User;

class PageHeader extends TBaseWidget {
	public $title;
	public $subtitle;
	public $model;
	public $showActions = true;
	public $showAdd = true;
	public function run() {
		if ($this->title === null) {
			if ($this->model != null) {
				$this->title = ( string ) $this->model;
			} else
				$this->title = Inflector::pluralize ( Inflector::camel2words ( Yii::$app->controller->id ) );
		}
		if ($this->subtitle === null) {
			
			$this->subtitle = Inflector::camel2words ( Yii::$app->controller->action->id );
		}
		$this->renderHtml ();
	}
	public function renderHtml() {
		?>


<div class="page-head">
	<h3 class="m-b-less"><?php echo \yii\helpers\Html::encode($this->title);?></h3>
           <?php if ($this->model != null) echo $this->model->getStateBadge()?>

			<?php if($this->showActions):?>
			<div class="state-information">
		
		       <?php if (!User::isGuest())  echo \app\components\TToolButtons::widget(); ?>
				
		</div>
			<?php endif;?>

	</div>
<!-- panel-menu -->



<?php
	}
}
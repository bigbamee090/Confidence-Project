<?php
use yii\helpers\Html;
use app\models\User;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<?=$this->render('header.php');?>
<tr>
	<td align="left"
		style="font-family: Lato, sans-serif; padding-top: 30px; padding-bottom: 0; color: #333333;"><h3
			style="margin: 0; font-weight: 500; font-size: 19px;">Hi <?php

echo Html::encode($model->full_name)?>,</h3></td>
</tr>

<tr>

	<td align="left">
		<p
			style="font-size: 14px; padding: 0 0px 23px; border-bottom: 1px solid #ececec; text-align: left; color: #666; margin-bottom: 8px;">Your
			<?php

echo 'You have been ' . $model->getState() . 'From ' . \yii::$app->name . "site";

?></p>
		<p
			style="font-size: 14px; padding: 0 0px 23px; border-bottom: 1px solid #ececec; text-align: left; color: #666; margin-bottom: 8px;">
			</br> </br>
			</br> </br>
			</br> </br>
		</p>

		<p></p>

	</td>
</tr>

<?=$this->render('footer.php');?>
  
  
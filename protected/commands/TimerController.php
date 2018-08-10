<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\commands;

use app\components\Settings;
use app\models\EmailQueue;
use app\modules\backup\helpers\MysqlBackup;
use yii\console\Controller;

class TimerController extends Controller {
	const MAX_ATTEMPTS = 5;
	public function actionEmail() {
		$mails = EmailQueue::find ()->limit ( 50 )->orderBy ( 'id asc' )->all ();
		foreach ( $mails as $mail ) {
			$mail->sendNow ();
		}
		return true;
	}
	public function log($string) {
		echo $string . PHP_EOL;
	}
	public function actionBackup() {
		$sql = new MysqlBackup ();
		$settings = \Yii::$app->settings;
		$backup_interval = $settings->getValue ( 'db_backup', '7' );
		$last_backup = $settings->getValue ( 'last_backup', '2017-10-10' );
		$last_backup_time = strtotime ( $last_backup );
		$next_backup_time = strtotime ( "+" . $backup_interval . "days", $last_backup_time );
		$this->log ( __FUNCTION__ . ":last_backup_time = $last_backup_time  and next_backup_time=$next_backup_time" );
		if (time () >= $next_backup_time) {
			$settings->setValue ( 'last_backup', date ( "Y-m-d H:i:s" ) );
			if (! $sql->startBackup ()) {
				$this->log ( __FUNCTION__ . ":Started" );
			}
			$tables = $sql->getTables ();
			foreach ( $tables as $tableName ) {
				$sql->getColumns ( $tableName );
			}
			foreach ( $tables as $tableName ) {
				$sql->getData ( $tableName );
			}
			
			$sqlFile = $sql->endBackup ();
			$this->log ( __FUNCTION__ . ":Finished : " . $sqlFile );
			return $sqlFile;
		}
	}
}



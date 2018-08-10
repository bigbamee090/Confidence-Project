<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use app\components\TController;
use app\models\User;
use yii\filters\AccessControl;
use app\models\Setting;

class DashboardController extends TController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'default-data'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ]
                
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $this->updateMenuItems();
        $smtpConfig = isset(\Yii::$app->settings) ? \Yii::$app->settings->smtp : null;
        if (empty($smtpConfig)) {
            Setting::setDefaultConfig();
        }
        $this->layout = 'main';
        if (User::isAdmin()) {
            $this->layout = 'main';
            return $this->render('index');
        } else {
            $this->layout = 'company';
            return $this->render('company-index');
        }
    }

    public static function MonthlySignups()
    {
        $date = new \DateTime();
        $date->modify('-12  months');
        $last = $date;
        $dates = array();
        $count = array();
        for ($i = 1; $i <= 12; $i ++) {
            $date->modify('+1 months');
            $month = $date->format('Y-m');
            
            $count[$month] = (int) User::find()->where([
                'like',
                'created_on',
                $month
            ])->count();
        }
        return $count;
    }

    public function actionDefaultData()
    {
        Setting::setDefaultConfig();
        $msg = 'Done !! Setting reset succefully!!!';
        \Yii::$app->session->setFlash('success', $msg);
        return $this->redirect(\Yii::$app->request->referrer);
    }
}

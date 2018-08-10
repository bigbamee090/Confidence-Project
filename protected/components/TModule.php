<?php
namespace app\components;

use app\models\User;
use Yii;

/**
 * Tmodule definition class
 */
class TModule extends \yii\base\Module
{

    public $allowGlobalAccess = false;

    const NAME = '';

    public static function isAdmin()
    {
        $module = Yii::$app->getModule(self::NAME);
        if ($module)
            return User::isAdmin() || $module->allowGlobalAccess;
        return User::isAdmin();
    }

    public static function isManager()
    {
        $module = Yii::$app->getModule(self::NAME);
        if ($module)
            return User::isManager() || $module->allowGlobalAccess;
        return User::isManager();
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (\Yii::$app instanceof \yii\web\Application) {
            $this->layoutPath = Yii::$app->view->theme->basePath . '/views/layouts/';
        }
    }

    public static function subNav()
    {
        return [];
        // TController::addMenu(\Yii::t('app', 'Main'), '#', 'list-alt ', (! User::isGuest()), [ // TController::addMenu(Yii::t('app', ' Page'), '//support/', 'envelope-o', (User::isAdmin())),
        
        // ]);
    }

    public static function dbFile()
    {
        return [];
    }

    public static function getRules()
    {
        return [];
    }

    public static function getExts()
    {
        return [];
    }

    public static function getPkgs()
    {
        return [];
    }
}
<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\jui\Tabs;
use yii\web\Controller;
use yii\web\View;

class TController extends Controller
{

    public $allowedIPs = [
        '127.0.0.1',
        '::1'
    ];

    public $layout = '//guest-main';

    public $menu = [];

    public $top_menu = [];

    public $side_menu = [];

    public $user_menu = [];

    public $tabs_data = null;

    public $tabs_name = null;

    public $dryRun = false;

    public $assetsDir = '@webroot/assets';

    public $ignoreDirs = [];

    public $nav_left = [];

    // nav-left-medium';
    private $_pageCaption = 'Confidence Pharmacy';

    private $_pageDescription = 'Confidence Pharmacy is a pharmacy suppliers of pharmacy products to medical professionals. ';

    private $_pageKeywords = 'yii, framework, php';

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null
            ]
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'index',
                    'view',
                    'create',
                    'update',
                    'delete'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update'
                        ],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'delete'
                        ],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return User::isAdmin();
                        }
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => [
                        'post'
                    ]
                ]
            ]
        ];
    }

    public static function cleanRuntimeDir($dir, $delete = false)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            
            $objects = FileHelper::findFiles($dir);
            foreach ($objects as $object) {
                if (unlink($object)) {
                    Yii::$app->session->setFlash('runtime_clean', Yii::t('app', 'Runtime cleaned'));
                }
            }
            reset($objects);
            
            if ($delete) {
                FileHelper::removeDirectory($dir);
            }
        }
    }

    public function cleanAssetsDir()
    {
        $assetsDirs = glob(Yii::getAlias($this->assetsDir) . '/*', GLOB_ONLYDIR);
        foreach ($assetsDirs as $dir) {
            if (in_array(basename($dir), $this->ignoreDirs)) {
                continue;
            }
            if (! $this->dryRun) {
                FileHelper::removeDirectory($dir);
            }
        }
        Yii::$app->session->setFlash('assets_clean', Yii::t('app', 'Assets cleaned'));
    }

    public function processSEO($model = null)
    {
        if (\yii::$app->getModule('seoManager')) {
            \yii::$app->seomanager->processSEO($this->id, $this->action->id);
        }
        
        if ($model && $model instanceof TActiveRecord && ! $model->isNewRecord) {
            $this->_pageCaption = Html::encode($model->label()) . ' - ' . Html::encode($model) . ' | ' . $this->_pageCaption;
            
            if ($model->hasAttribute('content'))
                $this->_pageDescription = substr(strip_tags($model->content), 0, 150);
            else if ($model->hasAttribute('description'))
                $this->_pageDescription = substr(strip_tags($model->description), 0, 150);
        } elseif ($this->action->id == 'index' && $this->id == 'site') {
            $this->_pageCaption = $this->_pageCaption;
        } else {
            $this->_pageCaption = Inflector::pluralize(Inflector::camel2words(Yii::$app->controller->id)) . '-' . Inflector::camel2words($this->action->id) . ' | ' . $this->_pageCaption;
            
            // if ( isset($this->module))
            // / $this->_pageCaption = Inflector::camel2words ($this->module->id) . ' | ' . $this->_pageCaption;;
        }
        $this->getView()->registerMetaTag([
            'name' => 'description',
            'content' => $this->_pageDescription
        ]);
        $this->getView()->registerMetaTag([
            'name' => 'keywords',
            'content' => $this->_pageKeywords
        ]);
        $this->getView()->registerMetaTag([
            'name' => 'author',
            'content' => '@toxsl'
        ]);
        
        $this->getView()->title = $this->_pageCaption;
        
        $this->getView()->registerLinkTag([
            'rel' => 'canonical',
            'href' => Url::canonical()
        ]);
        
        /*
         * $this->getView ()->registerMetaTag ( [
         * 'name' => 'google-site-verification',
         * 'content' => '4HYmNaNJUEdYyjaOIx39TschfK4RrGgmw80YodJgjZw'
         * ] );
         */
        
        /*
         * $this->getView ()->registerJs ( "
         * (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
         * (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
         * m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
         * })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
         *
         * ga('create', 'UA-97187503-2', 'auto');
         * ga('send', 'pageview');
         *
         * ", View::POS_END, 'google-analytics-code' );
         */
    }

    protected function checkIPAccess()
    {
        $ip = Yii::$app->getRequest()->getUserIP();
        foreach ($this->allowedIPs as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && ! strncmp($ip, $filter, $pos))) {
                return true;
            }
        }
        Yii::warning('Access to Gii is denied due to IP address restriction. The requested IP is ' . $ip, __METHOD__);
        
        return false;
    }

    public function beforeAction($action)
    {
        if (! file_exists(DB_CONFIG_FILE_PATH)) {
            if ($this->module->id != 'installer') {
                $this->redirect([
                    "/installer"
                ]);
                return false;
            }
        }
        if (! \Yii::$app->user->isGuest) {
            if (User::isAdmin()) {
                $this->layout = 'main';
            } else {
                $this->layout = 'company';
            }
        }
        
        return parent::beforeAction($action);
    }

    public function actionPdf()
    {
        Yii::$app->response->format = 'pdf';
        $this->layout = '//print';
        return $this->render('myview', []);
    }

    public function startPanel($name = 'tabpanel1')
    {
        $this->tabs_name = $name;
        $this->tabs_data = array();
    }

    public function addPanel($title, $objects, $relation, $model = null, $module = null, $addMenu = true)
    {
        $view = Inflector::camel2id($relation);
        
        if ($objects) {
            if ($objects instanceof ActiveDataProvider)
                $dataProvider = $objects;
            elseif ($objects instanceof ActiveQuery)
                $dataProvider = new ActiveDataProvider([
                    'query' => $objects
                ]);
            else {
                $function = 'get' . ucfirst($objects);
                $query = $model->$function();
                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC
                        ]
                    ]
                ]);
                
                $modelClass = $dataProvider->query->modelClass;
                
                if (strstr($modelClass, 'modules')) {
                    $len = strpos($modelClass, 'modules') + strlen('modules') + 1;
                    $module = substr($modelClass, $len, strpos($modelClass, 'models') - $len - 1);
                }
                
                $type = get_class($model);
                
                // $content = $this->renderPartial('/'.$view.'/_grid',['dataProvider'=>$dataProvider,'searchModel'=> null]);
                
                if (isset($module))
                    $view = $module . '/' . $view;
                $this->tabs_data[] = array(
                    'label' => $title . '(' . $dataProvider->totalCount . ')',
                    'url' => [
                        "/$view/ajax",
                        'type' => "$type",
                        'function' => "$objects",
                        'id' => $model->id,
                        'addMenu' => $addMenu
                    ],
                    'active' => count($this->tabs_data) == 0 ? true : false
                );
            }
        }
    }

    public function endPanel()
    {
        $id = 'project-' . $this->tabs_name;
        
        echo Tabs::widget([
            'items' => $this->tabs_data,
            'options' => [
                'id' => $id,
                'class' => 'ui-tabs ui-widget ui-widget-content',
                'style' => "display:none;"
            ] /*
               * 'itemOptions' => [
               * 'class' => 'ui-state-default ui-corner-top',
               * ]
               */
        ]);
        $this->getView()->registerJs("$( '#$id').show()", View::POS_READY, 'project-tabs');
    }

    public function actionAjax($type, $id, $function, $grid = '_ajax-grid')
    {
        $model = $type::findOne([
            'id' => $id
        ]);
        
        if (! empty($model)) {
            if (! ($model->isAllowed()))
                throw new \yii\web\HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));
            $function = 'get' . ucfirst($function);
            $dataProvider = new ActiveDataProvider([
                'query' => $model->$function()
            ]);
            
            return $this->renderPartial($grid, [
                'dataProvider' => $dataProvider,
                'searchModel' => null
            ]);
        }
    }

    public function actionMass($action = 'delete', $model)
    {
        \Yii::$app->response->format = 'json';
        $response['status'] = 'NOK';
        $Ids = Yii::$app->request->post('ids', []);
        $model = $model;
        
        if (! empty($Ids)) {
            foreach ($Ids as $Id) {
                $model = $model::findOne($Id);
                if (! empty($model) && ($model instanceof ActiveRecord)) {
                    if ($action == 'delete') {
                        if (($model instanceof User) && ($model->id == \Yii::$app->user->id)) {
                            $response['status'] = 'NOK';
                        } else {
                            $model->delete();
                        }
                    }
                }
            }
            $response['status'] = 'OK';
        }
        
        return $response;
    }

    public function render($view, $params = [])
    {
        if (array_key_exists('model', $params)) {
            $this->processSEO($params['model']);
        } else
            $this->processSEO();
        return parent::render($view, $params);
    }

    protected function updateMenuItems($model = null)
    {
        switch (\Yii::$app->controller->action->id) {
            
            default:
            case 'index':
                {
                    $this->menu['clear'] = [
                        'label' => '<span class=" glyphicon glyphicon-remove"></span>',
                        'title' => Yii::t('app', 'Clear'),
                        'url' => [
                            'clear'
                            /* 'id' => $model->id */
                        ],
                        'visible' => User::isAdmin()
                    ];
                }
                break;
            case 'view':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => \Yii::t('app', 'Add'),
                        'url' => [
                            'add'
                        ],
                        'visible' => User::isAdmin()
                    ];
                    $this->menu['manage'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => 'Manage',
                        'url' => array(
                            'index'
                        ),
                        'visible' => User::isAdmin()
                    ];
                }
                break;
        }
    }

    public static function addmenu($label, $link, $icon, $visible = null, $list = null)
    {
        if (! $visible)
            return null;
        $item = [
            'label' => '<i
							class="fa fa-' . $icon . '"></i> <span>' . $label . '</span>',
            'url' => [
                $link
            ]
        ];
        if ($list != null) {
            $item['options'] = [
                'class' => 'menu-list'
            ];
            
            $item['items'] = $list;
        }
        
        return $item;
    }

    public function renderNav()
    {
        $nav = [
            self::addMenu(Yii::t('app', 'Dashboard'), '//dashboard/index', 'home', ! User::isGuest()),
            // self::addMenu(Yii::t('app', 'User'), '//user', 'user', (User::isAdmin())),
            
            self::addMenu(Yii::t('app', 'Companies'), '//company/index', 'building', User::isAdmin()),
            self::addMenu(Yii::t('app', 'Users'), '//user/index', 'building', User::isAdmin()),
            
            'Manage' => self::addMenu(Yii::t('app', 'Manage'), '#', 'tasks', User::isAdmin(), [
                self::addMenu(Yii::t('app', 'Settings'), '//setting/index', 'tasks', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Media'), '//media/', 'picture-o', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Banners'), '//banner/', 'picture-o', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Feeds'), '//feed/', 'rss', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Notices'), '//notice/index', 'tasks', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Login History'), '//login-history/index', 'history', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Logs'), '//log/index', 'file', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Email Queue'), '//email-queue/index', 'envelope', User::isAdmin())
            
            ]),
            self::addMenu(Yii::t('app', 'Plans'), '//plan-type/index', 'fa fa-th', User::isAdmin()),
            self::addMenu(Yii::t('app', 'Categories'), '//category/index', 'cubes', User::isAdmin()),
            self::addMenu(Yii::t('app', 'Products'), '//product/index', 'fas fa-cart-plus', User::isAdmin()),
            self::addMenu(Yii::t('app', 'Orders'), '//order/index', 'fas fa-cart-plus', User::isAdmin()),
            self::addMenu(Yii::t('app', 'Page'), '//page/', 'file-text', ! User::isGuest())
            
            // self::addMenu(Yii::t('app', 'Backup'), '//backup/', 'download', (User::isAdmin()))
        ];
        
        $nav = array_merge($nav, $this->renderModuleNev());
        return $nav;
    }

    public function renderCompanyNav()
    {
        $nav = [
            self::addMenu(Yii::t('app', 'Dashboard'), '//dashboard/index', 'home', true),
            
            self::addMenu(Yii::t('app', 'Users'), '//user/company-index', 'users', User::checkPermision(User::PERMISSION_SUPER_USER)),
            
            // self::addMenu(Yii::t('app', 'Admins'), '//user/admin', 'users', User::checkPermision(User::PERMISSION_SUPER_USER)),
            // self::addMenu(Yii::t('app', 'Admin Managers'), '//user/admin-manager', 'users', User::checkPermision(User::PERMISSION_SUPER_USER)),
            // self::addMenu(Yii::t('app', 'Prescribers'), '//user/prescriber', 'users', User::checkPermision(User::PERMISSION_SUPER_USER)),
            
            self::addMenu(Yii::t('app', 'Products'), '//product/listview', 'cubes', true),
            
            self::addMenu(Yii::t('app', 'Orders'), '//order/index', 'shopping-cart', true)
        ];
        
        $nav = array_merge($nav, $this->renderModuleNev());
        return $nav;
    }

    public function renderModuleNev()
    {
        $config = include (DB_CONFIG_PATH . 'web.php');
        $nav = [];
        if (! empty($config['modules'])) {
            foreach ($config['modules'] as $modules) {
                $class = isset($modules['class']) ? $modules['class'] : null;
                if (class_exists("$class") && method_exists($class, 'subNav')) {
                    if (! empty($class::subNav())) {
                        $nav[] = $class::subNav();
                    }
                }
            }
        }
        return $nav;
    }
}

<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use Yii;
use app\models\Company;
use app\models\search\Company as CompanySearch;
use app\components\TController;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;
use yii\web\HttpException;
use app\components\TActiveForm;
use app\models\InvoiceAddress;
use app\models\DeliveryAddress;
use app\models\CompanyAdmin;
use yii\web\Response;
use app\models\CompanyPrescriber;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends TController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'rules' => [
                    [
                        'actions' => [
                            'clear'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'add',
                            'view',
                            'update',
                            'delete',
                            'ajax',
                            'mass'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'change-address'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (User::isCompanyAdmin() || User::isCompanyManager() || User::isCompanyPrescriber());
                        }
                    ]
                ]
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => [
                        'post'
                    ]
                ]
            ]
        ];
    }

    public function actionChangeAddress($id)
    {
        $model = DeliveryAddress::findOne($id);
        
        if (! empty($model)) {
            $model->state_id = DeliveryAddress::STATE_ACTIVE;
            DeliveryAddress::updateAll([
                'state_id' => DeliveryAddress::STATE_INACTIVE
            ], "company_id = {$model->company_id}");
            if (! $model->save()) {
                \Yii::$app->getSession()->setFlash('danger', $model->getErrorsString());
            }
        }
        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Lists all Company models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Company model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        
        $post = \Yii::$app->request->post();
        if (! empty($post)) {
            if ($model->load($post)) {
                
                if (isset($post['Company']['state_id']) && ($post['Company']['state_id'] != Company::STATE_ACTIVE)) {
                    
                    $cAdmins = CompanyAdmin::find()->select('user_id')
                        ->where([
                        'company_id' => $id
                    ])
                        ->all();
                    
                    if (! empty($cAdmins)) {
                        foreach ($cAdmins as $admin) {
                            $user = User::findOne([
                                'id' => $admin->user_id
                            ]);
                            if (! empty($user)) {
                                $user->state_id = $post['Company']['state_id'];
                                $user->save(false, [
                                    'state_id'
                                ]);
                            }
                        }
                    }
                    $cPrescriber = CompanyPrescriber::find()->select('user_id')
                        ->where([
                        'company_id' => $id
                    ])
                        ->all();
                    
                    if (! empty($cPrescriber)) {
                        foreach ($cPrescriber as $prescriber) {
                            $user = User::findOne([
                                'id' => $prescriber->user_id
                            ]);
                            if (! empty($user)) {
                                $user->state_id = $post['Company']['state_id'];
                                $user->save(false, [
                                    'state_id'
                                ]);
                            }
                        }
                    }
                }
                $model->save();
                
                return $this->redirect(\Yii::$app->request->referrer);
            }
        }
        
        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd()
    {
        $model = new Company();
        $model->loadDefaultValues();
        $model->state_id = Company::STATE_ACTIVE;
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post) && $model->save()) {
            return $this->redirect($model->getUrl());
        }
        $this->updateMenuItems();
        return $this->render('add', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    // public function actionUpdate($id)
    // {
    // $model = $this->findModel($id);
    
    // $post = \yii::$app->request->post();
    // if (\yii::$app->request->isAjax && $model->load($post)) {
    // \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    // return TActiveForm::validate($model);
    // }
    // if ($model->load($post) && $model->save()) {
    // return $this->redirect($model->getUrl());
    // }
    // $this->updateMenuItems($model);
    // return $this->render('update', [
    // 'model' => $model
    // ]);
    // }
    public function actionUpdate($id)
    {
        $company = Company::findOne($id);
        
        $user = User::findOne($company->created_by_id);
        if (empty($user)) {
            $user = new User();
        }
        
        // $invoiceAddress = InvoiceAddress::findOne([
        // 'created_by_id' => $company->created_by_id
        // ]);
        // if (empty($invoiceAddress)) {
        // $$invoiceAddress = new InvoiceAddress();
        // }
        // $deliveryAddress = DeliveryAddress::findOne([
        // 'created_by_id' => $company->created_by_id
        // ]);
        // if (empty($deliveryAddress)) {
        // $deliveryAddress = new DeliveryAddress();
        // }
        $companyAdmin = CompanyAdmin::findOne([
            'created_by_id' => $company->created_by_id
        ]);
        if (empty($companyAdmin)) {
            $companyAdmin = new CompanyAdmin();
        }
        $flag = true;
        $post = \Yii::$app->request->post();
        
        if (Yii::$app->request->isAjax && $user->load($post) && $company->load($post) && $invoiceAddress->load($post) && $deliveryAddress->load($post) && $companyAdmin->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validateMultiple([
                $user,
                $company,
                $invoiceAddress,
                $deliveryAddress,
                $companyAdmin
            ]);
        }
        
        if ($user->load($post) && $company->load($post) && $invoiceAddress->load($post) && $deliveryAddress->load($post) && $companyAdmin->load($post)) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $user->scenario = 'client';
            try {
                
                $companyAdmin->first_name = $user->first_name;
                $companyAdmin->last_name = $user->last_name;
                $companyAdmin->email = $user->email;
                $companyAdmin->contact_no = $user->contact_no;
                $user->role_id = User::ROLE_CLINIC_ADMIN;
                $user->state_id = User::STATE_INACTIVE;
                
                if ($user->save()) {
                    $companyAdmin->created_by_id = $user->id;
                    $companyAdmin->user_id = $user->id;
                    $companyAdmin->permission = json_encode($companyAdmin->permission);
                    
                    $company->created_by_id = $user->id;
                    $invoiceAddress->created_by_id = $user->id;
                    $deliveryAddress->created_by_id = $user->id;
                    
                    if ($company->save() && $invoiceAddress->save() && $invoiceAddress->save()) {
                        $companyAdmin->company_id = $company->id;
                        if (! $companyAdmin->save(false)) {
                            $flag = false;
                            \Yii::$app->getSession()->setFlash('error', "Error !!" . $companyAdmin->getErrorsString());
                        }
                        
                        if ($flag === true) {
                            $user->setPassword($user->password);
                            $user->generatePasswordResetToken();
                            $user->save();
                            $transaction->commit();
                            if (User::isAdmin()) {
                                return $this->redirect([
                                    'company/index'
                                ]);
                            } else {
                                return $this->redirect([
                                    'success'
                                ]);
                            }
                        }
                    } else {
                        \Yii::$app->getSession()->setFlash('error', "Error !!" . $company->getErrorsString());
                        \Yii::$app->getSession()->setFlash('error', $invoiceAddress->getErrorsString());
                        \Yii::$app->getSession()->setFlash('error', $invoiceAddress->getErrorsString());
                        \Yii::$app->getSession()->setFlash('error', $companyAdmin->getErrorsString());
                    }
                } else {
                    $flag = false;
                    \Yii::$app->getSession()->setFlash('error', "Error !!" . $user->getErrorsString());
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', $e);
            }
        }
        return $this->render('/site/register', [
            'user' => $user,
            'company' => $company,
            'invoiceAddress' => $invoiceAddress,
            'deliveryAddress' => $deliveryAddress,
            'companyAdmin' => $companyAdmin
        ]);
    }

    /**
     * Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        $model->delete();
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Truncate an existing Company model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = Company::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            Company::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Company Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = Company::findOne($id)) !== null) {
            
            if ($accessCheck && ! ($model->isAllowed()))
                throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));
            
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function updateMenuItems($model = null)
    {
        switch (\Yii::$app->controller->action->id) {
            
            case 'add':
                {
                    $this->menu['manage'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                }
                break;
            case 'index':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'Add'),
                        'url' => [
                            'site/register'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    $this->menu['clear'] = [
                        'label' => '<span class=" glyphicon glyphicon-remove"></span>',
                        'title' => Yii::t('app', 'Clear'),
                        'url' => [
                            'clear'
                        ],
                        'visible' => User::isAdmin()
                    ];
                }
                break;
            case 'update':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'add'),
                        'url' => [
                            'add'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    $this->menu['manage'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                }
                break;
            default:
            case 'view':
                {
                    if (\Yii::$app->hasModule('shadow')) {
                        
                        $this->menu['shadow'] = [
                            'label' => '<span class="glyphicon glyphicon-refresh ">Shadow</span>',
                            'title' => Yii::t('app', 'Login as ' . $model),
                            'url' => [
                                '/shadow/session/login',
                                'id' => $model->created_by_id
                            ],
                        /* 'htmlOptions'=>[], */
                        'visible' => User::isAdmin()
                        ];
                    }
                    $this->menu['manage'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    if ($model != null) {
                        $this->menu['update'] = [
                            'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                            'title' => Yii::t('app', 'Update'),
                            'url' => $model->getUrl('update'),
                            'visible' => User::isAdmin()
                        ];
                        $this->menu['delete'] = [
                            'label' => '<span class="glyphicon glyphicon-trash"></span>',
                            'title' => Yii::t('app', 'Delete'),
                            'url' => $model->getUrl('delete')
                            // 'visible' => User::isAdmin ()
                        ];
                    }
                }
        }
    }
}

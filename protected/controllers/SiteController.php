<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\models\ContactForm;
use app\models\EmailQueue;
use app\models\User;
use app\models\Company;
use app\models\InvoiceAddress;
use app\models\DeliveryAddress;
use app\models\CompanyAdmin;
use app\models\CompanyPrescriber;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use app\models\Category;
use yii\data\ActiveDataProvider;
use app\models\PlanType;
use app\models\Cart;

class SiteController extends TController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'register',
                            'error'
                        ],
                        'allow' => true,
                        'roles' => [
                            '*',
                            '?',
                            '@'
                        ]
                    ],
                    [
                        'actions' => [
                            'company-index'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (User::isCompanyAdmin() || User::isCompanyManager() || User::isCompanyPrescriber());
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'contact',
                            'about',
                            'policy',
                            'treatment',
                            'treatment-detail',
                            'traning',
                            'training-form',
                            'delivery',
                            'terms',
                            'checkout',
                            'error',
                            'success',
                            'shop',
                            'shop-detail',
                            'cart',
                            'wishlist',
                            'myaccount',
                            'product-list',
                            'extra'
                        
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ],
                    [
                        'actions' => [
                            'index',
                            'contact',
                            'about',
                            'policy',
                            'treatment',
                            'treatment-detail',
                            'traning',
                            'training-form',
                            'delivery',
                            'terms',
                            'error',
                            'success',
                            'shop',
                            'shop-detail',
                            'product-list',
                            'extra'
                        ],
                        'allow' => true,
                        'roles' => [
                            '*',
                            '?'
                        ]
                    ]
                ]
            ]
        ];
    }

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

    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        return $this->render('error', [
            'message' => $exception->getMessage(),
            'name' => 'Error'
        ]);
    }

    public function actionSuccess()
    {
        return $this->render('success');
    }

    public function actionExtra()
    {
        return $this->render('extra');
    }

    public function actionRegister()
    {
        $user = new User();
        $company = new Company();
        $invoiceAddress = new InvoiceAddress();
        $deliveryAddress = new DeliveryAddress();
        $companyAdmin = new CompanyAdmin();
        $user->state_id = User::STATE_INACTIVE;
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
                $planModel = PlanType::checkBasicType();
                if (! empty($planModel)) {
                    $company->plan_id = $planModel->id;
                }
                $companyAdmin->first_name = $user->first_name;
                $companyAdmin->last_name = $user->last_name;
                $companyAdmin->email = $user->email;
                $companyAdmin->contact_no = $user->contact_no;
                $user->role_id = User::ROLE_CLINIC_ADMIN;
                if (User::isAdmin()) {
                    $user->state_id = User::STATE_ACTIVE;
                } else {
                    $user->state_id = User::STATE_INACTIVE;
                }
                $password = $user->password;
                if ($user->save()) {
                    $company->state_id = Company::STATE_INACTIVE;
                    $companyAdmin->created_by_id = $user->id;
                    $companyAdmin->user_id = $user->id;
                    $companyAdmin->permission = json_encode($companyAdmin->permission);
                    
                    $company->created_by_id = $user->id;
                    $invoiceAddress->created_by_id = $user->id;
                    $deliveryAddress->created_by_id = $user->id;
                    
                    if ($company->save()) {
                        $companyAdmin->company_id = $company->id;
                        $invoiceAddress->company_id = $company->id;
                        $deliveryAddress->company_id = $company->id;
                        
                        $invoiceAddress->state_id = InvoiceAddress::STATE_ACTIVE;
                        $deliveryAddress->state_id = DeliveryAddress::STATE_ACTIVE;
                        
                        if (! $companyAdmin->save(false)) {
                            $flag = false;
                            \Yii::$app->getSession()->setFlash('error', "Error !!" . $companyAdmin->getErrorsString());
                        }
                        $invoiceAddress->save();
                        $deliveryAddress->save();
                        if ($flag === true) {
                            $user->setPassword($user->password);
                            
                            $user->generatePasswordResetToken();
                            $user->save();
                            $template = "newcompany";
                            $email = $user->email;
                            $subject = "New Account Created";
                            $user->password = $password;
                            $user->sendMail($user, $template, $email, $subject);
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
                        \Yii::$app->getSession()->setFlash('error', $deliveryAddress->getErrorsString());
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
        if (User::isAdmin()) {
            return $this->render('register', [
                'user' => $user,
                'company' => $company,
                'invoiceAddress' => $invoiceAddress,
                'deliveryAddress' => $deliveryAddress,
                'companyAdmin' => $companyAdmin
            ]);
        } else {
            return $this->render('register-front', [
                'user' => $user,
                'company' => $company,
                'invoiceAddress' => $invoiceAddress,
                'deliveryAddress' => $deliveryAddress,
                'companyAdmin' => $companyAdmin
            ]);
        }
    }

    public function actionShop()
    {
        $this->layout = 'guest-main';
        $query = Category::find()->where([
            'state_id' => Category::STATE_ACTIVE
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        
        $this->updateMenuItems();
        return $this->render('shop', [
            'dataProvider' => $dataProvider
        ]);
        return $this->render('shop');
    }

    public function actionShopDetail()
    {
        return $this->render('shop-detail');
    }

    public function actionProductList()
    {
        return $this->render('product-list');
    }

    public function actionIndex()
    {
        $this->updateMenuItems();
        if (! \Yii::$app->user->isGuest) {
            return $this->redirect('dashboard/index');
        } else {
            $this->layout = 'guest-main';
            return $this->render('index');
        }
    }

    public function actionCompanyIndex()
    {
        $this->layout = 'guest-main';
        return $this->render('index');
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $sub = 'New Contact: ' . $model->subject;
            $from = $model->email;
            $message = \yii::$app->view->renderFile('@app/mail/contact.php', [
                'user' => $model
            ]);
            EmailQueue::sendEmailToAdmins([
                'from' => $from,
                'subject' => $sub,
                'html' => $message
            ], true);
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Warm Greetings!! Thank you for contacting us. We have received your request. Our representative will contact you soon.'));
            return $this->refresh();
        }
        
        return $this->render('contact', [
            'model' => $model
        ]);
    }

    public function actionAbout()
    {
        $this->layout = 'guest-main';
        return $this->render('about');
    }

    public function actionPolicy()
    {
        $this->layout = 'guest-main';
        return $this->render('policy');
    }

    public function actionTerms()
    {
        $this->layout = 'guest-main';
        return $this->render('term');
    }

    public function actionTreatment()
    {
        $this->layout = 'guest-main';
        return $this->render('treatment');
    }

    public function actionTreatmentDetail()
    {
        $this->layout = 'guest-main';
        return $this->render('treatment-detail');
    }

    public function actionTraning()
    {
        $this->layout = 'guest-main';
        return $this->render('traning');
    }

    public function actionTrainingForm()
    {
        $this->layout = 'guest-main';
        return $this->render('training-form');
    }

    public function actionDelivery()
    {
        $this->layout = 'guest-main';
        return $this->render('delivery');
    }

    public function actionCart()
    {
        $this->layout = 'guest-main';
        $model = Cart::findOne([
            'created_by_id' => \Yii::$app->user->id
        ]);
        return $this->render('cart', [
            'model' => $model
        ]);
    }

    public function actionCheckout()
    {
        $this->layout = 'guest-main';
        $deliveryAddress = new DeliveryAddress();
        
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $deliveryAddress->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($deliveryAddress);
        }
        if ($deliveryAddress->load($post)) {
            $deliveryAddress->company_id = Company::getCompanyId();
            $deliveryAddress->state_id = DeliveryAddress::STATE_ACTIVE;
            DeliveryAddress::updateAll([
                'state_id' => DeliveryAddress::STATE_INACTIVE
            ], "company_id = {$deliveryAddress->company_id}");
            if ($deliveryAddress->save()) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('app', "New delivery address is added successfully"));
                return $this->redirect(\Yii::$app->request->referrer);
            } else {
                \Yii::$app->getSession()->setFlash('danger', $deliveryAddress->getErrorsString());
            }
        }
        
        $model = Cart::findOne([
            'created_by_id' => \Yii::$app->user->id
        ]);
        return $this->render('checkout', [
            'model' => $model,
            'deliveryAddress' => $deliveryAddress
        ]);
    }

    public function actionWishlist()
    {
        $this->layout = 'guest-main';
        return $this->render('wishlist');
    }

    public function actionMyaccount()
    {
        $this->layout = 'guest-main';
        return $this->render('myaccount');
    }

    protected function updateMenuItems($model = null)
    {}
}
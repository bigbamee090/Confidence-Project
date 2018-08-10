<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\models\LoginForm;
use app\models\User;
use app\models\search\User as UserSearch;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use app\models\CompanyAdmin;
use app\models\CompanyPrescriber;
use function GuzzleHttp\json_encode;
use app\modules\feed\models\Activity;
use app\models\Company;
use app\models\InvoiceAddress;
use app\models\DeliveryAddress;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends TController
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
                            'confirm-email',
                            // 'add',
                            'view',
                            'update',
                            'logout',
                            'changepassword',
                            'profile-image',
                            'toggle',
                            'dashboard'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isUser();
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'delete',
                            // 'add',
                            'view',
                            'update',
                            'delete',
                            'logout',
                            'changepassword',
                            'resetpassword',
                            'dashboard',
                            'profile-image',
                            'toggle',
                            'clear',
                            'recover',
                            'add-admin',
                            'status',
                            'mass'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'changepassword',
                            'view',
                            'logout',
                            'update',
                            'profile-image'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (User::isCompanyAdmin() || User::isCompanyManager() || User::isCompanyPrescriber());
                        }
                    ],
                    [
                        'actions' => [
                            'changepassword',
                            'view',
                            'logout',
                            'company-index',
                            'add-company-user',
                            'add-company-prescriber'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (User::checkPermision(User::PERMISSION_SUPER_USER));
                        }
                    ],
                    [
                        'actions' => [
                            'login',
                            'backend',
                            'signup',
                            'resetpassword',
                            'recover',
                            'add-admin',
                            'confirm-email'
                        ],
                        'allow' => true,
                        'roles' => [
                            '?',
                            '*'
                        ]
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

    public function actionClear()
    {
        $runtime = Yii::getAlias('@runtime');
        $this->cleanRuntimeDir($runtime);
        
        $this->cleanAssetsDir();
        return $this->goBack();
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCompanyIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        
        return $this->render('_company-admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionAddCompanyUser()
    {
        $user = new User([
            'scenario' => 'company_user'
        ]);
        $user->state_id = User::STATE_INACTIVE;
        $companyAdmin = new CompanyAdmin();
        $companyPrescriber = new CompanyPrescriber();
        $companyPrescriber->scenario = 'client_user';
        $companyAdmin->scenario = 'client_user';
        $post = Yii::$app->request->post();
        
        if (Yii::$app->request->isAjax && $user->load($post) && $companyAdmin->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($companyAdmin);
        }
        
        if ($user->load($post) && $companyAdmin->load($post)) {
            
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                $user->role_id = User::ROLE_CLINIC_ADMIN;
                $user->state_id = User::STATE_ACTIVE;
                $user->first_name = $companyAdmin->first_name;
                $user->last_name = $companyAdmin->last_name;
                $user->email = $companyAdmin->email;
                $user->contact_no = $companyAdmin->contact_no;
                
                $companyAdmin->permission = json_encode($companyAdmin->permission);
                
                $userAdmin = \Yii::$app->user->identity;
                if (User::isCompanyAdmin() && isset($userAdmin->companyAdmin)) {
                    $companyAdmin->company_id = $userAdmin->companyAdmin->company_id;
                } else if (User::isCompanyManager() && isset($userAdmin->companyAdmin)) {
                    $companyAdmin->company_id = $userAdmin->companyAdmin->company_id;
                } elseif (User::isCompanyPrescriber() && isset($userAdmin->companyPrescriber)) {
                    $companyAdmin->company_id = $userAdmin->companyPrescriber->company_id;
                }
                
                // print_r($companyAdmin->permission);exit;
                
                if ($user->validate() && $companyAdmin->validate()) {
                    $user->generatePasswordResetToken();
                    $user->setPassword($user->password);
                    
                    if ($user->save()) {
                        $companyAdmin->created_by_id = \Yii::$app->user->id;
                        $companyAdmin->user_id = $user->id;
                        if ($companyAdmin->save(false)) {
                            $transaction->commit();
                            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'User Added Successfully.'));
                            
                            Activity::saveFeed('Added new company Admin', $user);
                            
                            return $this->redirect([
                                'view',
                                'id' => $user->id
                            ]);
                        }
                    }
                } else {
                    \Yii::$app->getSession()->setFlash('error', "Error !!" . $user->getErrorsString() . "</br>" . $companyAdmin->getErrorsString());
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', $e);
            }
        }
        
        return $this->render('add-company-user', [
            'user' => $user,
            'companyAdmin' => $companyAdmin,
            'companyPrescriber' => $companyPrescriber
        ]);
    }

    public function actionAddCompanyPrescriber()
    {
        $user = new User([
            'scenario' => 'company_user'
        ]);
        $user->state_id = User::STATE_INACTIVE;
        $companyAdmin = new CompanyAdmin();
        $companyPrescriber = new CompanyPrescriber();
        $companyPrescriber->scenario = 'client_user';
        $companyAdmin->scenario = 'client_user';
        $post = Yii::$app->request->post();
        
        if (Yii::$app->request->isAjax && $user->load($post) && $companyPrescriber->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            return TActiveForm::validate($companyPrescriber);
        }
        
        if ($user->load($post) && $companyPrescriber->load($post)) {
            
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                $user->role_id = User::ROLE_PRESCRIBERS;
                $user->state_id = User::STATE_INACTIVE;
                $user->tos = true;
                
                $user->first_name = $companyPrescriber->first_name;
                $user->last_name = $companyPrescriber->last_name;
                $user->email = $companyPrescriber->email;
                $user->contact_no = $companyPrescriber->contact_no;
                
                $companyPrescriber->permission = json_encode($companyPrescriber->permission);
                
                $loginUser = \Yii::$app->user->identity;
                if (User::isCompanyAdmin() && isset($loginUser->companyAdmin)) {
                    $companyPrescriber->company_id = $loginUser->companyAdmin->company_id;
                } else if (User::isCompanyManager() && isset($loginUser->companyAdmin)) {
                    $companyPrescriber->company_id = $loginUser->companyAdmin->company_id;
                } elseif (User::isCompanyPrescriber() && isset($loginUser->companyPrescriber)) {
                    $companyPrescriber->company_id = $loginUser->companyPrescriber->company_id;
                }
                $companyPrescriber->saveUploadedFile($companyPrescriber, 'passport_image');
                
                // print_r($companyPrescriber);exit;
                
                if ($user->validate() && $companyPrescriber->validate()) {
                    $user->generatePasswordResetToken();
                    $user->setPassword($user->password);
                    if ($user->save()) {
                        $companyPrescriber->created_by_id = \Yii::$app->user->id;
                        $companyPrescriber->user_id = $user->id;
                        if ($companyPrescriber->save(false)) {
                            $transaction->commit();
                            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'User Added Successfully.'));
                            return $this->redirect([
                                'view',
                                'id' => $user->id
                            ]);
                        } else {
                            \Yii::$app->getSession()->setFlash('error', "Error Prescriber!!" . $companyPrescriber->getErrorsString());
                        }
                    } else {
                        \Yii::$app->getSession()->setFlash('error', "Error User!!" . $user->getErrorsString());
                    }
                } else {
                    \Yii::$app->getSession()->setFlash('error', "Error !!" . $user->getErrorsString() . "</br>" . $companyPrescriber->getErrorsString());
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', $e);
            }
        }
        
        // $companyAdmin->scenario = '';
        // $companyPrescriber->scenario = '';
        
        return $this->render('add-company-user', [
            'user' => $user,
            'companyAdmin' => $companyAdmin,
            'companyPrescriber' => $companyPrescriber
        ]);
    }

    public function actionStatus($id)
    {
        $model = $this->findModel($id);
        if ($model->state_id == User::STATE_ACTIVE) {
            $model->state_id = User::STATE_INACTIVE;
        } else {
            $model->state_id = User::STATE_ACTIVE;
        }
        $model->save(false, [
            'state_id'
        ]);
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id, false);
        $saveStatus = $model->state_id;
        $this->updateMenuItems($model);
        
        $post = \Yii::$app->request->post();
        if (! empty($post)) {
            
            if ($model->role_id == User::ROLE_CLINIC_ADMIN) {
                
                $companyAdmin = CompanyAdmin::findOne([
                    'user_id' => $id
                ]);
                
                if (! empty($companyAdmin)) {
                    if ($companyAdmin->load($post)) {
                        $companyAdmin->permission = json_encode($post['CompanyAdmin']['permission']);
                        $companyAdmin->save();
                    }
                }
            } else if ($model->role_id == User::ROLE_CLINIC_ADMIN) {
                $companyAdmin = CompanyPrescriber::findOne([
                    'user_id' => $id
                ]);
                
                if (! empty($companyAdmin)) {
                    if ($companyAdmin->load($post)) {
                        $companyAdmin->permission = json_encode($post['CompanyAdmin']['permission']);
                        $companyAdmin->save();
                    }
                }
            }
            
            if ($model->load($post)) {
                if ($saveStatus != $model->state_id) {
                    $template = "changeUserStatus";
                    $email = $model->email;
                    // User::
                    $subject = 'You have been ' . $model->getState();
                    $model->sendMail($model, $template, $email, $subject);
                }
                $model->save();
            }
            return $this->redirect(\Yii::$app->request->referrer);
        }
        
        if ($model->role_id == User::ROLE_CLINIC_ADMIN) {
            return $this->render('_company_admin_view', [
                'model' => $model
            ]);
        } elseif ($model->role_id == User::ROLE_CLINIC_MANAGER) {
            return $this->render('_company_admin_view', [
                'model' => $model
            ]);
        } elseif ($model->role_id == User::ROLE_PRESCRIBERS) {
            return $this->render('_prescriber_view', [
                'model' => $model
            ]);
        }
        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionAddAdmin()
    {
        $this->layout = "guest-main";
        $count = User::find()->count();
        if ($count != 0) {
            return $this->redirect([
                '/'
            ]);
        }
        $model = new User();
        $model->scenario = 'add-admin';
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->role_id = User::ROLE_ADMIN;
            $model->state_id = User::STATE_ACTIVE;
            if ($model->validate()) {
                $model->setPassword($model->password);
                $model->generatePasswordResetToken();
                if ($model->save()) {
                    \Yii::$app->user->login($model);
                    \Yii::$app->getSession()->setFlash('success', \Yii::t('app', "Wellcome $model->full_name"));
                    return $this->goBack([
                        'dashboard/index'
                    ]);
                } else {
                    \Yii::$app->getSession()->setFlash('error', "Error !!" . $model->getErrorsString());
                }
            } else {
                \Yii::$app->getSession()->setFlash('error', "Error !!" . $model->getErrorsString());
            }
        }
        return $this->render('add-admin', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd()
    {
        $this->layout = 'main';
        $model = new User();
        $model->role_id = User::ROLE_USER;
        $model->state_id = User::STATE_ACTIVE;
        $model->scenario = 'signup';
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'profile_file');
            if (! empty($image)) {
                $image->saveAs(UPLOAD_PATH . $image->baseName . '.' . $image->extension);
                $model->profile_file = $image->baseName . '.' . $image->extension;
            }
            if ($model->validate()) {
                $model->scenario = 'add';
                $model->generatePasswordResetToken();
                $model->sendRegistrationMailtoUser($model);
                $model->setPassword($model->password);
                if ($model->save()) {
                    \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'User Added Successfully.'));
                    return $this->redirect([
                        'view',
                        'id' => $model->id
                    ]);
                }
            } else {
                \Yii::$app->getSession()->setFlash('error', "Error !!" . $model->getErrorsString());
            }
        }
        $this->updateMenuItems($model);
        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionRecover()
    {
        $this->layout = 'guest-main';
        $model = new User();
        $model->scenario = 'token_request';
        if (isset($_POST['User'])) {
            $email = trim($_POST['User']['email']);
            if ($email != null) {
                
                $user = User::findOne([
                    'email' => $email
                ]);
                if ($user) {
                    $user->generatePasswordResetToken();
                    if (! $user->save(false, [
                        'activation_key'
                    ])) {
                        throw new Exception(\Yii::t('app', "Cant Generate Authentication Key"));
                    }
                    $user->sendEmail();
                    \Yii::$app->session->setFlash('success', \Yii::t('app', 'Please check your email to reset your password.'));
                    return $this->redirect([
                        '/user/login'
                    ]);
                } else {
                    \Yii::$app->session->setFlash('error', \Yii::t('app', 'Email is not registered.'));
                }
            } else {
                $model->addError('error', \Yii::t('app', 'Email cannot be blank'));
            }
        }
        $this->updateMenuItems($model);
        return $this->render('requestPasswordResetToken', [
            'model' => $model
        ]);
    }

    public function actionResetpassword($token)
    {
        $this->layout = 'guest-main';
        $model = User::findByPasswordResetToken($token);
        if (! ($model)) {
            \Yii::$app->session->setFlash('error', \Yii::t('app', 'This URL is expired.'));
        }
        $newModel = new User([
            'scenario' => 'resetpassword'
        ]);
        if ($newModel->load(Yii::$app->request->post()) && $newModel->validate() && $model) {
            
            if (defined('ALLOW_PASSWORD_CHANGE')) {
                $model->setPassword($newModel->password);
                $model->removePasswordResetToken();
                if ($model->save()) {
                    \Yii::$app->session->setFlash('success', \Yii::t('app', 'New password is saved successfully.'));
                } else {
                    \Yii::$app->session->setFlash('error', \Yii::t('app', 'Error while saving new password.'));
                }
            } else {
                \Yii::$app->session->setFlash('error', \Yii::t('app', 'Password change disabled.'));
            }
        }
        $this->updateMenuItems($model);
        return $this->render('resetpassword', [
            'model' => $newModel
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->layout = 'main';
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $post = \yii::$app->request->post();
        $old_image = $model->profile_file;
        $password = $model->password;
        
        if (Yii::$app->request->isAjax && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        
        if ($model->load($post)) {
            
            if (! empty($post['User']['password']))
                $model->setPassword($post['User']['password']);
            else
                $model->password = $password;
            
            $model->profile_file = $old_image;
            $model->saveUploadedFile($model, 'profile_file', $old_image);
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', \Yii::t('app', 'User Updated successfully.'));
                return $this->redirect($model->getUrl());
            } else {
                \Yii::$app->getSession()->setFlash('error', "Error !!" . $model->getErrorsString());
            }
        }
        
        $model->password = '';
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        
        if (\Yii::$app->user->id == $model->id || $model->role_id == User::ROLE_ADMIN) {
            \Yii::$app->session->setFlash('warrning', 'You are not allowed to perform this operation.');
            return $this->redirect(\Yii::$app->request->referrer);
        }
        $model->delete();
        if (\Yii::$app->request->isAjax) {
            return true;
        }
        \Yii::$app->session->setFlash('success', \Yii::t('app', 'User Deleted successfully.'));
        return $this->redirect([
            'index'
        ]);
    }

    public function actionConfirmEmail($id)
    {
        $user = User::find()->where([
            'activation_key' => $id
        ])->one();
        if (! empty($user)) {
            $user->email_verified = User::EMAIL_VERIFIED;
            if ($user->save()) {
                if (Yii::$app->user->login($user, 3600 * 24 * 30)) {
                    \Yii::$app->getSession()->setFlash('success', 'Congratulations! your account is verified');
                    return $this->redirect([
                        '/dashboard'
                    ]);
                }
            }
        } else {
            \Yii::$app->getSession()->setFlash('error', 'Token is Expired Please Resend Again');
            return $this->redirect([
                'dashboard'
            ]);
        }
    }

    public function actionSignup()
    {
        $this->layout = "guest-main";
        $model = new User([
            'scenario' => 'signup'
        ]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $model->scenario = 'signup';
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->state_id = User::STATE_ACTIVE;
            $model->role_id = User::ROLE_USER;
            $model->email_verified = User::EMAIL_NOT_VERIFIED;
            if ($model->validate()) {
                $model->scenario = 'add';
                $model->setPassword($model->password);
                $model->generatePasswordResetToken();
                if ($model->save()) {
                    $model->sendRegistrationMailtoAdmin();
                    \Yii::$app->user->login($model, 3600 * 24 * 30);
                    
                    return $this->redirect([
                        '/dashboard'
                    ]);
                } else {
                    \Yii::$app->getSession()->setFlash('error', "Error !!" . $model->getErrorsString());
                }
            } else {
                \Yii::$app->getSession()->setFlash('error', "Error !!" . $model->getErrorsString());
            }
        }
        return $this->render('signup', [
            'model' => $model
        ]);
    }

    public function actionLogin()
    {
        $this->layout = "guest-main";
        if (! \Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            $user = User::findByUsername($model->username);
            
            if (! empty($user)) {
                $company_id = 0;
                if ($user->role_id == User::ROLE_CLINIC_ADMIN && isset($user->companyAdmin)) {
                    $company_id = $user->companyAdmin->company_id;
                } else if ($user->role_id == User::ROLE_CLINIC_MANAGER && isset($user->companyAdmin)) {
                    $company_id = $user->companyAdmin->company_id;
                } else if ($user->role_id == User::ROLE_PRESCRIBERS && isset($user->companyPrescriber)) {
                    $company_id = $user->companyPrescriber->company_id;
                }
                if ($company_id > 0) {
                    $companyModel = Company::findOne([
                        'id' => $company_id,
                        'state_id' => Company::STATE_ACTIVE
                    ]);
                    if (empty($companyModel)) {
                        Yii::$app->getSession()->setFlash('user-error', 'Company is inactive need to approval from admin');
                        return $this->render('login', [
                            'model' => $model
                        ]);
                    }
                }
                if ($user->isActive()) {
                    if ($user->role_id != User::ROLE_ADMIN) {
                        if ($model->login()) {
                            return $this->goBack([
                                'dashboard/index'
                            ]);
                        }
                    } else {
                        Yii::$app->getSession()->setFlash('user-error', 'Please Enter Right Credentials');
                    }
                } else {
                    Yii::$app->getSession()->setFlash('user-error', 'User is inactive need to approval from admin');
                }
            } else {
                Yii::$app->getSession()->setFlash('user-error', 'Please Enter Right Credentials');
            }
        }
        return $this->render('login', [
            'model' => $model
        ]);
    }

    public function actionBackend()
    {
        $this->layout = "login";
        if (! \Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            $user = User::findByUsername($model->username);
            if (! empty($user)) {
                if ($user->isActive()) {
                    if ($user->role_id == User::ROLE_ADMIN) {
                        if ($model->login()) {
                            return $this->goBack([
                                'dashboard/index'
                            ]);
                        }
                    } else {
                        Yii::$app->getSession()->setFlash('user-error', 'Please Enter Right Credentials');
                    }
                } else {
                    Yii::$app->getSession()->setFlash('user-error', 'Currently your account is not activated.');
                }
            } else {
                Yii::$app->getSession()->setFlash('user-error', 'Please Enter Right Credentials');
            }
        }
        return $this->render('backend', [
            'model' => $model
        ]);
    }

    public function actionProfileImage($id = null)
    {
        return Yii::$app->user->identity->getProfileImage($id);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionChangepassword($id)
    {
        $this->layout = 'main';
        $model = $this->findModel($id);
        if (! ($model->isAllowed()))
            throw new \yii\web\HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));
        
        $newModel = new User([
            'scenario' => 'changepassword'
        ]);
        if (Yii::$app->request->isAjax && $newModel->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return TActiveForm::validate($newModel);
        }
        if ($newModel->load(Yii::$app->request->post()) && $newModel->validate()) {
            $model->setPassword($newModel->newPassword);
            if ($model->save(false, [
                'password'
            ])) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Password Changed successfully'));
                return $this->redirect([
                    'dashboard/index'
                ]);
            } else {
                \Yii::$app->getSession()->setFlash('error', "Error !!" . $model->getErrorsString());
            }
        }
        $this->updateMenuItems($model);
        return $this->render('changepassword', [
            'model' => $newModel
        ]);
    }

    public function actionDashboard()
    {
        return $this->redirect([
            'dashboard/index'
        ]);
    }

    protected function findModel($id, $allow = true)
    {
        if (($model = User::findOne($id)) !== null) {
            if (! ($model->isAllowed()) && ($allow == true))
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
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ],
                        'visible' => User::isAdmin()
                    ];
                }
                break;
            case 'company-index':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'Add'),
                        'url' => [
                            'add-company-user'
                        ],
                        'visible' => User::checkPermision(User::PERMISSION_SUPER_USER)
                    ];
                }
                break;
            case 'index':
                {
                    // $this->menu['add'] = [
                    // 'label' => '<span class="glyphicon glyphicon-plus"></span>',
                    // 'title' => Yii::t('app', 'Add'),
                    // 'url' => [
                    // 'add'
                    // ],
                    // 'visible' => User::isAdmin()
                    // ];
                }
                
                break;
            case 'admin':
                {
                    if (User::checkPermision(User::PERMISSION_SUPER_USER)) {
                        $this->menu['add'] = [
                            'label' => '<span class="glyphicon glyphicon-plus"></span>',
                            'title' => Yii::t('app', 'Add'),
                            'url' => [
                                'add-company-admin'
                            ]
                        ];
                    }
                }
                break;
            case 'admin-manager':
                {
                    if (User::checkPermision(User::PERMISSION_SUPER_USER)) {
                        $this->menu['add'] = [
                            'label' => '<span class="glyphicon glyphicon-plus"></span>',
                            'title' => Yii::t('app', 'Add'),
                            'url' => [
                                'add-company-admin-manager'
                            ]
                        ];
                    }
                }
                
                break;
            case 'prescriber':
                {
                    if (User::checkPermision(User::PERMISSION_SUPER_USER)) {
                        $this->menu['add'] = [
                            'label' => '<span class="glyphicon glyphicon-plus"></span>',
                            'title' => Yii::t('app', 'Add'),
                            'url' => [
                                'add-prescriber'
                            ]
                        ];
                    }
                }
                
                break;
            case 'update':
                {
                    // if (User::checkPermision(User::PERMISSION_SUPER_USER)) {
                    // $this->menu['add'] = [
                    // 'label' => '<span class="glyphicon glyphicon-plus"></span>',
                    // 'title' => Yii::t('app', 'add'),
                    // 'url' => [
                    // 'add'
                    // ],
                    // 'visible' => User::isAdmin()
                    // ];
                    // }
                }
                break;
            case 'view':
                {
                    if (User::checkPermision(User::PERMISSION_SUPER_USER)) {
                        // if ($model != null && ($model->role_id != User::ROLE_ADMIN) && \Yii::$app->hasModule('shadow'))
                        // $this->menu['shadow'] = [
                        // 'label' => '<span class="glyphicon glyphicon-refresh ">Shadow</span>',
                        // 'title' => Yii::t('app', 'Login as ' . $model),
                        // 'url' => [
                        // '/shadow/session/login',
                        // 'id' => $model->id
                        // ],
                        // /* 'htmlOptions'=>[], */
                        // 'visible' => User::isAdmin()
                        // ];
                        // if ($model->role_id != User::ROLE_ADMIN)
                        // $this->menu['add'] = [
                        // 'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        // 'title' => Yii::t('app', 'Add' . $model->role_id),
                        // 'url' => [
                        // 'add'
                        // ],
                        // 'visible' => User::isAdmin()
                        // ];
                        if ($model != null && $model->role_id != User::ROLE_ADMIN)
                            $this->menu['changepassword'] = [
                                'label' => '<span class="glyphicon glyphicon-paste"></span>',
                                'title' => Yii::t('app', 'changepassword'),
                                'url' => $model->getUrl('changepassword'),
                                
                                'visible' => User::isAdmin()
                            ];
                        if ($model != null && $model->role_id != User::ROLE_ADMIN)
                            $this->menu['update'] = [
                                'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                                'title' => Yii::t('app', 'Update'),
                                'url' => $model->getUrl('update'),
                                
                                'visible' => User::isAdmin()
                            ];
                        if ($model->role_id != User::ROLE_ADMIN)
                            $this->menu['manage'] = [
                                'label' => '<span class="glyphicon glyphicon-list"></span>',
                                'title' => Yii::t('app', 'Manage'),
                                'url' => [
                                    'index'
                                ],
                                'visible' => User::isAdmin()
                            ];
                        if ($model != null && $model->role_id != User::ROLE_ADMIN)
                            $this->menu['delete'] = [
                                'label' => '<span class="glyphicon glyphicon-trash"></span>',
                                'title' => Yii::t('app', 'Delete'),
                                'url' => $model->getUrl('delete'),
                                'htmlOptions' => [
                                    'data-method' => 'post'
                                ],
                                'visible' => User::isAdmin()
                            ];
                    }
                }
                break;
        }
    }
}

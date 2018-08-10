<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use Yii;
use app\models\Order;
use app\models\search\Order as OrderSearch;
use app\components\TController;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;
use yii\web\HttpException;
use app\components\TActiveForm;
use app\models\Cart;
use app\models\CartItem;
use app\models\DeliveryAddress;
use app\models\OrderItem;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends TController
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
                            'clear',
                            'list',
                            'index',
                            'delete',
                            'ajax',
                            'mass',
                            'view'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'list',
                            'proceed',
                            'success',
                            'view',
                            'index'
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

    public function actionList()
    {
        return $this->render('list');
    }

    public function actionProceed()
    {
        $cart = Cart::find()->where([
            'created_by_id' => \Yii::$app->user->id
        ])->one();
        if (! empty($cart)) {
            $cartItem = CartItem::find()->where([
                'cart_id' => $cart->id
            ])->all();
            
            $order = new Order();
            
            $order->price = $cart->discounted_price;
            $order->quantity = $cart->quantity;
            $order->company_id = $cart->company_id;
            $order->delivery_status = Order::STATE_PENDING;
            $order->type_id = Order::TYPE_CASH;
            $address = DeliveryAddress::findActive()->andWhere([
                'company_id' => $cart->company_id
            ])
                ->asArray()
                ->one();
            
            $order->delivery_address = json_encode($address);
            
            if (($order->save())) {
                if (! empty($cartItem)) {
                    foreach ($cartItem as $item) {
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $order->id;
                        $orderItem->product_id = $item->product_id;
                        $orderItem->quantity = $item->quantity;
                        $orderItem->price = $item->discounted_price;
                        $orderItem->save();
                    }
                    $cart->delete();
                }
                return $this->redirect([
                    'success',
                    'id' => $order->id
                ]);
            } else {
                \Yii::$app->getSession()->setFlash('error', $order->getErrorsString());
            }
        } else {
            \Yii::$app->getSession()->setFlash('error', "Error Cart!!" . "You cann't proceed with empty cart");
        }
        $this->layout = 'guest-main';
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionSuccess($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('success', [
            'model' => $model
        ]);
    }

    /**
     * Lists all Order models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Order model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Order model.
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
     * Truncate an existing Order model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = Order::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            Order::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Order Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = Order::findOne($id)) !== null) {
            
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
            
            case 'index':
                {
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
            default:
            case 'view':
                {
                    $this->menu['manage'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    if ($model != null) {
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

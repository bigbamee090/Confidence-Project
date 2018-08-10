<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\models\Cart;
use app\models\CartItem;
use app\models\Category;
use app\models\DeliveryAddress;
use app\models\Product;
use app\models\User;
use app\models\search\Product as ProductSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Company;
use app\models\Wishlist;
use app\models\Order;
use app\models\OrderItem;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends TController
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
                            'index',
                            'add',
                            'view',
                            'update',
                            'listview',
                            'items',
                            'delete',
                            'ajax',
                            'list'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'list',
                            'items',
                            'listview',
                            'detail',
                            'delete-cart-item',
                            'checkout',
                            'listview',
                            'items',
                            'quantity',
                            'detail',
                            'add-wishlist',
                            'add-cart',
                            'wish-list'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return (User::isCompanyAdmin() || User::isCompanyManager() || User::isCompanyPrescriber());
                        }
                    ],
                    [
                        'actions' => [
                            'list',
                            'items',
                            'listview',
                            'detail'
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

    public function actionWishList()
    {
        $query = Wishlist::find([
            'created_by_id' => \Yii::$app->user->id
        ]);
        $this->layout = 'guest-main';
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        
        return $this->render('wishlist', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Lists all Product models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Product model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id, false);
        $this->updateMenuItems($model);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionAddCart()
    {
        \Yii::$app->response->format = 'json';
        $response = [
            'status' => 1000,
            'errors' => ''
        ];
        $post = \Yii::$app->request->post();
        
        if (! empty($post)) {
            if (isset($post['id'])) {
                if (is_array($post['id'])) {
                    foreach ($post['id'] as $id) {
                        $cart = Cart::saveCart($id);
                        if (isset($cart['errorr'])) {
                            $response['errors'][] = $cart['errorr'];
                        } else {
                            $response['item'] = $cart;
                        }
                    }
                } else {
                    $cart = Cart::saveCart($post['id']);
                    if (isset($cart['errorr'])) {
                        $response['errors'][] = $cart['errorr'];
                    } else {
                        $response['item'] = $cart;
                    }
                }
                $response['status'] = 200;
            }
        }
        
        return $response;
    }

    public function actionAddWishlist($id)
    {
        \Yii::$app->response->format = 'json';
        $response = [
            'status' => 1000,
            'errors' => ''
        ];
        
        $model = Wishlist::findOne([
            'product_id' => $id,
            'created_by_id' => \Yii::$app->user->id
        ]);
        $product = Product::findOne($id);
        if (empty($model) && ! empty($product)) {
            $model = new Wishlist();
            $model->product_id = $id;
            $model->company_id = Company::getCompanyId();
            $model->price = $product->price;
            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('app', "Wishlist is Updated successfully"));
                $response['status'] = 200;
            }
        }
        
        return $response;
    }

    public function actionQuantity($q, $itemId)
    {
        \Yii::$app->response->format = 'json';
        $response = [
            'status' => 1000,
            'errors' => ''
        ];
        
        $item = CartItem::findOne($itemId);
        if (! empty($item)) {
            $cart = Cart::findOne($item->cart_id);
            if ($q == "p") {
                $item->quantity = $item->quantity + 1;
                if ($cart) {
                    $cart->actual_price += (floatval($item->actual_price));
                    $cart->discounted_price += (floatval($item->discounted_price));
                    $cart->save();
                }
            } else {
                if ($item->quantity > 1) {
                    $item->quantity = $item->quantity - 1;
                    if ($cart) {
                        $cart->actual_price -= (floatval($item->actual_price));
                        $cart->discounted_price -= (floatval($item->discounted_price));
                        $cart->save();
                    }
                }
            }
            
            $item->save(false, [
                'quantity'
            ]);
        }
        
        return $response;
    }

    public function actionItems()
    {
        $this->layout = "guest-main";
        $model = Product::findActive();
        
        $pageSize = 20;
        
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => $pageSize
            ]
        ]);
        
        return $this->render('items', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionList($id)
    {
        $this->layout = "guest-main";
        $model = Product::findActive();
        $category = null;
        if (! empty($id)) {
            $model->andWhere([
                'category_id' => $id
            ]);
            
            $category = Category::findOne($id);
        }
        
        $pageSize = 12;
        
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => $pageSize
            ]
        ]);
        
        return $this->render('productList', [
            'dataProvider' => $dataProvider,
            'category' => $category
        ]);
    }

    public function actionListview()
    {
        $model = Product::findActive();
        
        if (! empty($id)) {
            $model->andWhere([
                'category_id' => $id
            ]);
        }
        $pageSize = 12;
        
        $params = \Yii::$app->request->get();
        
        if (! empty($params)) {
            if (isset($params['pageSize'])) {
                $pageSize = $params['pageSize'];
            }
            
            if (isset($params['sortBy'])) {
                if ($params['sortBy'] == Product::SORT_PRICE_L) {
                    $model->orderBy([
                        'CAST(price as DECIMAL)' => SORT_ASC
                    ]);
                } else if ($params['sortBy'] == Product::SORT_PRICE_H) {
                    $model->orderBy([
                        'CAST(price as DECIMAL)' => SORT_DESC
                    ]);
                } else if ($params['sortBy'] == Product::SORT_TITLE_A) {
                    $model->orderBy([
                        'title' => SORT_ASC
                    ]);
                } else if ($params['sortBy'] == Product::SORT_TITLE_Z) {
                    $model->orderBy([
                        'title' => SORT_DESC
                    ]);
                }
            } else {
                $model->orderBy([
                    'id' => SORT_DESC
                ]);
            }
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => $pageSize
            ]
        ]);
        
        return $this->render('listView', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionDetail($id)
    {
        $this->layout = 'guest-main';
        $model = $this->findModel($id, false);
        
        return $this->render('detail', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd()
    {
        $model = new Product();
        $model->loadDefaultValues();
        $model->state_id = Product::STATE_ACTIVE;
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post) && $model->save()) {
            return $this->redirect([
                'view',
                'id' => $model->id
            ]);
        }
        $this->updateMenuItems();
        return $this->render('add', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post) && $model->save()) {
            return $this->redirect([
                'view',
                'id' => $model->id
            ]);
        }
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionCheckout()
    {
        $deliveryAddress = new DeliveryAddress();
        $model = Cart::findOne([
            'created_by_id' => \Yii::$app->user->id
        ]);
        
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
        
        return $this->render('checkout', [
            'model' => $model,
            'deliveryAddress' => $deliveryAddress
        ]);
    }

    public function actionDeleteCartItem($cartId, $itemId)
    {
        $cart = Cart::findOne($cartId);
        if (! empty($cart)) {
            $cartItem = CartItem::findOne([
                'id' => $itemId
            ]);
            if (! empty($cartItem)) {
                $cart->actual_price = (floatval($cart->actual_price) - floatval($cartItem->actual_price) * $cartItem->quantity);
                $cart->discounted_price = (floatval($cart->discounted_price) - floatval($cartItem->discounted_price) * $cartItem->quantity);
                $cart->save();
                $cartItem->delete();
                
                $checkCart = CartItem::find([
                    'cart_id' => $cartId
                ])->count();
                
                if (empty($checkCart)) {
                    $cart->delete();
                }
            }
        }
        
        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = Product::findOne($id)) !== null) {
            
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
                    $this->menu['manage'] = array(
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    );
                }
                break;
            case 'index':
                {
                    $this->menu['add'] = array(
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'Add'),
                        'url' => [
                            'add'
                        ],
                        'visible' => User::isAdmin()
                    );
                }
                break;
            case 'update':
                {
                    $this->menu['add'] = array(
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'add'),
                        'url' => [
                            'add'
                        ]
                        // 'visible' => User::isAdmin ()
                    );
                    $this->menu['manage'] = array(
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    );
                }
                break;
            default:
            case 'view':
                {
                    $this->menu['manage'] = array(
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    );
                    /*
                     * if ($model != null) {
                     * $this->menu['update'] = array(
                     * 'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                     * 'title' => Yii::t('app', 'Update'),
                     * 'url' => [
                     * 'update',
                     * 'id' => $model->id
                     * ]
                     * // 'visible' => User::isAdmin ()
                     * );
                     * $this->menu['delete'] = array(
                     * 'label' => '<span class="glyphicon glyphicon-trash"></span>',
                     * 'title' => Yii::t('app', 'Delete'),
                     * 'url' => [
                     * 'delete',
                     * 'id' => $model->id
                     * ]
                     * // 'visible' => User::isAdmin ()
                     * );
                     * }
                     */
                }
        }
    }
}

<?php
use app\components\TGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\Product $searchModel
 */

?>
<?php

Pjax::begin();
$dataProvider->sort->attributes = [];
?>
    <?php
    
    echo TGridView::widget([
        'id' => 'product-ajax-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],
            
            'id',
            'title',
            /* 'description:html',*/
            'code',
            'price',
            /* 'actual_quantity',*/
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getCategories() : null,
                'value' => function ($data) {
                    return $data->getRelatedDataLink('category_id');
                }
            ],
            /* [
				'attribute' => 'deal_id',
				'format'=>'raw',
				'value' => function ($data) { return $data->getRelatedDataLink('deal_id');  },
				],*/
            /* 'is_fav',*/
            /* [
			'attribute' => 'state_id','format'=>'raw','filter'=>isset($searchModel)?$searchModel->getStateOptions():null,
			'value' => function ($data) { return $data->getStateBadge();  },],*/
            /* ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
			'value' => function ($data) { return $data->getType();  },],*/
            'created_on:datetime',
            /* 'updated_on:datetime',*/
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('created_by_id');
                }
            ],
            
            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php

Pjax::end();
?>


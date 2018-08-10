<?php
use app\components\TGridView;
use yii\helpers\Html;
use yii\helpers\Url;

use app\models\User;

use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\PlanType;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\PlanType $searchModel
 */

?>
<?php

if (User::isAdmin())
    echo Html::a('', '#', [
        'class' => 'multiple-delete glyphicon glyphicon-trash',
        'id' => "bulk_delete_plan-type-grid"
    ])?>
<?php

Pjax::begin([
    'id' => 'plan-type-pjax-grid'
]);
?>
    <?php
    
    echo TGridView::widget([
        'id' => 'plan-type-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],
            [
                'name' => 'check',
                'class' => 'yii\grid\CheckboxColumn',
                'visible' => User::isAdmin()
            ],
            
            'id',
            'title',
            'percent',
            /*
             * [
             * 'attribute' => 'state_id','format'=>'raw','filter'=>isset($searchModel)?$searchModel->getStateOptions():null,
             * 'value' => function ($data) { return $data->getStateBadge(); },],
             */
            /*
             * [
             * 'attribute' => 'created_by_id',
             * 'format'=>'raw',
             * 'value' => function ($data) { return $data->getRelatedDataLink('created_by_id'); },],
             */
            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>' . Yii::t('app', 'Actions') . '</a>',
                'template' => '{view}&nbsp;{update}&nbsp;{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        if ($model->type_id != PlanType::TYPE_BASIC) {
                            return Html::a('<i class="glyphicon glyphicon-trash"></i>', Url::toroute([
                                'delete',
                                'id' => $model->id
                            ]), [
                                'title' => yii::t('app', 'delete'),
                                'id' => $model->id,
                                'class' => 'btn btn-danger btn-red',
                                "data-method" => "post"
                            ]);
                        }
                    }
                ]
            ]
        ]
    ]);
    ?>
<?php

Pjax::end();
?>
<script> 
$('#bulk_delete_plan-type-grid').click(function(e) {
	e.preventDefault();
	 var keys = $('#plan-type-grid-view').yiiGridView('getSelectedRows');

	 if ( keys != '' ) {
		var ok = confirm("Do you really want to delete these items?");

		if( ok ) {
			$.ajax({
				url  : '<?php
    
    echo Url::toRoute([
        'plan-type/mass',
        'action' => 'delete',
        'model' => get_class($searchModel)
    ])?>', 
				type : "POST",
				data : {
					ids : keys,
				},
				success : function( response ) {
					if ( response.status == "OK" ) {
						 $.pjax.reload({container: '#plan-type-pjax-grid'});
					}
				}
		     });
		}
	 } else {
		alert('Please select items to delete');
	 }
});

</script>


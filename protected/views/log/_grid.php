<?php
use app\components\TGridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\User;
use yii\helpers\Url;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\Log $searchModel
 */

?>

<?php echo Html::a('','#',['class'=>'multiple-delete glyphicon glyphicon-trash','id'=>"bulk-delete"])?>

<?php

Pjax::begin ( [ 
		'id' => 'log-pjax-grid' 
] );
echo TGridView::widget ( [ 
		'id' => 'log-grid',
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'tableOptions' => [ 
				'class' => 'table table-bordered' 
		],
		'columns' => [ 
				[ 
						'name' => 'check',
						'class' => 'yii\grid\CheckboxColumn',
						'visible' => User::isAdmin () 
				],
				// ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],
				
				'id',
				'error',
            /* 'api:html',*/
            /* 'description:html',*/
            [ 
						'attribute' => 'state_id',
						'format' => 'raw',
						'filter' => isset ( $searchModel ) ? $searchModel->getStateOptions () : null,
						'value' => function ($data) {
							return $data->getStateBadge ();
						} 
				],
				'link',
				[ 
						'attribute' => 'type_id',
						'filter' => isset ( $searchModel ) ? $searchModel->getTypeOptions () : null,
						'value' => function ($data) {
							return $data->getType ();
						} 
				],
				'created_on:datetime',
				
				[ 
						'class' => 'app\components\TActionColumn',
						'template' => '{view} {delete}',
						'header' => '<a>Actions</a>' 
				] 
		
		] 
] );
?>
<?php Pjax::end(); ?>
<script>
	$('#bulk-delete').click(function(e) {
		e.preventDefault();
		 var keys = $('#log-grid').yiiGridView('getSelectedRows');
		 if ( keys != '' ) {
			var ok = confirm("Do you really want to delete these items?");
			if( ok ) {
				$.ajax({
					url  : '<?php echo Url::toRoute(['/log/mass','action'=>'delete', 'model' => get_class($searchModel)]);?>',
					type : "POST",
					data : {
						ids : keys
					},
					success : function( response ) {
						if ( response.status == "OK" ) {
							 $("#error_flash").show();
							 $.pjax.reload({container: '#log-pjax-grid'});
						} else { 
							 $("#error_flash").hide();
						}
					}
			     });
			}
		 } else {
			alert('Please select items to delete');
		 }
	});
</script>
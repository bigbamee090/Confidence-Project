<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\TGridView;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = [
    'label' => 'Users'
];

?>
<div class="wrapper">
	<div class="user-index">
		<div class=" panel ">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>

		<div class="panel panel-margin">
			<div class="panel-body">
				<div class="content-section clearfix">
					<?php if (user::isAdmin()) echo Html::a('','#',['class'=>'multiple-delete glyphicon glyphicon-trash','id'=>"bulk-delete"])?>

                        <div class="table table-responsive">
                        	 <?php
                        Pjax::begin([
                            'id' => 'user-pjax-grid'
                        ]);
                        echo TGridView::widget([
                            'id' => 'user-grid',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                
                                [
                                    'name' => 'check',
                                    'class' => 'yii\grid\CheckboxColumn',
                                    'visible' => User::isAdmin()
                                ],
                                'id',
                                [
                                    'attribute' => 'full_name',
                                    'value' => function ($data) {
                                        return $data->getFullName();
                                    }
                                ],
                                'email:email',
                                    /* 'password',*/
                                   				'contact_no',
                                [
                                    'attribute' => 'state_id',
                                    'filter' => $searchModel->getStateOptions(),
                                    'format' => 'html',
                                    'value' => function ($data) {
                                        return $data->getStateBadge();
                                    }
                                ],
                                     /* ['attribute' => 'type_id','filter'=>$searchModel->getTypeOptions(),
                        			'value' => function ($data) { return $data->getTypeOptions($data->type_id);  },] */
                                    /* 'last_visit_time:datetime',*/
                                    /* 'last_action_time:datetime',*/
                                    /* 'last_password_change:datetime',*/
                                    /* 'activation_key',*/
                                    /* 'login_error_count',*/
                                    /* 'create_user_id',*/
                                     'created_on:datetime',
                                
                                [
                                    'class' => 'app\components\TActionColumn',
                                    'header' => '<a>Actions</a>'
                                ]
                            ]
                        ]);
                        
                        ?>
                        <?php Pjax::end()?>
                        </div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	$('#bulk-delete').click(function(e) {
		e.preventDefault();
		 var keys = $('#user-grid').yiiGridView('getSelectedRows');
		 if ( keys != '' ) {
			var ok = confirm("Do you really want to delete these items?");
			if( ok ) {
				$.ajax({
					url  : '<?php echo Url::toRoute(['/user/mass','action'=>'delete', 'model' => get_class($searchModel)]);?>',
					type : "POST",
					data : {
						ids : keys
					},
					success : function( response ) {
						if ( response.status == "OK" ) {
							 $("#error_flash").show();
							 
							 $.pjax.reload({container: '#user-pjax-grid'});
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
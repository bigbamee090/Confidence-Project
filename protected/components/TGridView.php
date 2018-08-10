<?php
namespace app\components;

use Yii;
use yii\grid\GridView;

class TGridView extends GridView
{

    public $enableRowClick = true;
    public $responsive = true;

    public function init()
    {
        parent::init();
        $controllerId = \Yii::$app->controller->id;
        if ($this->enableRowClick == true) {
            Yii::$app->controller->getView()->registerJs("
			    $('.grid-view td').click(function (e) {
			        var id = $(this).closest('tr').data('id');
					var url = $(this).closest('tr').data('url');
			       		var name = $(this).closest('tr').data('name');
			       		var target = $(e.target);
                    if( typeof url != 'undefined' ) {
    			        if(e.target == this || target.is('p')) {
    						if(!$(this).closest('tr').hasClass('filters'))
    			            	location.href = url;
    				    }
                    }
			    });");
        }
        // onclick event should always open detail view:
        if ($this->rowOptions == NULL)
            $this->rowOptions = function ($model, $key, $index, $grid) {
                // get the model name is necessary, if the grid is not the main grid
                // without this the routed view is the view of the main controller
                return [
                    'data-id' => $model->id,
                    'style' => $this->enableRowClick ? "cursor:pointer;" : '',
                    'data-name' => \yii\helpers\Inflector::camel2id(\yii\helpers\StringHelper::basename(get_class($model))),
                    'data-url' => $model->getUrl()
                ];
            };
    }
     /**
     * Runs the widget.
     */
    public function run()
    {
        if ($this->responsive)
            echo "<div class='table-responsive'>";
        parent::run();
        if ($this->responsive)
            echo "</div>";
    }

}
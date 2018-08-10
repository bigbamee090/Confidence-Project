<?php
namespace app\components\useraction;

use app\components\TBaseWidget;
use app\models\User;

class UserAction extends TBaseWidget
{

    public $model;

    public $attribute;

    public $states;

    public $actions;

    public $allowed;

    public $visible = true;

    public $title;

    public function getButtonColor($id)
    {
        $list = [
            'New' => "btn-primary",
            'Active' => "btn-success",
            'Deleted' => "btn-danger"
        ];
        
        if (isset($list[$id]))
            return $list[$id];
        return "btn-primary";
    }

    public function init()
    {
        $this->visible = User::isGuest() ? false : true;
        if (empty($this->actions))
            $this->actions = $this->states;
        if (empty($this->allowed)) {
            $this->allowed = $this->actions;
            $this->allowed[$this->model->{$this->attribute}] = null;
            $this->allowed = array_filter($this->allowed);
        }
        // array_shift ($this->options);
        $this->title = 'Change State';
        parent::init();
    }

    public function renderHtml()
    {
        if ($this->visible == false)
            return;
        
        if (isset($_POST['workflow'])) {
            $submit = trim($_POST['workflow']);
            $state_list = $this->states;
            $actions = $this->actions;
            $allowed = $this->allowed;
            
            $state_id = array_search($submit, $actions);
            
            $ok = array_search($submit, $allowed);
            
            if ($ok >= 0 && $state_id >= 0 && $state_id != $this->model->{$this->attribute}) {
                $old_state = $state_list[$this->model->{$this->attribute}];
                $new_state = $state_list[$state_id];
                $this->model->{$this->attribute} = $state_id;
                
                if (($this->model instanceof User) && (\Yii::$app->user->id == $this->model->id)) {
                    \Yii::$app->session->setFlash('error', \Yii::t('app', 'You are not allowed to perform this operation.'));
                } else {
                    if ($this->model->save(false, [
                        'state_id'
                    ])) {
                        \Yii::$app->session->setFlash('success', 'State Changed.');
                        $msg = 'State Changed : ' . $old_state . ' to ' . $new_state;
                        $this->model->updateHistory($msg);
                        \Yii::$app->session->setFlash('success', $msg);
                    } else {
                        $error = \Yii::t('app', 'You are not allowed to perform this operation.') . $this->model->getErrorsString();
                        \Yii::$app->session->setFlash('error', $error);
                    }
                }
            }
            \Yii::$app->controller->redirect($this->model->getUrl());
        }
        
        if (! empty($this->model))
            echo $this->render('user-action', [
                'model' => $this->model,
                'allowed' => $this->allowed,
                'attribute' => $this->attribute,
                'title' => $this->title
            ]);
    }
}
<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_email_queue".
 *
 * @property integer $id
 * @property string $from_email
 * @property string $to_email
 * @property string $message
 * @property string $subject
 * @property string $date_published
 * @property string $last_attempt
 * @property string $date_sent
 * @property integer $attempts
 * @property integer $state_id
 * @property integer $email_id
 * @property integer $project_id
 *
 */
namespace app\models;

use Yii;
use yii\helpers\VarDumper;

class EmailQueue extends \app\components\TActiveRecord
{

    public $mail_sent = 0;

    public function __toString()
    {
        return (string) $this->from_email;
    }

    const STATE_PENDING = 0;

    const STATE_SENT = 1;

    const STATE_DELETED = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_PENDING => "Pending",
            self::STATE_SENT => "Sent",
            self::STATE_DELETED => "Discarded"
        ];
    }

    public function getState()
    {
        $list = self::getStateOptions();
        return isset($list[$this->state_id]) ? $list[$this->state_id] : 'Not Defined';
    }

    public function getStateBadge()
    {
        $list = [
            self::STATE_PENDING => "primary",
            self::STATE_SENT => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'label label-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%email_queue}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'message'
                ],
                'string'
            ],
            [
                [
                    'date_published',
                    'last_attempt',
                    'date_sent'
                ],
                'safe'
            ],
            [
                [
                    'state_id'
                ],
                'in',
                'range' => array_keys(EmailQueue::getStateOptions())
            ],
            
            [
                [
                    'attempts',
                    'state_id',
                    'email_account_id'
                ],
                'integer'
            ],
            [
                [
                    'from_email',
                    'to_email'
                ],
                'string',
                'max' => 128
            ],
            
            [
                [
                    'subject'
                ],
                'string',
                'max' => 255
            ],
            /* 				[
             'model_id',
             'unique',
             'targetAttribute' => [
             'model_id',
             'model_type'
             ]
             ], */
            [
                [
                    'from_email',
                    'to_email',
                    'message',
                    'subject'
                ],
                'trim'
            ]
        
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'from_email' => Yii::t('app', 'From Email'),
            'to_email' => Yii::t('app', 'To Email'),
            'message' => Yii::t('app', 'Message'),
            'subject' => Yii::t('app', 'Subject'),
            'date_published' => Yii::t('app', 'Date Published'),
            'last_attempt' => Yii::t('app', 'Last Attempt'),
            'date_sent' => Yii::t('app', 'Date Sent'),
            'attempts' => Yii::t('app', 'Attempts'),
            'state_id' => Yii::t('app', 'State'),
            'email_account_id' => Yii::t('app', 'Email Account')
        ];
    }

    public function getModel()
    {
        if (! empty($this->model_type))
            return $this->model_type::findOne($this->model_id);
        return null;
    }

    public static function getHasManyRelations()
    {
        $relations = [];
        return $relations;
    }

    public static function getHasOneRelations()
    {
        $relations = [];
        return $relations;
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['from_email'] = $this->from_email;
        $json['to_email'] = $this->to_email;
        $json['message'] = $this->message;
        $json['subject'] = $this->subject;
        $json['date_published'] = $this->date_published;
        $json['last_attempt'] = $this->last_attempt;
        $json['date_sent'] = $this->date_sent;
        $json['attempts'] = $this->attempts;
        $json['state_id'] = $this->state_id;
        $json['model_id'] = $this->model_id;
        $json['model_type'] = $this->model_type;
        $json['email_account_id'] = $this->email_account_id;
        if ($with_relations) {}
        return $json;
    }


    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (! isset($this->state_id))
                $this->state_id = EmailQueue::STATE_PENDING;
        } else {}
        return parent::beforeValidate();
    }
    
    public static function cleanEmailAddress($value)
    {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            
            $pattern = '/[a-z0-9_.\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
            if (preg_match($pattern, $value, $matches))
                $value = ($matches[0]);
        }
        return trim($value);
    }
    public function sendNow()
    {
        $mail_sent = 0;
        
        $mailer = \Yii::$app->mailer;
        
        try {
            
            $to_email = self::cleanEmailAddress($this->to_email);
            
            $mail = $mailer->compose();
            $this->message_id = $mail->getSwiftMessage()->getId();
                        
            $mail->setHtmlBody($this->message)
                ->setTo($to_email)
                ->setFrom($this->from_email)
                ->setSubject($this->subject);
            
            $mail->setReadReceiptTo($this->from_email);
            
            if (isset($this->email) && isset($this->email->files)) {
                foreach ($this->email->files as $file) {
                    $file = UPLOAD_PATH . $file;
                    if (file_exists($file) && ! is_dir($file))
                        $mail->attach($file);
                }
            }
            $mail_sent = $mail->send();
            $this->date_sent = date('Y-m-d H:i:s');
            $this->state_id = self::STATE_SENT;
            
            if (! $this->save(false, [
                'state_id',
                'date_sent',
                'message_id',
                'message'
            ])) {
                var_dump($this->errors);
            }

        } catch (\Exception $e) {
            
            echo $e->getMessage();
            echo $e->getTraceAsString();
        }
        return $mail_sent;
    }


    public static function add($args = [], $trySendNow = true)
    {
        if (! $args)
            return false;
        $mail = new EmailQueue();
        $mail->from_email = isset($args['from']) ? self::cleanEmailAddress($args['from']) : Yii::$app->params['adminEmail'];
        if (isset($args['model'])) {
            $mail->model_id = $args['model']->id;
            $mail->model_type = get_class($args['model']);
        }
        $mail->to_email = $args['to'];
        
        $mail->subject = (isset($args['subject'])) ? $args['subject'] : "EmailQueue";
        $mail->date_sent = date('Y-m-d H:i:s');
        if (isset($args['html'])) {
            $mail->message = $args['html'];
        } else {
            $view = isset($args['view']) ? $args['view'] : '@app/mail/email';
            $args = isset($args['viewArgs']) ? $args['viewArgs'] : [];
            $mail->message = \Yii::$app->mailer->render($view, $args);
        }
        if ($mail->save()) {
            if ($trySendNow)
                $mail->sendNow();
        } else {
            VarDumper::dump($mail->errors);
        }
        return false;
    }
    
    
    public static function sendEmailToAdmins($data, $trySendNow = true)
    {
        $allAdmins = User::find()->where([
            'role_id' => User::ROLE_ADMIN
        ]);
        foreach ($allAdmins->batch() as $admins) {
            foreach ($admins as $admin) {
                $data['to'] = $admin->email;
                self::add($data, $trySendNow);
            }
        }
    }

}
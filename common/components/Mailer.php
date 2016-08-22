<?php
namespace api\common\components;

use yii;
use yii\base\Component;
use common\models\User;

class Mailer extends Component
{
    /** @var string */
    public $viewPath = '@api/common/views/mail';

    /** @var string|array Default: `Yii::$app->params['adminEmail']` OR `no-reply@example.com` */
    public $sender;

    /** @var string */
    protected $passwordResetSubject;

    protected $module;

    /**
     * @return string
     */
    public function getPasswordRestSubject()
    {
        if ($this->passwordResetSubject == null) {
            $this->setPasswordResetSubject(Yii::t('api', 'Password reset for {0}', Yii::$app->name));
        }

        return $this->passwordResetSubject;
    }

    /**
     * @param string $passwordResetSubject
     */
    public function setPasswordResetSubject($passwordResetSubject)
    {
        $this->passwordResetSubject = $passwordResetSubject;
    }

    /** @inheritdoc */
    public function init()
    {
        $this->module = Yii::$app->getModule('user');
        parent::init();
    }

    /**
     * Sends an email to a user password reset.
     *
     * @param User  $user
     * @param string  $password
     *
     * @return bool
     */
    public function sendPasswordResetMessage(User $user, $password)
    {
        return $this->sendMessage($user->email,
            $this->getPasswordRestSubject(),
            'password-reset',
            ['user' => $user, 'password' => $password, 'module' => $this->module]
        );
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array  $params
     *
     * @return bool
     */
    protected function sendMessage($to, $subject, $view, $params = [])
    {
        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = Yii::$app->mailer;
        $mailer->viewPath = $this->viewPath;
        $mailer->getView()->theme = Yii::$app->view->theme;

        if ($this->sender === null) {
            $this->sender = isset(Yii::$app->params['adminEmail']) ? Yii::$app->params['adminEmail'] : 'no-reply@example.com';
        }

        return $mailer->compose(['html' => $view, 'text' => 'text/' . $view], $params)
            ->setTo($to)
            ->setFrom($this->sender)
            ->setSubject($subject)
            ->send();
    }
}

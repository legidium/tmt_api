<?php
namespace api\modules\v1\models\request\common;

use yii;
use common\models\User;
use api\modules\v1\models\request\Base;
use api\modules\v1\models\request\Error;

class AuthLicense extends Base
{
    public $login;
    public $pass;
    public $confirm;

    /** @var  \common\models\User */
    protected $user;

    /**
     * @return null|\common\models\User
     */
    public function getUser()
    {
        if (!$this->user) {
            $this->user = User::findByUsername($this->login);
        }

        return $this->user;
    }

    /**
     * Define rules for validation
     */
    public function rules()
    {
        return [
            ['login', 'required', 'message' => Yii::t('api', 'Login field is required')],
            ['pass',  'required', 'message' => Yii::t('api', 'Password field is required')],
            ['login', 'validateLogin'],
            ['pass', 'validatePassword'],
            ['confirm', 'required', 'message' => Yii::t('api', 'Confirm field is required')],
            ['confirm', 'boolean', 'message' => Yii::t('api', 'Confirm field must be of boolean type')],
        ];  
    }

    /**
     * Validates login and password
     * @param $attribute
     * @param $params
     */
    public function validateLogin($attribute, $params)
    {
        if (!$this->hasErrors()) {

            $user = $this->getUser();

            if (!$user) {
                $this->addError($attribute, Yii::t('api', 'Invalid login'));
                $this->setErrorCode(Error::AUTH, Error::AUTH_BAD_CREDENTIALS);
            }
        }
    }

    /**
     * Validates login and password
     * @param $attribute
     * @param $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {

            $user = $this->getUser();

            if ($user && !$user->validatePassword($this->$attribute)) {
                $this->addError($attribute, Yii::t('api', 'Invalid password'));
                $this->setErrorCode(Error::AUTH, Error::AUTH_BAD_CREDENTIALS);
            }
        }
    }

    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            if (($user = $this->getUser())) {
                $this->confirm
                    ? $user->confirmLicense()
                    : $user->resetLicense();
            } else {
                $this->setErrorCode(Error::BASE, Error::BASE_INTERNAL_FAILURE);
            }
        }

        return $this;
    }
}

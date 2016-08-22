<?php
namespace api\modules\v1\models\request\common;

use yii;
use common\models\User;
use common\models\AuthAccessToken as AccessToken;
use api\modules\v1\models\request\Base;
use api\modules\v1\models\request\Error;

class AuthAccessToken extends Base
{
    public $login;
    public $pass;
    public $access_token;
    public $expires_in;
    public $refresh_token;
    public $role;
    public $license_url;

    /** @var  \common\models\User */
    private $_user;

    /**
     * @return null|\common\models\User
     */
    protected function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findByUsername($this->login);
        }
        return $this->_user;
    }

    /**
     * @return null|string
     */
    protected function getLicenseUrl()
    {
        // TODO: Obtain license using settings component
        return isset(Yii::$app->params['licenseUrl']) ? Yii::$app->params['licenseUrl'] : null;
    }

    /**
     * Define rules for validation
     */
    public function rules()
    {
        return [
            ['login', 'required', 'message' => Yii::t('api', 'Login is required')],
            ['pass',  'required', 'message' => Yii::t('api', 'Password is required')],
            ['login', 'validateLogin'],
            ['pass', 'validatePassword'],
        ];
    }

    /**
     * Validates login
     * @param $attribute
     * @param $params
     */
    public function validateLogin($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!($user = $this->getUser())) {
                $this->addError($attribute, Yii::t('api', 'Invalid login'));
                $this->setErrorCode(Error::AUTH, Error::AUTH_BAD_CREDENTIALS);
            }
        }
    }

    /**
     * Validates password
     * @param $attribute
     * @param $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (($user = $this->getUser()) && !$user->validatePassword($this->$attribute)) {
                $this->addError($attribute, Yii::t('api', 'Invalid password'));
                $this->setErrorCode(Error::AUTH, Error::AUTH_BAD_CREDENTIALS);
            }
        }
    }

    /** @inheritdoc */
    public function afterValidate()
    {
        parent::afterValidate();

        // Check whether a license is confirmed
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user->license) {
                $this->license_url = $this->getLicenseUrl();
                $this->setErrorCode(Error::AUTH, Error::AUTH_LICENSE_REQUIRED);
            }
        }

        if (!$this->isSuccess() && Yii::$app->request->isPost) {
            $errorCode = $this->getErrorCode();
            $badCredentials  = Error::code(Error::AUTH, Error::AUTH_BAD_CREDENTIALS) == $errorCode;
            $licenseRequired = Error::code(Error::AUTH, Error::AUTH_LICENSE_REQUIRED) == $errorCode;
            $internalError   = Error::code(Error::BASE, Error::BASE_INTERNAL_FAILURE) == $errorCode;

            $is500 = $internalError;
            $is401 = $badCredentials | $licenseRequired;
            $statusCode = $is500 ? 500 : $is401 ? 401 : 400;

            Yii::$app->response->setStatusCode($statusCode);
        }
    }



    /** @inheritdoc */
    public function run()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $token = AccessToken::create($user->id);
            if ($token) {
                $this->access_token  = $token->code;
                $this->expires_in    = $token->expires;
                $this->refresh_token = $token->refresh_code;

                $roles = [
                    'employee' => 0,
                    'chief'    => 1,
                    'manager'  => 2,
                ];

                $role = $user->getRole();
                $this->role = isset($roles[$role]) ? $roles[$role] : null;
            }
        }

        return $this;
    }

    /** @inheritdoc */
    protected function response()
    {
        return [
            'default' => [
                'access_token'  => 'access_token',
                'expires_in'    => 'expires_in',
                'refresh_token' => 'refresh_token',
                'role'          => 'role',
            ]
        ];
    }

    protected function responseError()
    {
        $response = parent::responseError();

        if ($this->checkErrorCode(Error::AUTH, Error::AUTH_LICENSE_REQUIRED)) {
            $response['license_url'] = 'license_url';
        }

        return $response;
    }
}

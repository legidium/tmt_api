<?php
namespace api\modules\v1\filters;

use Yii;
use common\models\AuthAccessToken;
use api\modules\v1\exceptions\TokenExpiredException;
use api\modules\v1\exceptions\BadTokenException;

class HttpBearerAuth extends \yii\filters\auth\HttpBearerAuth
{
    private $_authToken;

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            $authToken = $this->getAuthToken($matches[1]);

            if (!$authToken) {
                $this->handleAuthTokenFailure($response);
                return null;
            }

            if ($authToken->isExpired()) {
                $this->handleAuthTokenExpiredFailure($response);
                return null;
            }

            $identity = $user->loginByAccessToken($matches[1], get_class($this));
            if ($identity === null) {
                $this->handleFailure($response);
            }
            return $identity;
        }

        return null;
    }

    /**
     * @param $token
     * @return AuthAccessToken
     */
    protected function getAuthToken($token)
    {
        if ($this->_authToken == null) {
            $this->_authToken = AuthAccessToken::findOne(['code' => $token]);
        }
        return $this->_authToken;
    }

    /**
     * @param $response
     * @throws BadTokenException
     */
    protected function handleAuthTokenFailure($response)
    {
        throw new BadTokenException();
    }

    /**
     * @param $response
     * @throws TokenExpiredException
     */
    protected function handleAuthTokenExpiredFailure($response)
    {
        throw new TokenExpiredException();
    }
}

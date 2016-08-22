<?php
namespace api\modules\v1\exceptions;

use api\modules\v1\models\request\Error;

class TokenExpiredException extends ApiException
{
    /** @inheritdoc */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        $message = Error::message(Error::BASE, Error::BASE_TOKEN_EXPIRED);
        $code = Error::code(Error::BASE, Error::BASE_TOKEN_EXPIRED);

        parent::__construct(401,$message, $code, $previous);
    }
}

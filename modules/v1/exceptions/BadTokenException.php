<?php
namespace api\modules\v1\exceptions;

use api\modules\v1\models\request\Error;

class BadTokenException extends ApiException
{
    /** @inheritdoc */
    public function __construct(\Exception $previous = null)
    {
        $message = Error::message(Error::BASE, Error::BASE_BAD_TOKEN);
        $code = Error::code(Error::BASE, Error::BASE_BAD_TOKEN);
        
        parent::__construct(401,$message, $code, $previous);
    }
}

<?php
namespace api\modules\v1\models\request;

abstract class Error
{
    // Base errors
    const BASE = 100;
    const BASE_INTERNAL_FAILURE = 1;
    const BASE_BAD_REQUEST      = 2;
    const BASE_BAD_TOKEN        = 3;
    const BASE_TOKEN_EXPIRED    = 4;
    const BASE_INVALID_VALUE    = 5;
    const BASE_ACTION_REJECTED  = 6;
    const BASE_CREATE_REJECTED  = 7;
    const BASE_UPDATE_REJECTED  = 8;

    // Auth errors
    const AUTH = 200;
    const AUTH_BAD_CREDENTIALS         = 1;
    const AUTH_LICENSE_REQUIRED        = 2;
    const AUTH_PASSWORD_RESET_FAILURE  = 3;
    const AUTH_PASSWORD_MAIL_FAILURE   = 4;

    // Activities errors
    const ACTIVITIES = 300;
    const ACTIVITIES_INVALID_STATUS        = 1;
    const ACTIVITIES_INVALID_REJECT_TYPE   = 2;
    const ACTIVITIES_INVALID_DATE_RANGE    = 3;
    const ACTIVITIES_CREATE_REJECTED       = 4;
    const ACTIVITIES_USERS_CREATE_REJECTED = 5;

    // Jobs errors
    const JOBS = 400;
    const JOBS_INVALID_STATUS        = 1;
    const JOBS_INVALID_REJECT_TYPE   = 2;
    const JOBS_INVALID_DATE_RANGE    = 3;
    const JOBS_CREATE_REJECTED       = 4;
    const JOBS_TASKS_CREATE_REJECTED = 5;
    const JOBS_USERS_CREATE_REJECTED = 6;

    // Tasks errors
    const TASKS = 500;
    const TASKS_CREATE_REJECTED        = 1;
    const TASKS_PHOTOS_APPEND_REJECTED = 2;

    // Forms errors
    const FORMS = 600;
    const FORMS_INVALID_STATUS = 1;
    const FORMS_INVALID_FIELDS = 2;

    // Location errors
    const LOCATION = 700;
    const LOCATION_INVALID_COORDINATES = 1;

    // Error messages
    public static $messages = [
        self::BASE => [
            self::BASE_INTERNAL_FAILURE => 'Internal error',
            self::BASE_BAD_REQUEST      => 'Bad request',
            self::BASE_BAD_TOKEN        => 'Bad token',
            self::BASE_TOKEN_EXPIRED    => 'Token expired',
            self::BASE_INVALID_VALUE    => 'Invalid value',
            self::BASE_ACTION_REJECTED  => 'Action rejected',
            self::BASE_CREATE_REJECTED  => 'Create rejected',
            self::BASE_UPDATE_REJECTED  => 'Update rejected',
        ],
        self::AUTH => [
            self::AUTH_BAD_CREDENTIALS        => 'Bad credentials',
            self::AUTH_LICENSE_REQUIRED       => 'License confirmation required',
            self::AUTH_PASSWORD_RESET_FAILURE => 'Password reset failure',
            self::AUTH_PASSWORD_MAIL_FAILURE  => 'Password mail failure',
        ],
        self::ACTIVITIES => [
            self::ACTIVITIES_INVALID_STATUS        => 'Invalid activity status',
            self::ACTIVITIES_INVALID_REJECT_TYPE   => 'Invalid activity reject type',
            self::ACTIVITIES_INVALID_DATE_RANGE    => 'Invalid activity date range',
            self::ACTIVITIES_CREATE_REJECTED       => 'Create rejected',
            self::ACTIVITIES_USERS_CREATE_REJECTED => 'Users create rejected',
        ],
        self::JOBS => [
            self::JOBS_INVALID_STATUS        => 'Invalid job status',
            self::JOBS_INVALID_REJECT_TYPE   => 'Invalid job reject type',
            self::JOBS_INVALID_DATE_RANGE    => 'Invalid job date range',
            self::JOBS_CREATE_REJECTED       => 'Create rejected',
            self::JOBS_TASKS_CREATE_REJECTED => 'Tasks create rejected',
            self::JOBS_USERS_CREATE_REJECTED => 'Users create rejected',
        ],
        self::TASKS => [
            self::TASKS_CREATE_REJECTED        => 'Create rejected',
            self::TASKS_PHOTOS_APPEND_REJECTED => 'Photos append rejected',
        ],
        self::FORMS => [
            self::FORMS_INVALID_STATUS => 'Invalid status',
            self::FORMS_INVALID_FIELDS => 'Invalid form fields',
        ],
        self::LOCATION => [
            self::LOCATION_INVALID_COORDINATES => 'Invalid location coordinates',
        ],
    ];

    /**
     * Returns error message
     * @param $base
     * @param $code integer
     * @return string
     */
    public static function message($base, $code)
    {
        return isset(self::$messages[$base][$code]) ? self::$messages[$base][$code] : '';
    }

    /**
     * Returns error code
     * @param integer $base
     * @param integer $code
     * @return integer
     */
    public static function code($base, $code)
    {
        return $base + $code;
    }
}

<?php

use yii\web\Response;
use yii\web\HttpException;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id'         => 'api',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => [
        'log',
        'v1' => 'api\modules\v1\Bootstrap',
    ],
    'language'   => 'ru',
    'modules'    => [
        //'user' => null,
        'rbac' => [
            'class' => 'dektrium\rbac\Module',
        ],
        'v1'   => [
            'basePath' => '@app/modules/v1',
            'class'    => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'request'    => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response'   => [
            'class'         => 'yii\web\Response',
            'format'        => Response::FORMAT_JSON,
            'on beforeSend' => function($event) {
                /** @var Response $response */
                $response = $event->sender;
                $exception = Yii::$app->errorHandler->exception;

                if (Yii::$app->request->isOptions) {
                    $response->data = null;
                    return;
                }

                if (!$response->isSuccessful) {
                    if ($exception) {
                        $response->data = [
                            'success'       => 0,
                            'error_code'    => $exception->getCode(),
                            'error_message' => $exception->getMessage(),
                            'error_log'     => null,
                        ];
                    } elseif ($response->data === null) {
                        $response->data = [
                            'success'       => 0,
                            'error_code'    => 0,
                            'error_message' => null,
                            'error_log'     => null,
                        ];
                    }

                    if ($exception) {
                        $response->data['error_debug'] = [
                            'code'    => $exception->getCode(),
                            'message' => $exception->getMessage(),
                            'file'    => $exception->getFile(),
                            'line'    => $exception->getLine(),
                            'trace'   => $exception->getTraceAsString(),
                        ];
                    }
                }
            },
        ],
        'user'       => [
            'identityClass' => 'common\models\User',
            'enableSession' => false,
            'loginUrl'      => null,
        ],
        'log'        => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'except' => [
                        'yii\web\HttpException:401',
                    ],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => true,
            'showScriptName'      => false,
            'rules'               => array(
                // Help files. Remove in production !!!
                '/v1/help/api'                                    => 'v1/help/api',
                '/v1/help/api-list'                               => 'v1/help/api-list',
                '/v1/help/api-statuses'                           => 'v1/help/api-statuses',

                // Authorization
                '/v1/auth/authorize'                               => 'v1/auth/authorize',
                '/v1/auth/refresh'                                 => 'v1/auth/refresh',
                '/v1/auth/password'                                => 'v1/auth/password',
                '/v1/auth/license'                                 => 'v1/auth/license',

                // Activities
                'GET,OPTIONS /v1/activities'                               => 'v1/activities/index',
                'POST,OPTIONS /v1/activities'                              => 'v1/activities/create',
                'GET,OPTIONS  /v1/activities/<id:\d+>'                     => 'v1/activities/view',
                'POST,OPTIONS /v1/activities/<id:\d+>'                     => 'v1/activities/update',
                'GET,OPTIONS  /v1/activities/<id:\d+>/users'               => 'v1/activities/users-index',
                'POST,OPTIONS /v1/activities/<id:\d+>/users'               => 'v1/activities/users-create',
                'GET,OPTIONS  /v1/activities/<id:\d+>/users/<user_id:\d+>' => 'v1/activities/users-view',
                'POST,OPTIONS /v1/activities/<id:\d+>/users/<user_id:\d+>' => 'v1/activities/users-update',
                'GET,OPTIONS  /v1/activities/<id:\d+>/forms'               => 'v1/activities/forms-index',
                'GET,OPTIONS  /v1/activities/types'                        => 'v1/activities/types-index',

                // Jobs
                'GET  /v1/jobs'                                    => 'v1/jobs/index',
                'POST /v1/jobs'                                    => 'v1/jobs/create',
                'GET  /v1/jobs/<id:\d+>'                           => 'v1/jobs/view',
                'POST /v1/jobs/<id:\d+>'                           => 'v1/jobs/update',
                'GET  /v1/jobs/<id:\d+>/users'                     => 'v1/jobs/users-index',
                'POST /v1/jobs/<id:\d+>/users'                     => 'v1/jobs/users-create',
                'GET  /v1/jobs/<id:\d+>/tasks'                     => 'v1/jobs/tasks-index',
                'POST /v1/jobs/<id:\d+>/tasks'                     => 'v1/jobs/tasks-create',

                // Tasks
                'GET  /v1/tasks'                                   => 'v1/tasks/index',
                'POST /v1/tasks'                                   => 'v1/tasks/create',
                'GET  /v1/tasks/<id:\d+>'                          => 'v1/tasks/view',
                'POST /v1/tasks/<id:\d+>'                          => 'v1/tasks/update',
                'GET  /v1/tasks/<id:\d+>/users'                    => 'v1/tasks/users-index',
                'POST /v1/tasks/<id:\d+>/users'                    => 'v1/tasks/users-create',
                'GET  /v1/tasks/<id:\d+>/users/<user_id:\d+>'      => 'v1/tasks/users-index',

                'GET  /v1/tasks/<id:\d+>/photos'                   => 'v1/tasks/photos-index',
                'POST /v1/tasks/<id:\d+>/photos'                   => 'v1/tasks/photos-create',

                // Forms
                'GET  /v1/forms'                                   => 'v1/forms/index',
                'POST /v1/forms'                                   => 'v1/forms/create',
                'GET  /v1/forms/<activity_id:\d+>-<user_id:\d+>'   => 'v1/forms/view',
                'POST /v1/forms/<activity_id:\d+>-<user_id:\d+>'   => 'v1/forms/update',

                // Users
                '/v1/users'                                        => 'v1/users/index',
                '/v1/users/<id:\d+>'                               => 'v1/users/view',
                
                // Team
                '/v1/team'                                         => 'v1/teams/view-own',
                
                // Standards
                'GET  /v1/standards'                               => 'v1/standards/index',
                'POST /v1/standards'                               => 'v1/standards/create',
                'GET  /v1/standards/<id:\d+>'                      => 'v1/standards/view',
                'POST /v1/standards/<id:\d+>'                      => 'v1/standards/update',

                // Location
                '/v1/location'                                     => 'v1/location/update',
            ),
        ],
    ],
    'params'     => $params,
];

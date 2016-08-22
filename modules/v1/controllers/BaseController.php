<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use common\traits\FindModelTrait;
use api\modules\v1\filters\HttpBearerAuth;

abstract class BaseController extends \yii\rest\Controller
{
    use FindModelTrait;

    /** @var bool */
    public $isAuthOptional = false;

    /** @inheritdoc */
    public function init()
    {
        parent::init();

        // Fix CORS errors for GET requests
        header("Access-Control-Allow-Origin: *");

        // Skip authentication for CORS "preflight" requests
        if (Yii::$app->request->getMethod() == 'OPTIONS') {
            return false;
        }

        // We must authenticate users here,
        // so we can have the user identity object ready in the `actions` method.
        $auth = Yii::createObject(HttpBearerAuth::className());
        $response = Yii::$app->getResponse();
        $identity = null;

        try {
            $identity = $auth->authenticate(
                Yii::$app->getUser(),
                Yii::$app->getRequest(),
                $response
            );
        } catch (UnauthorizedHttpException $e) {
            if (!$this->isAuthOptional) {
                throw $e;
            }
        }

        if (!$identity && !$this->isAuthOptional) {
            $auth->challenge($response);
            $auth->handleFailure($response);
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = array_merge(
            [
                [
                    'class' => Cors::className(),
                    'cors' => [
                        'Origin' => ['*'],
                        'Access-Control-Request-Method' => ['GET', 'POST', 'OPTIONS'],
                        'Access-Control-Request-Headers' => ['*'],
                        'Access-Control-Max-Age' => 86400,
                    ]
                ]
            ],
            parent::behaviors(),
            [
                'contentNegotiator' => [
                    'class'   => ContentNegotiator::className(),
                    'formats' => [
                        'application/json' => Response::FORMAT_JSON,
                    ]
                ],
                'authenticator' => [
                    'class' => HttpBearerAuth::className(),
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        // Авторизация
                        // ---------------------------------------------------------------------------------------------
                        // Разрешаем авторизацию, обновление токена, сброс пароля, подтверждение согласия с лицензией
                        // API: auth/authorize|refresh|password
                        [
                            'allow'       => true,
                            'controllers' => ['v1/auth'],
                            'actions'     => ['authorize', 'refresh', 'password', 'license'],
                        ],

                        // Пользователи
                        // ---------------------------------------------------------------------------------------------
                        // Разрешаем получение списка пользователей
                        // API: GET users
                        [
                            'allow'       => true,
                            'controllers' => ['v1/users'],
                            'actions'     => ['index'],
                            'roles'       => ['api.users.index'],
                        ],
                        // Разрешаем получение данных пользователя
                        // API: GET users/{id}
                        [
                            'allow'       => true,
                            'controllers' => ['v1/users'],
                            'actions'     => ['view'],
                            'roles'       => ['api.users.view'],
                        ],

                        // Команда
                        // ---------------------------------------------------------------------------------------------
                        // Разрешаем получение данных о команде пользователя
                        // API: GET team
                        [
                            'allow'       => true,
                            'controllers' => ['v1/teams'],
                            'actions'     => ['view-own'],
                            'roles'       => ['api.teams.view-own'],
                        ],

                        // Активности
                        // ---------------------------------------------------------------------------------------------
                        // Разрешаем получение списка активностей
                        // API: GET activities
                        [
                            'allow'       => true,
                            'controllers' => ['v1/activities'],
                            'actions'     => ['index'],
                            'roles'       => ['api.activities.index'],
                        ],
                        // Разрешаем получение данных активности
                        // API: GET activities/{id}
                        [
                            'allow'       => true,
                            'controllers' => ['v1/activities'],
                            'actions'     => ['view'],
                            'roles'       => ['api.activities.view'],
                        ],
                        // Разрешаем создание активности
                        // API: POST activities
                        [
                            'allow'       => true,
                            'controllers' => ['v1/activities'],
                            'actions'     => ['create'],
                            'roles'       => ['api.activities.create'],
                        ],
                        // Разрешаем обновление данных активности
                        // API: POST activities/{id}
                        [
                            'allow'       => true,
                            'controllers' => ['v1/activities'],
                            'actions'     => ['update'],
                            'roles'       => ['api.activities.update'],
                        ],
                        // Разрешаем получение списка исполнителей активности
                        // API: GET activities/{id}/users
                        [
                            'allow'       => true,
                            'controllers' => ['v1/activities'],
                            'actions'     => ['users-index'],
                            'roles'       => ['api.activities.users-index'],
                        ],
                        // Разрешаем добавление пользователей в списк исполнителей активности
                        // API: POST activities/{id}/users
                        [
                            'allow'       => true,
                            'controllers' => ['v1/activities'],
                            'actions'     => ['users-index'],
                            'roles'       => ['api.activities.users-create'],
                        ],
                        // Разрешаем получение данных пользователя для активности
                        // API: GET activities/{id}/users/{user_id}
                        [
                            'allow'       => true,
                            'controllers' => ['v1/activities'],
                            'actions'     => ['users-view'],
                            'roles'       => ['api.activities.users-view'],
                        ],
                        // Разрешаем обновление данных пользователя для активности
                        // API: POST activities/{id}/users/{user_id}
                        [
                            'allow'       => true,
                            'controllers' => ['v1/activities'],
                            'actions'     => ['users-update'],
                            'roles'       => ['api.activities.users-update'],
                        ],
                        // Разрешаем обновление данных пользователя для активности
                        // API: POST activities/types
                        [
                            'allow'       => true,
                            'controllers' => ['v1/activities'],
                            'actions'     => ['types-index'],
                            'roles'       => ['api.activities.types-index'],
                        ],

                        // Задания
                        // ---------------------------------------------------------------------------------------------
                        // Разрешаем получение списка заданий
                        // API: GET jobs
                        [
                            'allow'       => true,
                            'controllers' => ['v1/jobs'],
                            'actions'     => ['index'],
                            'roles'       => ['api.jobs.index'],
                        ],
                    ],
                ],
            ]
        );

        if (Yii::$app->request->getMethod() == 'OPTIONS') {
            unset($behaviors['verbFilter']);
            unset($behaviors['authenticator']);
            unset($behaviors['access']);
        }

        return $behaviors;
    }

    /**
     * @param mixed $model
     * @param string|null $message
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function assertModel($model, $message = null)
    {
        if (!$model) {
            throw new NotFoundHttpException($message ? $message : 'Object not found');
        } else {
            return $model;
        }
    }

    /**
     * @param string $modelClass
     * @param array $params
     * @return array
     */
    protected function process($modelClass, array $params = [])
    {
        /** @var \api\modules\v1\models\request\Base $model */
        $model = new $modelClass($params);
        $model->load(Yii::$app->request->getBodyParams(), '');

        return $model->run()->toArray();
    }
}

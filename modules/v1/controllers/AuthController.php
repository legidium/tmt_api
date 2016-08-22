<?php
namespace api\modules\v1\controllers;

use yii;

/**
 * Auth Controller
 */
class AuthController extends BaseController
{
    public $isAuthOptional = true;

    /** @inheritdoc */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);

        return $behaviors;
    }

    /** @inheritdoc */
    public function verbs()
    {
        return [
            'authorize' => ['POST'],
            'refresh'   => ['POST'],
            'password'  => ['POST'],
            'license'   => ['POST'],
        ];
    }

    /** @inheritdoc */
    public function actions()
    {
        return [
            'authorize'  => [
                'class'      => 'api\modules\v1\controllers\actions\BaseCreate',
                'modelClass' => 'api\modules\v1\models\request\common\AuthAccessToken',
            ],
            'refresh'  => [
                'class'      => 'api\modules\v1\controllers\actions\BaseCreate',
                'modelClass' => 'api\modules\v1\models\request\common\AuthRefreshToken',
            ],
            'password' => [
                'class'      => 'api\modules\v1\controllers\actions\BaseCreate',
                'modelClass' => 'api\modules\v1\models\request\common\AuthPassword',
            ],
            'license'  => [
                'class'      => 'api\modules\v1\controllers\actions\BaseCreate',
                'modelClass' => 'api\modules\v1\models\request\common\AuthLicense',
            ]
        ];
    }
}

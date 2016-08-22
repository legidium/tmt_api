<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use common\models\ActivityForm;
use common\models\ActivityFormUser;

/**
 *  Forms Controller
 */
class FormsController extends BaseController
{
    /** @inheritdoc */
    public function verbs()
    {
        return [
            'index'  => ['GET'],
            'view'   => ['GET'],
            'create' => ['POST'],
            'update' => ['POST'],
        ];
    }

    /** @inheritdoc */
    public function actions()
    {
        $user = Yii::$app->user;

        if ($user->can('chief')) {
            return [
                'index' => [
                    'class'      => 'api\modules\v1\controllers\actions\Index',
                    'modelClass' => 'api\modules\v1\models\request\chief\FormsIndex',
                    'prepareDataProvider' => function() use ($user) {
                        return new ActiveDataProvider([
                            'query' => ActivityForm::find()->byOwnerId($user->id)
                        ]);
                    },
                ],
                'view'  => [
                    'class'          => 'api\modules\v1\controllers\actions\chief\FormsView',
                    'modelClass'     => 'api\modules\v1\models\request\chief\FormsView',
                    'findModelClass' => 'common\models\ActivityForm',
                ],
                'create'  => [
                    'class'      => 'api\modules\v1\controllers\actions\BaseCreate',
                    'modelClass' => 'api\modules\v1\models\request\chief\FormsCreate',

                    'scenario'       => ActivityForm::SCENARIO_API_CREATE,
                ],
                'update'  => [
                    'class'          => 'api\modules\v1\controllers\actions\chief\FormsUpdate',
                    'modelClass'     => 'api\modules\v1\models\request\chief\FormsUpdate',
                    'findModelClass' => 'common\models\ActivityForm',
                    'scenario'       => ActivityForm::SCENARIO_API_UPDATE,
                ]
            ];
        } elseif ($user->can('employee')) {
            return [
                'index' => [
                    'class'               => 'api\modules\v1\controllers\actions\Index',
                    'modelClass'          => 'api\modules\v1\models\request\employee\FormsIndex',
                    'prepareDataProvider' => function() use ($user) {
                        return new ActiveDataProvider([
                            'query' => ActivityForm::find()
                                ->innerJoinWith('activity')
                                ->byUserId($user->id)
                        ]);
                    },
                ],
                'view'  => [
                    'class'          => 'api\modules\v1\controllers\actions\employee\FormsView',
                    'modelClass'     => 'api\modules\v1\models\request\employee\FormsView',
                    'findModelClass' => 'common\models\ActivityFormUser',
                ],
                'update'  => [
                    'class'          => 'api\modules\v1\controllers\actions\employee\FormsUpdate',
                    'modelClass'     => 'api\modules\v1\models\request\employee\FormsUpdate',
                    'findModelClass' => 'common\models\ActivityFormUser',
                    'scenario'       => ActivityFormUser::SCENARIO_API_UPDATE,
                ]
            ];
        }

        throw new ForbiddenHttpException(Yii::t('api', 'You are not allowed to perform this action.'));
    }
}

<?php
namespace api\modules\v1\controllers;

use yii;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use common\models\Activity;
use common\models\ActivityForm;
use common\models\ActivityType;
use common\models\ActivityUser;

/**
 * Activities Controller
 */
class ActivitiesController extends BaseController
{
    /** @inheritdoc */
    public function verbs()
    {
        return [
            'index'        => ['GET'],
            'view'         => ['GET'],
            'create'       => ['POST'],
            'update'       => ['POST'],
            'users-index'  => ['GET'],
            'users-view'   => ['GET'],
            'users-create' => ['POST'],
            'users-update' => ['POST'],
            'forms-index'  => ['GET'],
            'types-index'  => ['GET'],
        ];
    }

    /** @inheritdoc */
    public function actions()
    {
        /** @var \common\models\User $user */
        $user = Yii::$app->user->identity;

        if (Yii::$app->user->can('manager')) { // Экшены для МН
            return [
                'index'        => [
                    'class'               => 'api\modules\v1\controllers\actions\chief\ActivitiesIndex',
                    'modelClass'          => 'api\modules\v1\models\request\chief\ActivitiesIndex',
                    'prepareDataProvider' => function() use ($user) {
                        return new ActiveDataProvider([
                            'query' => Activity::find()
                                ->filter()
                                ->orderByDate()
                        ]);
                    },
                ],
            ];

        } elseif (Yii::$app->user->can('chief') || Yii::$app->user->can('manager')) { // Экшены для ПН
            return [
                'index'        => [
                    'class'               => 'api\modules\v1\controllers\actions\chief\ActivitiesIndex',
                    'modelClass'          => 'api\modules\v1\models\request\chief\ActivitiesIndex',
                    'prepareDataProvider' => function() use ($user) {
                        return new ActiveDataProvider([
                            'query' => Activity::find()
                                ->filter()
                                ->byOwnerId($user->id)
                                ->orderByDate()
                        ]);
                    },
                ],
                'view'         => [
                    'class'          => 'api\modules\v1\controllers\actions\View',
                    'modelClass'     => 'api\modules\v1\models\request\chief\ActivitiesView',
                    'findModelClass' => 'common\models\Activity',
                ],
                'create'       => [
                    'class'          => 'api\modules\v1\controllers\actions\Create',
                    'modelClass'     => 'api\modules\v1\models\request\chief\ActivitiesCreate',
                    'findModelClass' => 'common\models\Activity',
                    'scenario'       => Activity::SCENARIO_API_CREATE,
                ],
                'update'       => [
                    'class'          => 'api\modules\v1\controllers\actions\Update',
                    'modelClass'     => 'api\modules\v1\models\request\chief\ActivitiesUpdate',
                    'findModelClass' => 'common\models\Activity',
                    'scenario'       => Activity::SCENARIO_API_UPDATE,
                ],
                'users-index'  => [
                    'class'               => 'api\modules\v1\controllers\actions\IndexRelated',
                    'modelClass'          => 'api\modules\v1\models\request\chief\ActivitiesUsersIndex',
                    'findModelClass'      => 'common\models\Activity',
                    'prepareDataProvider' => function() {
                        return new ActiveDataProvider([
                            'query' => ActivityUser::find()
                                ->with(['activity', 'user'])
                                ->byActivityId(Yii::$app->request->get('id'))
                        ]);
                    },
                ],
                'users-create' => [
                    'class'          => 'api\modules\v1\controllers\actions\CreateRelated',
                    'modelClass'     => 'api\modules\v1\models\request\chief\ActivitiesUsersCreate',
                    'findModelClass' => 'common\models\Activity',
                ],
                'users-view'   => [
                    'class'      => 'api\modules\v1\controllers\actions\View',
                    'modelClass' => 'api\modules\v1\models\request\chief\ActivitiesUsersView',
                    'findModel'  => function() {
                        return $this->assertModel(
                            ActivityUser::find()
                                ->byActivityId(Yii::$app->request->get('id'))
                                ->byUserId(Yii::$app->request->get('user_id'))
                                ->one()
                        );
                    }
                ],
                'users-update' => [
                    'class'      => 'api\modules\v1\controllers\actions\Update',
                    'modelClass' => 'api\modules\v1\models\request\chief\ActivitiesUsersUpdate',
                    'findModel'  => function() {
                        return $this->assertModel(
                            ActivityUser::find()
                                ->byActivityId(Yii::$app->request->get('id'))
                                ->byUserId(Yii::$app->request->get('user_id'))
                                ->one()
                        );
                    },
                    'scenario'  => ActivityUser::SCENARIO_API_UPDATE,
                ],
                'forms-index'  => [
                    'class'               => 'api\modules\v1\controllers\actions\Index',
                    'modelClass'          => 'api\modules\v1\models\request\chief\ActivitiesFormsIndex',
                    'prepareDataProvider' => function() use ($user) {
                        return new ActiveDataProvider([
                            'query' => ActivityForm::find()
                                ->byActivityId(Yii::$app->request->get('id'))
                                ->byOwnerId($user->id)
                        ]);
                    },
                ],
                'types-index'  => [
                    'class'               => 'api\modules\v1\controllers\actions\Index',
                    'modelClass'          => 'api\modules\v1\models\request\chief\ActivitiesTypesIndex',
                    'prepareDataProvider' => function() {
                        return new ActiveDataProvider([
                            'query' => ActivityType::find()->with('category')
                        ]);
                    },
                ],
            ];

        } elseif (Yii::$app->user->can('employee')) { // Экшены для ПС
            return [
                'index'       => [
                    'class'               => 'api\modules\v1\controllers\actions\Index',
                    'modelClass'          => 'api\modules\v1\models\request\employee\ActivitiesIndex',
                    'prepareDataProvider' => function() use ($user) {
                        return new ActiveDataProvider([
                            'query' => ActivityUser::find()
                                ->active()
                                ->filter()
                                ->byUserId($user->id)
                                ->orderByDate()
                        ]);
                    },
                ],
                'view'        => [
                    'class'      => 'api\modules\v1\controllers\actions\View',
                    'modelClass' => 'api\modules\v1\models\request\employee\ActivitiesView',
                    'findModel'  => function($id) use ($user) {
                        return $this->assertModel(
                            ActivityUser::find()
                                ->active()
                                ->byActivityId($id)
                                ->byUserId($user->id)
                                ->one()
                        );
                    }
                ],
                'create'      => [
                    'class' => 'api\modules\v1\controllers\actions\Forbidden',
                ],
                'update'      => [
                    'class'      => 'api\modules\v1\controllers\actions\Update',
                    'modelClass' => 'api\modules\v1\models\request\employee\ActivitiesUpdate',
                    'findModel'  => function($id) use ($user) {
                        return $this->assertModel(
                            ActivityUser::find()
                                ->byActivityId($id)
                                ->byUserId($user->id)
                                ->one()
                        );
                    },
                    'scenario'   => ActivityUser::SCENARIO_API_UPDATE,
                ],
                'users-index' => [
                    'class' => 'api\modules\v1\controllers\actions\Forbidden',
                ],
                'forms-index' => [
                    'class' => 'api\modules\v1\controllers\actions\Forbidden',
                ],
                'types-index' => [
                    'class' => 'api\modules\v1\controllers\actions\Forbidden',
                ]
            ];

        } else {
            return [
                'index'        => 'yii\rest\OptionsAction',
                'view'         => 'yii\rest\OptionsAction',
                'create'       => 'yii\rest\OptionsAction',
                'update'       => 'yii\rest\OptionsAction',
                'users-index'  => 'yii\rest\OptionsAction',
                'users-view'   => 'yii\rest\OptionsAction',
                'users-create' => 'yii\rest\OptionsAction',
                'users-update' => 'yii\rest\OptionsAction',
                'forms-index'  => 'yii\rest\OptionsAction',
                'types-index'  => 'yii\rest\OptionsAction',
            ];
        }

        //throw new ForbiddenHttpException(Yii::t('api', 'You are not allowed to perform this action.'));
    }
}

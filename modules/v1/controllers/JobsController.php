<?php
namespace api\modules\v1\controllers;

use yii;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use common\models\Job;
use common\models\JobUser;
use common\models\JobUserTask;
use common\models\Task;

/**
 * JobsController Controller
 */
class JobsController extends BaseController
{
    /** @inheritdoc */
    public function verbs()
    {
        return [
            'index'           => ['GET'],
            'view'            => ['GET'],
            'create'          => ['POST'],
            'update'          => ['POST'],
            'delete'          => ['POST'],
            'users-index'     => ['GET'],
            'users-create'    => ['POST'],
            'tasks-index'     => ['GET'],
            'tasks-create'    => ['POST'],
        ];
    }

    /** @inheritdoc */
    public function actions()
    {
        /** @var \common\models\User $user */
        $user = Yii::$app->user->identity;

        if (Yii::$app->user->can('chief')) {
            return [
                'index' => [
                    'class'               => 'api\modules\v1\controllers\actions\Index',
                    'modelClass'          => 'api\modules\v1\models\request\chief\JobsIndex',
                    'prepareDataProvider' => function() use($user) {
                        return new ActiveDataProvider([
                            'query' => Job::find()
                                ->where(['owner_id' => $user->id])
                                ->with('owner'),
                        ]);
                    },
                ],
                'view'  => [
                    'class'          => 'api\modules\v1\controllers\actions\View',
                    'modelClass'     => 'api\modules\v1\models\request\chief\JobsView',
                    'findModelClass' => 'common\models\Job',
                ],
                'create' => [
                    'class'          => 'api\modules\v1\controllers\actions\Create',
                    'modelClass'     => 'api\modules\v1\models\request\chief\JobsCreate',
                    'findModelClass' => 'common\models\Job',
                    'scenario'       => Job::SCENARIO_API_CREATE,
                ],
                'update' => [
                    'class'          => 'api\modules\v1\controllers\actions\Update',
                    'modelClass'     => 'api\modules\v1\models\request\chief\JobsUpdate',
                    'findModelClass' => 'common\models\Job',
                    'scenario'       => Job::SCENARIO_API_UPDATE,
                ],
                'users-index' => [
                    'class'               => 'api\modules\v1\controllers\actions\Index',
                    'modelClass'          => 'api\modules\v1\models\request\chief\JobsUsersIndex',
                    'prepareDataProvider' => function() {
                        return new ActiveDataProvider([
                            'query' => JobUser::find()
                                ->where(['job_id' => Yii::$app->request->get('id')])
                                ->with('user'),
                        ]);
                    },
                ],
                'users-create' => [
                    'class'          => 'api\modules\v1\controllers\actions\CreateRelated',
                    'modelClass'     => 'api\modules\v1\models\request\chief\JobsUsersCreate',
                    'findModelClass' => 'common\models\Job',
                ],
                'users-index' => [
                    'class'      => 'api\modules\v1\controllers\actions\BaseIndex',
                    'modelClass' => 'api\modules\v1\models\request\chief\JobsUsersIndex',
                ],
                'users-create' => [
                    'class'      => 'api\modules\v1\controllers\actions\BaseIndex',
                    'modelClass' => 'api\modules\v1\models\request\chief\JobsUsersCreate',
                ],
                'tasks-index' => [
                    'class'               => 'api\modules\v1\controllers\actions\Index',
                    'modelClass'          => 'api\modules\v1\models\request\chief\JobsTasksIndex',
                    'prepareDataProvider' => function() {
                        return new ActiveDataProvider([
                            'query' => Task::find()
                                ->where(['job_id' => Yii::$app->request->get('id')])
                        ]);
                    },
                ],
                'tasks-create' => [
                    'class'          => 'api\modules\v1\controllers\actions\CreateRelated',
                    'modelClass'     => 'api\modules\v1\models\request\chief\JobsTasksCreate',
                    'findModelClass' => 'common\models\Job',
                ],
            ];
        } elseif (Yii::$app->user->can('employee')) {
            return [
                'index' => [
                    'class'               => 'api\modules\v1\controllers\actions\Index',
                    'modelClass'          => 'api\modules\v1\models\request\employee\JobsIndex',
                    'prepareDataProvider' => function() use($user) {
                        return new ActiveDataProvider([
                            'query' => JobUser::find()
                                ->withUserTasks()
                                ->byUserId($user->id)
                                ->active()
                        ]);
                    },
                ],
                'view'  => [
                    'class'      => 'api\modules\v1\controllers\actions\View',
                    'modelClass' => 'api\modules\v1\models\request\employee\JobsView',
                    'findModel'  => function($id) use ($user) {
                        return $this->assertModel(
                            JobUser::find()
                                ->byJobId($id)
                                ->byUserId($user->id)
                                ->active()
                                ->one()
                        );
                    }
                ],
                'create' => [
                    'class'      => 'api\modules\v1\controllers\actions\Forbidden',
                ],
                'update' => [
                    'class'      => 'api\modules\v1\controllers\actions\Update',
                    'modelClass' => 'api\modules\v1\models\request\employee\JobsUpdate',
                    'findModel'  => function($id) use ($user) {
                        return $this->assertModel(
                            JobUser::find()
                                ->byJobId($id)
                                ->byUserId($user->id)
                                ->active()
                                ->one()
                        );
                    },
                    'scenario'   => JobUser::SCENARIO_API_UPDATE,
                ],
                'tasks-index' => [
                    'class'               => 'api\modules\v1\controllers\actions\Index',
                    'modelClass'          => 'api\modules\v1\models\request\employee\JobsTasksIndex',
                    'prepareDataProvider' => function($id) {
                        return new ActiveDataProvider([
                            'query' => JobUserTask::find()
                                ->byJobId(Yii::$app->request->get('id'))
                        ]);
                    },
                ],
                'tasks-create' => [
                    'class'      => 'api\modules\v1\controllers\actions\Forbidden',
                ],
            ];
        }

        throw new ForbiddenHttpException(Yii::t('api', 'You are not allowed to perform this action.'));
    }
}

<?php
namespace api\modules\v1\controllers;

use yii;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use common\models\JobUserTask;
use common\models\Task;

/**
 * TasksController Controller
 */
class TasksController extends BaseController
{
    /** @inheritdoc */
    public function verbs()
    {
        return [
            'index'         => ['GET'],
            'view'          => ['GET'],
            'create'        => ['POST'],
            'update'        => ['POST'],
            'photos-index'  => ['GET'],
            'photos-create' => ['POST'],
        ];
    }

    /** @inheritdoc */
    public function actions()
    {
        /** @var \common\models\User $user */
        $user = Yii::$app->user->identity;

        if (Yii::$app->user->can('chief')) {
            return [
                'index'         => [
                    'class'               => 'api\modules\v1\controllers\actions\Index',
                    'modelClass'          => 'api\modules\v1\models\request\chief\TasksIndex',
                    'prepareDataProvider' => function() use($user) {
                        return new ActiveDataProvider([
                            'query' => Task::find()
                                ->joinWith([
                                    'job' => function(ActiveQuery $query) use($user) {
                                        $query->where(['job.owner_id' => $user->id]);
                                    }
                                ])
                                ->orderBy('job_id')
                        ]);
                    },
                ],
                'view'          => [
                    'class'          => 'api\modules\v1\controllers\actions\View',
                    'modelClass'     => 'api\modules\v1\models\request\chief\TasksView',
                    'findModelClass' => 'common\models\Task',
                ],
                'create'        => [
                    'class'          => 'api\modules\v1\controllers\actions\Create',
                    'modelClass'     => 'api\modules\v1\models\request\chief\TasksCreate',
                    'findModelClass' => 'common\models\Task',
                    'scenario'       => Task::SCENARIO_API_CREATE,
                ],
                'update'        => [
                    'class'      => 'api\modules\v1\controllers\actions\Update',
                    'modelClass' => 'api\modules\v1\models\request\chief\TasksUpdate',
                    'findModelClass' => 'common\models\Task',
                    'scenario'       => Task::SCENARIO_API_UPDATE,
                ],
                'photos-index'  => [
                    'class'          => 'api\modules\v1\controllers\actions\View',
                    'modelClass'     => 'api\modules\v1\models\request\chief\TasksPhotosIndex',
                    'findModelClass' => 'common\models\Task',
                ],
                'photos-create' => [
                    'class'          => 'api\modules\v1\controllers\actions\CreateRelated',
                    'modelClass'     => 'api\modules\v1\models\request\chief\TasksPhotosCreate',
                    'findModelClass' => 'common\models\Task',
                    'scenario'       => Task::SCENARIO_API_UPDATE,
                ],
            ];
        } elseif (Yii::$app->user->can('employee')) {
            return [
                'index'         => [
                    'class'               => 'api\modules\v1\controllers\actions\Index',
                    'modelClass'          => 'api\modules\v1\models\request\employee\TasksIndex',
                    'prepareDataProvider' => function() use($user) {
                        return new ActiveDataProvider([
                            'query' => JobUserTask::find()
                                ->innerJoinWith('task')
                                ->byUserId($user->id)
                        ]);
                    },
                ],
                'view'          => [
                    'class'      => 'api\modules\v1\controllers\actions\View',
                    'modelClass' => 'api\modules\v1\models\request\employee\TasksView',
                    'findModel'  => function($id) use ($user) {
                        return $this->assertModel(
                            JobUserTask::find()
                                ->innerJoinWith('task')
                                ->byTaskId($id)
                                ->byUserId($user->id)
                                ->one()
                        );
                    }
                ],
                'create'        => [
                    'class' => 'api\modules\v1\controllers\actions\Forbidden',
                ],
                'update'        => [
                    'class'      => 'api\modules\v1\controllers\actions\View',
                    'modelClass' => 'api\modules\v1\models\request\employee\TasksUpdate',
                    'findModel'  => function($id) use ($user) {
                        return $this->assertModel(
                            JobUserTask::find()
                                ->innerJoinWith('task', false)
                                ->byTaskId($id)
                                ->byUserId($user->id)
                                ->one()
                        );
                    },
                    'scenario'   => JobUserTask::SCENARIO_API_UPDATE,
                ],
                'photos-index'  => [
                    'class'      => 'api\modules\v1\controllers\actions\View',
                    'modelClass' => 'api\modules\v1\models\request\employee\TasksPhotosIndex',
                    'findModel'  => function($id) use ($user) {
                        return $this->assertModel(
                            JobUserTask::find()
                                ->innerJoinWith('task', false)
                                ->byTaskId($id)
                                ->byUserId($user->id)
                                ->one()
                        );
                    },
                ],
                'photos-create' => [
                    'class'      => 'api\modules\v1\controllers\actions\CreateRelated',
                    'modelClass' => 'api\modules\v1\models\request\employee\TasksPhotosCreate',
                    'findModel'  => function($id) use ($user) {
                        return $this->assertModel(
                            JobUserTask::find()
                                ->innerJoinWith('task', false)
                                ->byTaskId($id)
                                ->byUserId($user->id)
                                ->one()
                        );
                    },
                    'scenario'   => JobUserTask::SCENARIO_API_UPDATE,
                ],
            ];
        }

        throw new ForbiddenHttpException(Yii::t('api', 'You are not allowed to perform this action.'));
    }
}

<?php
namespace api\modules\v1\models\request\chief;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\Activity;
use common\models\ActivityUser;
use common\models\ActivityForm;
use api\modules\v1\models\request\View;

class ActivitiesView extends View
{
    protected function getData(array $filter = [])
    {
        $request = Yii::$app->request;
        $filter['owner']   = $request->get('owner');
        $filter['users']   = $request->get('users');
        $filter['forms']   = $request->get('forms');

        /** @var \common\models\Activity $model */
        /** @var \common\models\User $owner */

        $model = $this->getModel();
        $owner = $model->owner;
        $data  = $model->toArray();

        // Show activity owner
        if (isset($filter['owner']) && $filter['owner']) {
            $data['owner'] = $owner->toArray(['id', 'name', 'title']);
        }

        // Show activity users
        if (isset($filter['users']) && $filter['users']) {
            if (!in_array($model->status, [Activity::STATUS_DRAFT, Activity::STATUS_PENDING])) {
                $users = $model->getUsers()
                    ->andWhere(['!=' , ActivityUser::tableName() . '.status', ActivityUser::STATUS_REJECTED])
                    ->all();
            } else {
                $users = $model->getUsers()->all();
            }

            $data['users'] = ArrayHelper::toArray($users, [
                'common\models\ActivityUser' => [
                    'id'    => 'user_id',
                    'name'  => function($model) { return $model->user->name; },
                    'title' => function($model) { return $model->user->title; },
                    'status',
                    'reject_type',
                    'started_at',
                    'finished_at',
                    'owner_comment',
                    'user_comment',
                ],
            ]);
        }

        // Show activity forms
        if (isset($filter['forms']) && $filter['forms']) {
            $forms = $model->getForms()
                ->with('activity.type.form')
                ->all();

            $data['forms'] = ArrayHelper::toArray($forms, [
                'common\models\ActivityForm' => [
                    'object_id',
                    'status',
                    'object' => function(ActivityForm $model) { return $model->getFormObject(); },
                    'users'  => function(ActivityForm $model) { return $model->getFormStatuses(); },
                    'values' => function(ActivityForm $model) { return $model->getFormValues(); },
                    'schema' => function(ActivityForm $model) { return $model->getFormSchema(); },
                ]
            ]);
        }

        return $data;
    }
}

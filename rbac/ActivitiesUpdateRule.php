<?php
namespace api\rbac;

use Yii;
use yii\rbac\Rule;
use common\models\Activity;

class ActivitiesUpdateRule extends Rule
{
    /** @var string */
    public $name = 'api\rbac\ActivitiesUpdateRule';

    /** @inheritdoc */
    public function execute($user, $item, $params)
    {
        $allow = false;
        $id = Yii::$app->request->get('id'); // Activity ID

        if (Yii::$app->user->can('manager')) {
            // Пока пусто

        } elseif (Yii::$app->user->can('chief')) {
            // Проверяем является ли ПН владельцем активности
            $allow = Activity::find()
                ->where(['id' => $id, 'owner_id' => $user])
                ->exists();

        } elseif (Yii::$app->user->can('employee')) {
            // Проверяем назначена ли активность ПС'у
            $allow = Activity::find()
                ->with('users')
                ->where(['id' => $id, 'users.user_id' => $user])
                ->exists();
        }

        return $allow;
    }
}

<?php
namespace api\rbac;

use Yii;
use yii\rbac\Rule;
use common\models\User;

class UsersViewRule extends Rule
{
    /** @var string */
    public $name = 'api\rbac\UsersViewRule';

    /** @inheritdoc */
    public function execute($user, $item, $params)
    {
        $allow = false;
        $id = Yii::$app->request->get('id'); // The ID of the user from the request

        if (Yii::$app->user->can('manager')) {
            $allow = true;

        } elseif (Yii::$app->user->can('chief')) {
            $allow = $id == $user || User::find()->where(['id' => $id, 'chief_id' => $user])->exists();

        } elseif (Yii::$app->user->can('employee')) {
            $allow = $id == $user;
        }

        return $allow;
    }
}

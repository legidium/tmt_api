<?php
namespace api\modules\v1\models\request\employee;

use yii;
use api\modules\v1\models\request\View;
use yii\helpers\ArrayHelper;
use common\models\Team;
use common\models\User;

class TeamsView extends View
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        $ownerFields = ['id', 'group', 'department', 'name', 'title', 'username', 'email', 'phone'];
        $memberFields = ['id', 'name', 'title', 'username', 'email', 'phone'];;

        return ArrayHelper::toArray($this->getModel(), [
            'common\models\Team' => [
                'owner'   => function(Team $model) use($ownerFields) {
                    return $model->owner->toArray($ownerFields);
                },
                'members' => function(Team $model) use($memberFields) {
                    return array_map(function(User $user) use($memberFields) {
                        return $user->toArray($memberFields);
                    }, $model->members);
                },
            ]
        ]);
    }
}

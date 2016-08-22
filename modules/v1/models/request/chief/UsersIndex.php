<?php
namespace api\modules\v1\models\request\chief;

use yii;
use api\modules\v1\models\request\Index;
use common\models\User;

class UsersIndex extends Index
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        /** @var  \common\models\User $owner */
        $owner = $this->model;
        $fields = ['id', 'name', 'title', 'email', 'phone'];
        $extra = [];

        return array_map(function(User $model) use($owner, $fields, $extra) {
            return array_merge(
                $model->toArray($fields, $extra),
                ['is_owner' => (int)($model->id == $owner->id)]
            );
        }, $this->data->getModels());
    }
}

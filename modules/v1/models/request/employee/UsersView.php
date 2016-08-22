<?php
namespace api\modules\v1\models\request\employee;

use yii;
use api\modules\v1\models\request\View;

class UsersView extends View
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        $model = $this->getModel();
        $fields = ['id', 'name', 'title', 'email', 'phone'];
        $extra = [];

        return $model->toArray($fields, $extra);
    }
}

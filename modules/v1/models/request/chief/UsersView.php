<?php
namespace api\modules\v1\models\request\chief;

use yii;
use api\modules\v1\models\request\View;

class UsersView extends View
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        $model = $this->getModel();
        $fields = [];
        $extra = [];

        return $model->toArray($fields, $extra);
    }
}

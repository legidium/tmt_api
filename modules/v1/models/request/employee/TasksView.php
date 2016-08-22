<?php
namespace api\modules\v1\models\request\employee;

use yii;
use api\modules\v1\models\request\View;

class TasksView extends View
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        $request = Yii::$app->request;
        $filter['user'] = $request->get('user');

        $fields = [];
        $extra = array_filter([
            'user' => isset($filter['user']) && $filter['user'] ? 'user' : false,
        ]);

        $model = $this->getModel();

        return $model->toArray($fields, $extra);
    }
}

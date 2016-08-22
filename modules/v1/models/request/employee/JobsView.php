<?php
namespace api\modules\v1\models\request\employee;

use Yii;
use api\modules\v1\models\request\View;

class JobsView extends View
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        $request = Yii::$app->request;
        $filter['owner'] = $request->get('owner');
        $filter['user'] = $request->get('user');
        $filter['tasks'] = $request->get('tasks');

        $fields = [];
        $extra = array_filter([
            'owner' => isset($filter['owner']) && $filter['owner'] ? 'owner' : false,
            'user'  => isset($filter['user']) && $filter['user'] ? 'user' : false,
            'tasks' => isset($filter['tasks']) && $filter['tasks'] ? 'tasks' : false,
        ]);

        $model = $this->getModel();

        return $model->toArray($fields, $extra);
    }
}

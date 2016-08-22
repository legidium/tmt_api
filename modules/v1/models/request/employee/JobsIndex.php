<?php
namespace api\modules\v1\models\request\employee;

use Yii;
use api\modules\v1\models\request\Index;
use yii\base\Model;

class JobsIndex extends Index
{
    /** @inheritdoc */
    public function getData(array $filter = [])
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

        return array_map(function(Model $model) use($fields, $extra) {
            return $model->toArray($fields, $extra);
        }, $this->data->getModels());
    }
}

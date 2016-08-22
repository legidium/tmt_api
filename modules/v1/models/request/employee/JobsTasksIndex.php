<?php
namespace api\modules\v1\models\request\employee;

use Yii;
use api\modules\v1\models\request\Index;
use yii\base\Model;

class JobsTasksIndex extends Index
{
    /** @inheritdoc */
    public function getData(array $filter = [])
    {
        $request = Yii::$app->request;
        $filter['user'] = $request->get('user');

        $fields = [];
        $extra = array_filter([
            'user' => isset($filter['user']) && $filter['user'] ? 'user' : false,
        ]);

        return array_map(function(Model $model) use($fields, $extra) {
            return $model->toArray($fields, $extra);
        }, $this->data->getModels());
    }
}

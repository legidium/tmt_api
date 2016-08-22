<?php
namespace api\modules\v1\models\request\chief;

use Yii;
use api\modules\v1\models\request\Index;
use yii\base\Model;

class ActivitiesUsersIndex extends Index
{
    protected function getData(array $filter = [])
    {
        $request = Yii::$app->request;
        $fields = [];
        $extra = array_filter([
            'user'     => $request->get('user') ? 'user' : false,
            'object'   => $request->get('object') ? 'object' : false,
            'forms'    => $request->get('forms') ? 'forms' : false,
        ]);

        return array_map(function(Model $model) use($fields, $extra) {
            return $model->toArray($fields, $extra);
        }, $this->data->getModels());
    }
}

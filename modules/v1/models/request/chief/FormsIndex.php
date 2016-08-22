<?php
namespace api\modules\v1\models\request\chief;

use Yii;
use api\modules\v1\models\request\Index;
use yii\helpers\ArrayHelper;

class FormsIndex extends Index
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        return  ArrayHelper::toArray($this->data->getModels(), [
            'common\models\ActivityForm' => [
                'activity_id',
                'object_id',
                'status',
                'activity_title' => function($model) { return $model->activity->title; },
                'object_name'    => function($model) { return $model->object->name; },
                'object_address' => function($model) { return $model->object->name; },
            ]
        ]);
    }
}

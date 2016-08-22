<?php
namespace api\modules\v1\models\request\chief;

use yii;
use api\modules\v1\models\request\View;

class TasksPhotosIndex extends View
{
    /** @inheritdoc */
    public function getData(array $filter = [])
    {
        /** @var \common\models\Task $model */
        $model = $this->getModel();

        return $model->getTaskPhotos();
    }

    protected function response()
    {
        return [
            'default' => [
                'items' => function(TasksPhotosIndex $model) { return $model->getData(); },
            ]
        ];
    }
}

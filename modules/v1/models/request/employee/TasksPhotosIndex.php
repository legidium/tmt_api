<?php
namespace api\modules\v1\models\request\employee;

use yii;
use api\modules\v1\models\request\View;

class TasksPhotosIndex extends View
{
    /** @inheritdoc */
    public function getData(array $filter = [])
    {
        /** @var \common\models\JobUserTask $model */
        $model = $this->getModel();
        return $model->getUserPhotos();
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

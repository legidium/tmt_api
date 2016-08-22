<?php
namespace api\modules\v1\models\request\chief;

use api\modules\v1\models\request\Index;
use yii\helpers\ArrayHelper;
use common\models\Task;

class JobsTasksIndex extends Index
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        return ArrayHelper::toArray($this->data->getModels(), [
            'common\models\Task' => [
                'id',
                'job_id',
                'title',
                'comment',
                'photos' => 'taskPhotos',
            ]
        ]);
    }
}

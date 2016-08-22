<?php
namespace api\modules\v1\models\request\chief;

use api\modules\v1\models\request\Index;

class JobsIndex extends Index
{
    /** @inheritdoc */
    public function getData(array $filter = [])
    {
        //$filter['owner'] = 1;
        //$filter['tasks'] = 1;
        //$filter['users'] = 1;
        $show = [
            'owner' => isset($filter['owner']) ? (bool)$filter['owner'] : false,
            'tasks' => isset($filter['tasks']) ? (bool)$filter['tasks'] : false,
            'users' => isset($filter['users']) ? (bool)$filter['users'] : false,
        ];

        $fields = ['id', 'status', 'date_start', 'date_end', 'is_common', 'is_photo', 'title', 'comment'];
        $extra = array_keys(array_filter($show));

        $data = [];
        foreach ($this->data->getModels() as $job) {
            /** @var \common\models\Job $job */
            $data[] = $job->toArray($fields, $extra);
        }

        return $data;
    }
}

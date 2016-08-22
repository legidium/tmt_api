<?php
namespace api\modules\v1\models\request\chief;

use api\modules\v1\models\request\View;

class JobsView extends View
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        //$filter['owner'] = 1;
        //$filter['tasks'] = 1;
        //$filter['users'] = 1;

        $show = [
            'owner' => isset($filter['owner']) ? (bool)$filter['owner'] : false,
            'tasks' => isset($filter['tasks']) ? (bool)$filter['tasks'] : false,
            'users' => isset($filter['users']) ? (bool)$filter['users'] : false,
        ];

        return  $this->getModel()->toArray([], array_keys(array_filter($show)));
    }
}

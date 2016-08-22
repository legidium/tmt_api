<?php
namespace api\modules\v1\models\request\chief;

use yii;
use api\modules\v1\models\request\View;

class TasksView extends View
{
    protected function getData(array $filter = [])
    {
        return  $this->getModel()->toArray();
    }
}

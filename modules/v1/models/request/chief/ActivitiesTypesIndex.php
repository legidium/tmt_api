<?php
namespace api\modules\v1\models\request\chief;

use api\modules\v1\models\request\Index;

class ActivitiesTypesIndex extends Index
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        return $this->data->getModels();
    }
}

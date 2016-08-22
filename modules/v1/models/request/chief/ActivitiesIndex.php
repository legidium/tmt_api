<?php
namespace api\modules\v1\models\request\chief;

use api\modules\v1\models\request\Index;
use yii\helpers\ArrayHelper;

class ActivitiesIndex extends Index
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        return ArrayHelper::toArray($this->data->getModels(), [
            'common\models\Activity' => []
        ]);
    }
}

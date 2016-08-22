<?php
namespace api\modules\v1\models\request\common;

use yii;
use api\modules\v1\models\request\View;

class StandardsView extends View
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        //$filter['items'] = 1;

        $show = [
            'items' => isset($filter['items']) ? (bool)$filter['items'] : false,
        ];

        return  $this->getModel()->toArray([], array_keys(array_filter($show)));
    }
}

<?php
namespace api\modules\v1\models\request\chief;

use Yii;
use api\modules\v1\models\request\View;

class FormsView extends View
{
    protected function getData(array $filter = [])
    {
        return $this->getModel()->toArray([], ['form', 'statuses']);
    }
}

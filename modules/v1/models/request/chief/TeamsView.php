<?php
namespace api\modules\v1\models\request\chief;

use yii;
use api\modules\v1\models\request\View;

class TeamsView extends View
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        $model = $this->getModel();
        return $model->toArray([''], ['owner', 'members']);
    }
}

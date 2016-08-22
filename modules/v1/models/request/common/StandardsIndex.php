<?php
namespace api\modules\v1\models\request\common;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\Standarts;
use api\modules\v1\models\request\Index;

class StandardsIndex extends Index
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        return ArrayHelper::toArray($this->data->getModels(), [
            'common\models\Standarts' => [
                'id',
                'is_folder',
                'title',
                'items' => function($model) {
                    return Standarts::getChildren($model->id, 10, false);
                },
            ]
        ]);
    }
}

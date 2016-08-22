<?php
namespace api\modules\v1\models\request\employee;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\ActivityUser;
use api\modules\v1\models\request\Index;

class ActivitiesIndex extends Index
{
    /** @inheritdoc */
    protected function getData(array $filter = [])
    {
        $request = Yii::$app->request;
        $filter['owner'] = $request->get('owner');

        $fields = [];
        $extra  = [];

        // Show owner
        if (isset($filter['owner']) && $filter['owner']) {
            $extra = array_merge($extra, [
                'owner' => function(ActivityUser $model) {
                    return $model->activity->owner->toArray(['id', 'name', 'title']);
                }
            ]);
        }

        return ArrayHelper::toArray($this->data->getModels(), [
            'common\models\ActivityUser' => array_merge($fields, $extra)
        ]);
    }
}
